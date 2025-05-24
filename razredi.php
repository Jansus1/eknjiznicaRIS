<?php
//require_once 'vendor/autoload.php';
require_once 'db_connect.php';

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

class Clan {
    private $idClan;
    private $ime;
    private $priimek;
    private $izposoje;
    private static $seznamClanov = [
        1 => ['ime' => 'Ana', 'priimek' => 'Novak', 'izposoje' => 2],
        2 => ['ime' => 'Boris', 'priimek' => 'Kranjc', 'izposoje' => 0],
        3 => ['ime' => 'Cvetka', 'priimek' => 'Zupančič', 'izposoje' => 5],
    ];

    // Statična metoda za pridobivanje člana po ID
    public static function vrniClana(int $id): ?Clan {
        if (!isset(self::$seznamClanov[$id])) {
            return null;
        }

        $podatki = self::$seznamClanov[$id];
        $clan = new self();
        $clan->idClan = $id;
        $clan->ime = $podatki['ime'];
        $clan->priimek = $podatki['priimek'];
        $clan->izposoje = $podatki['izposoje'];
        return $clan;
    }
    
    public function vrniPodatkeOClan($idClan, $conn) : ?Clan {
        $this->idClan = $idClan;

        $stmt = $conn->prepare(
            "SELECT ime, priimek, izposoje FROM clan WHERE idClan = ?"
        );
        $stmt->bind_param('i', $idClan);

        if ($stmt->execute()) {
            $stmt->bind_result($ime, $priimek, $izposoje);
            if ($stmt->fetch()) {
                $this->ime = $ime;
                $this->priimek = $priimek;
                $this->izposoje = $izposoje;
                return $this;
            }
        }
        return null;
    }

    function vrniSteviloIzposoj(){
        return $this->izposoje;
    }
}

class ZMUporabnikIzposodiGradivo{
    private $idClan;
    private $idGradivo;

    function izposodiGradivo($idGradiva) : void{
        //Update idClan then call izposodiGradivo of Gradivo
        //calls Gradivo izposodi Gradivo, but that is not a good way to go about this, so it will be cut.
    }
    
    function prikaziPotrdilo($potrdilo){
        //Not implamented
    }

    function vrniRazlog($razlog){
        //Not implamented
    }
}

class ZMKnjižnicar{
    private $idKnjiznicar;
    protected $conn;

    public function __construct(mysqli $conn) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->conn = $conn;
        $this->idKnjiznicar = (int)$_SESSION['user']['id'];
    }

    function clanJeOdobren(){
        //implamented elsewhere
    }

    function skenirajGradivo($idGradivo): int{
        //Tukaj naj bi bila skenirana koda gradiva
        //Simulacija da se skenira:
        $ids = [];
        $stmt = $this->conn->prepare("
            SELECT k.idKnjiznice
            FROM knjiznice AS k
            JOIN gradiva   AS g ON g.idKnjiznice = k.idKnjiznice
            WHERE g.idGradiva = ?
        ");
        $stmt->bind_param('i', $idGradivo);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row['idKnjiznice'];
        }
        $res->free();
        $stmt->close();

        $randomKey = array_rand($ids);
        $randomLibraryId = $ids[$randomKey];

        return $randomLibraryId;
    }

    function podajPotrdilo($potrdilo){
        //Implamented elsewhere
    }

    function potrdiPoterdilo(){
        //Implamented elsewhere
    }
}

class KIzposodiGradivo{
    private $gradivo;
    private $clan;
    protected $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }


    function poisciGradivo($idGradivo){
        $sql = "
                SELECT 
                    g.idGradiva,
                    g.ime,
                    g.tipGradiva,
                    a.ime   AS imeAvtor,
                    a.priimek AS priimekAvtor,
                    r.steviloGradiv
                FROM gradiva g
                JOIN Avtor a       ON g.idAvtor     = a.idAvtor
                JOIN Clan c        ON g.idZalozba   = c.idClan
                JOIN razpolozljivost r ON g.idGradiva = r.idGradiva
                WHERE g.idGradiva = ?
                LIMIT 1
            ";

        if (! $stmt = $conn->prepare($sql)) {
            return null;
        }

        $stmt->bind_param('i', $idGradiva);
        $stmt->execute();

        $stmt->bind_result(
            $f_idGradiva,
            $f_ime,
            $f_tipGradiva,
            $f_imeAvtor,
            $f_priimekAvtor,
            $f_steviloGradiv
        );

        if ($stmt->fetch()) {
            $gradivo = new Gradivo();
            $gradivo->idGradiva = $f_idGradiva;
            $gradivo->ime = $f_ime;
            $gradivo->tipGradiva = $f_tipGradiva;
            $gradivo->avtor = trim($f_imeAvtor . ' ' . $f_priimekAvtor);
            $gradivo->razpolozljivost = $f_steviloGradiv;
            $stmt->close();
            $this->gradivo = $gradivo;
            return $gradivo;
        }

        $stmt->close();
        return null;
    }

    function skenirajClanskoIzkaznico($izkaznica): ?Clan {
        $id = 2;

        // Pridobimo člana iz statične zbirke
        $clan = Clan::vrniClana($id);

        if ($clan === null) {
            echo "Član s tem ID-jem ne obstaja.\n";
            return $clan;
        }

        $this->clan=$clan;

        return $clan;
    }

    function zabeležiTransakcijo($idClan, $idKnjiznicar, $idGradivo) : void{
        
        $datumIzposoje  = date('Y-m-d');
        $datumVracila = date('Y-m-d', strtotime('+14 days'));

        $sql = "
            INSERT INTO izposoja(
                idClan,
                idGradiva,
                datumIzposoje,
                datumVracila,
                idKnjiznicarja
            )VALUES
            (?,?,?,?,?)
        ";

        if (! $stmt = $this->conn->prepare($sql)) {
            return;
        }

        $stmt->bind_param(
            'iissi',
            $idClan,
            $idGradivo,
            $datumIzposoje,
            $datumVracila,
            $idKnjiznicar
        );

        $stmt->execute();
        $stmt->close();

        $sql = "UPDATE clan SET izposoje=izposoje+1;
        WHERE idClan = ?";

        if (! $stmt = $this->conn->prepare($sql)) {
            return;
        }

        $stmt->bind_param('i',$idClan);
        $stmt->execute();
        $stmt->close();
        return;
    }

    function odobriClana($izposoje) : bool{
        if($izposoje < 10){
            return true;
        }
        else{
            return false;
        }
    }

    function izdajPotrdilo(array $potrdilo) : string {
        echo '<div class="potrdilo-container">';
        echo '<h2>Potrdilo o izposoji</h2>';
        echo '<p><strong>ID izposoje:</strong> '      . htmlspecialchars($potrdilo['idIzposoja'])   . '</p>';
        echo '<p><strong>Uporabnik:</strong> '       . htmlspecialchars($potrdilo['uporabniskoIme']). '</p>';
        echo '<p><strong>Gradivo:</strong> '         . htmlspecialchars($potrdilo['naslov'])         . '</p>';
        echo '<p><strong>Datum izposoje:</strong> '  . htmlspecialchars($potrdilo['datumIzposoje'])  . '</p>';
        echo '<p><strong>Datum vračila:</strong> ' . ($potrdilo['datumVracila']
                ? htmlspecialchars($potrdilo['datumVracila'])
                : '<em>ni še vrnjeno</em>') . '</p>';
        echo '<div class="potrdilo-actions">';
        echo '<form action="knjiga.php" method="get" style="display:inline">';
        echo '  <input type="hidden" name="id" value="' . intval($potrdilo['idGradiva']) . '">';
        echo '  <button type="submit" class="btn btn-cancel">Nazaj na gradivo</button>';
        echo '</form> ';
        echo '<form action="izposoje.php" method="post" style="display:inline">';
        echo '  <input type="hidden" name="action" value="confirm">';
        echo '  <input type="hidden" name="idIzposoja" value="' . intval($potrdilo['idIzposoja']) . '">';
        echo '  <button type="submit" class="btn btn-confirm">Potrdi izposojo</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        return "This string can be a packed echo!";
    }

    function posliEPotrdilo(int $idClan) : bool{
        $stmt = $conn->prepare("
            SELECT 
                z.idIzposoja,
                c.uporabniskoIme,
                c.email,
                g.ime AS naslov,
                g.idGradiva,
                z.datumIzposoje,
                z.datumVracila
            FROM izposoja z
            JOIN clan c    ON z.idClan    = c.idClan
            JOIN gradiva g ON z.idGradiva = g.idGradiva
            WHERE z.idClan = ?
            ORDER BY z.datumIzposoje DESC
            LIMIT 1
        ");
        $stmt->bind_param('i',$idClan);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$row = $res->fetch_assoc()) {
            return false;
        }
        $stmt->close();

        $pdfFile = izdajPotrdilo($row);

        //this is how the mail would look like.
        // $mail = new PHPMailer(true);
        // try {
        //     $mail->setFrom('no-reply@eknjiznica.si','eKnjiznica');
        //     $mail->addAddress($row['email'], $row['uporabniskoIme']);
        //     $mail->Subject = 'Potrdilo o izposoji gradiva';
        //     $mail->Body    = "Spoštovani,\n\nv priponki vam pošiljamo potrdilo o vaši izposoji.\n\nLep pozdrav,\neKnjiznica.";
        //     $mail->addAttachment($pdfFile); //Used FPDF to create attachement.
        //     $mail->send();
        // } catch (Exception $e) {
        //     error_log("Mailer error: " . $mail->ErrorInfo);
        // }
        return true;
    }
}

class Gradivo{
    private $ime;
    private $avtor;
    private $idGradiva;
    private $tipGradiva;
    private $razpoložljivost;
    protected $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    function izposodiGradivo(int $idGradivo, int $idKnjiznice): bool {
        $this->idGradivo = $idGradivo;

        $sql = "
            UPDATE razpolozljivost
               SET steviloIzposojenih = steviloIzposojenih + 1
             WHERE idGradiva   = ?
               AND idKnjiznice = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (! $stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param(
            'ii',
            $this->idGradivo,
            $idKnjiznice
        );

        $ok = $stmt->execute();
        if (! $ok) {
            error_log("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        return $ok;
    }
}

class LokacijaGradiva{
    private $idGradiva;
    private $stanje;
    protected $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    function lokacijaGradiva ($idGradiva) : ?Knjiznice{
        $sql = "
            SELECT k.*
            FROM razpolozljivost r
            JOIN knjiznice    k ON r.idKnjiznice = k.idKnjiznice
            WHERE r.idGradiva = ?
            LIMIT 1
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $idGradiva);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $stmt->close();
            return new Knjiznice($row, $this->conn);
        }
        $stmt->close();
        return null;
    }
}

class Knjiznice{
    private $idKnjiznice;
    private $imeKnjiznice;
    private $naslov;
    private $telefon;
    private $email;
    private $opis;

    public function __construct(array $data) {
        $this->conn = $conn;
        $this->idKnjiznice = (int)$data['idKnjiznice'];
        $this->imeKnjiznice = $data['ime'];
        $this->naslov = $data['naslov'];
        $this->telefon = $data['telefon'];
        $this->email = $data['email'];
        $this->opis = $data['opis'];
    }

    //za rezervacijo
    function knjiznicaGradivo ($idGradiva) : Knjiznice{

    }
}
?>
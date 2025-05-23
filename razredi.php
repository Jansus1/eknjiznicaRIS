<?php
//require_once 'vendor/autoload.php';
require_once 'db_connect.php';

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

//Turn these publics to private!

class Clan {
    public $idClan;
    public $ime;
    public $priimek;
    public $izposoje;

    public function vrniPodatkeOClan($idClan, $conn) {
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
                return true;
            }
        }
        return false;
    }

    function vrniSteviloIzposoj(){
        return $this->izposoje;
    }
}

class ZMUporabnikIzposodiGradivo{
    public $idClan;
    public $idGradivo;

    function izposodiGradivo($idGradiva) : void{
        //Update idClan then call izposodiGradivo of Gradivo
        //calls Gradivo izposodi Gradivo, but that is not a good way to go about this, so it will be cut.
    }
    
    function prikaziPotrdilo($potrdilo){

    }

    function vrniRazlog($razlog){

    }
}

class ZMKnjižnicar{
    public $idKnjiznicar;
    protected $conn;

    public function __construct(mysqli $conn) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->conn = $conn;
        $this->idKnjiznicar = (int)$_SESSION['user']['id'];
    }

    function clanJeOdobren(){

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

    }

    function potrdiPoterdilo(){

    }

}

class KIzposodiGradivo{
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
            return $gradivo;
        }

        $stmt->close();
        return null;
    }

    function skenirajClanskoIzkaznico(){
        return 1; //No clue how to do this here?
    }

    function zabeležiTransakcijo($idClan, $idKnjiznicar, $idGradivo){
        
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

        if (! $stmt = $conn->prepare($sql)) {
            return null;
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
        return null;
    }

    function odobriClana($izposoje){
        if($izposoje < 10){
            return true;
        }
        else{
            return false;
        }
    }

    function izdajPotrdilo(array $potrdilo) {
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
        return null;
    }

    function posliEPotrdilo(int $idClan){
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
        return null;
    }
}

class Gradivo{
    public $ime;
    public $avtor;
    public $idGradiva;
    public $tipGradiva;
    public $razpoložljivost;
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
    public $idGradiva;
    public $stanje;

    function lokacijaGradiva ($idGradiva){

    }
}

class Knjiznice{
    public $idKnjiznice;
    public $idKnjige;
    public $imeKnjiznice;
    public $lokacijaKnjiznice;

    //za rezervacijo
    function knjiznicaGradivo ($idGradiva){
        $this->idKnjige = $idGradiva;
    }
}
?>
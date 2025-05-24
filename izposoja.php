<?php
session_start();
if (!(isset($_SESSION["user"])) || !(isset($_SESSION["user"]))=== 2) {
    header("Location: prijava.php");
    exit;
}
require_once 'db_connect.php';
require_once 'razredi.php';

$ok = false;
$error = '';

$kIzposodi = new KIzposodiGradivo($conn);
$zmKnjiznicar = new ZMKnjižnicar($conn);
$gradivo = new Gradivo($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && !empty($_POST['user_id']) 
    && !empty($_POST['material_id'])
) {
    $idClan    = (int)$_POST['user_id'];
    $idGradivo = (int)$_POST['material_id'];
    $idKnjiznice = $zmKnjiznicar->skenirajGradivo($idGradivo);
    if ($gradivo->izposodiGradivo($idGradivo, $idKnjiznice)) {
        $ok = true;
    } else {
        $error = 'Prišlo je do napake pri izposoji.';
    }
}

//idClan = $kIzposoid->skenirajClanskoIzkaznico();
//idMaterial = $kIzposoid->poisciGradivo();
$materials = [];
if ($res = $conn->query("SELECT idGradiva, ime FROM gradiva")) {
    while ($row = $res->fetch_assoc()) {
        $materials[] = [
            'id'   => (int)$row['idGradiva'],
            'name' => $row['ime']
        ];
    }
    $res->free();
}
$users = [];
if ($res = $conn->query("SELECT idClan, ime, priimek, izposoje FROM clan")) {
    while ($row = $res->fetch_assoc()) {
        $users[] = [
            'id'   => (int)$row['idClan'],
            'name' => trim($row["ime"] . ' ' . $row["priimek"]),
            'borrowed' => $row["izposoje"]
        ];
    }
    $res->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>eKnjiznica</title>
</head>
<body>
    <a href="index.php">
        <div class="naslov">
            <img class="glavnaSlika" src="files/image.png" alt="naslov" srcset="">
            <h1>eKnjiznica <small>digitalna knjiznica</small></h1>
        </div>
    </a>
 <div class="iskanje">
        <!-- <a class="active"href="#kjiznica">O knjižnici</a> to rata ko klikne gor (js da ga rederecta dol do tega odstavka-->
        <a href="index.php#onas">O nas</a>
        <a href="knjiznice.php">Lokacije</a>
        <a href="gradiva.php">Gradiva</a>
        <?php if (isset($_SESSION["user"])): ?>
            <a href="profil.php">Profile (<?= htmlspecialchars($_SESSION["user"]["ime"]) ?>)</a>
            <?php if (isset($_SESSION["user"]["tipUporabnika"]) && $_SESSION["user"]["tipUporabnika"] === 1): ?>
                <a href="zalozba.php">Zalozba</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="prijava.php">Prijava / Registracija</a>
        <?php endif; ?>
        <?php if (isset($_SESSION["user"]["tipUporabnika"]) && $_SESSION["user"]["tipUporabnika"] === 2): ?>
            <a class="active" href="izposoja.php">Izposoja</a>
            <a href="izposoje.php">Izposoje</a>
        <?php endif; ?>
        <div class="iskalnik">
            <form action="/action_page.php">
                <input type="text" placeholder="Išči.." name="search">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <?php if ($ok): ?>
        <div class="alert success">
            Izposoja je bila uspešno zabeležena!
        </div>
        <?php elseif ($error): ?>
        <div class="alert error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="izposoja">
                <label for="user_id">Za clana (Simulacija skeniranje kode clana)</label>
                <div class="search-wrapper">
                    <input type="text" class="search-input" data-target="user_id" placeholder="Išči clana…">
                    <select id="user_id" name="user_id" class="searchable-select" required>
                        <option value="" disabled selected>Izberite clana</option>
                        <?php foreach ($users as $user): ?>
                            <?php if ($kIzposodi->odobriClana($user['borrowed'])): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['name']) ?> - <?= htmlspecialchars($user['borrowed']) ?>
                                </option>
                            <?php else: ?>
                                <option disabled value="<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['name']) ?> - <?= htmlspecialchars($user['borrowed']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <label for="material_id">Za gradivo (Simulacija skeniranje kode gradiva)</label>
                <div class="search-wrapper">
                    <input type="text" class="search-input" data-target="material_id" placeholder="Išči gradivo…">
                    <select id="material_id" name="material_id" class="searchable-select" required>
                        <option value="" disabled selected>Izberite gradivo</option>
                        <?php foreach ($materials as $material): ?>
                            <option value="<?= $material['id'] ?>"><?= htmlspecialchars($material['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Potrdi izposojo</button>
        </div>
    </form>
</body>
                        <!--Missing potrdilo, call to dnevnik. -->
<script>
document.querySelectorAll('.search-input').forEach(input => {
  const targetId = input.dataset.target;
  const selectEl = document.getElementById(targetId);

  input.addEventListener('input', () => {
    const filter = input.value.toLowerCase();
    Array.from(selectEl.options).forEach(opt => {
      if (!opt.value) return opt.hidden = false;
      opt.hidden = !opt.text.toLowerCase().includes(filter);
    });
  });
});
</script>
</html>
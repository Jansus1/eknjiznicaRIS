<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: prijava.php");
    exit;
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
            <a class="active" href="profil.php">Profile (<?= htmlspecialchars($_SESSION["user"]["ime"]) ?>)</a>
            <?php if (isset($_SESSION["user"]["tipUporabnika"]) && $_SESSION["user"]["tipUporabnika"] === 1): ?>
                <a href="zalozba.php">Zalozba</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="prijava.php">Prijava / Registracija</a>
        <?php endif; ?>
        <?php if (isset($_SESSION["user"]["tipUporabnika"]) && $_SESSION["user"]["tipUporabnika"] === 2): ?>
            <a href="izposoja.php">Izposoja</a>
            <a href="izposoje.php">Izposoje</a>
        <?php endif; ?>
        <div class="iskalnik">
            <form action="/action_page.php">
                <input type="text" placeholder="Išči.." name="search">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <div class="profile-container">
        <h2>Vaš profil</h2>

        <div class="field">
            <label>Ime:</label>
            <span><?= htmlspecialchars($_SESSION["user"]["ime"]) ?></span>
        </div>

        <div class="field">
            <label>Priimek:</label>
            <span><?= htmlspecialchars($_SESSION["user"]["priimek"] ?? '') ?></span>
        </div>

        <div class="field">
            <label>Uporabniško ime:</label>
            <span><?= htmlspecialchars($_SESSION["user"]["uporabniskoIme"]) ?></span>
        </div>

        <div class="field">
            <label>Email:</label>
            <span><?= htmlspecialchars($_SESSION["user"]["email"]) ?></span>
        </div>

        <div class="actions">
            <a href="spremeni_geslo.php">Spremeni geslo</a>
            <form method="post" action="login.php" style="display:inline;">
                <input type="hidden" name="login" value="2">
                <button type="submit" class="logout">Odjava</button>
            </form>
        </div>
    </div>
</body>
</html>
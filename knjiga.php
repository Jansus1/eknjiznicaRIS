<?php
include 'db_connect.php';
session_start();
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
        <a class="active" href="gradiva.php">Gradiva</a>
        <?php if (isset($_SESSION["user"])): ?>
            <a href="profil.php">Profile (<?= htmlspecialchars($_SESSION["user"]["ime"]) ?>)</a>
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
    <br/>
<?php

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
}
else {
    echo "<div class='knjiga-container'>";
    echo "<img src='https://static.s4be.cochrane.org/app/uploads/2017/04/shutterstock_531145954.jpg' alt='Error image'>";
    echo "<div class='text'>";
    echo "<h2>Prišlo je do napake :(</h2>";
    echo "Napačen ID knjige.";

    exit;
}

$sql = "SELECT 
        g.*, 
        a.ime AS imeAvtor, 
        a.priimek AS priimekAvtor,
        c.ime AS imeZalozbe
        FROM 
        gradiva g
        JOIN 
        Avtor a ON g.idAvtor = a.idAvtor
        JOIN 
        Clan c ON g.idZalozba = c.idClan
        WHERE 
        g.idGradiva = $id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<div class='knjiga-container'>";
            if (!empty($row['ime'])) {
                echo "<h2 class='knjiga-title'>" . htmlspecialchars($row['ime']) . "</h2>";
            }

            $authorFull = trim($row['imeAvtor'] . ' ' . $row['priimekAvtor']);
            if (!empty($authorFull)) {
                echo "<h3 class='knjiga-author'>" . htmlspecialchars($authorFull) . "</h3>";
            }

            if (!empty($row['imeZalozbe'])) {
                echo "<h4 class='knjiga-publisher'>" . htmlspecialchars($row['imeZalozbe']) . "</h4>";
            }

            if (!empty($row['opis'])) {
                echo "<p class='knjiga-description'>" . trim(htmlspecialchars($row['opis'])) . "</p>";
            }

            if (!empty($row['tipGradiva'])) {
                echo "<p class='knjiga-type'><strong>Tip gradiva:</strong> " . htmlspecialchars($row['tipGradiva']) . "</p>";
            }
            echo "</div>";

            if (
                isset($_SESSION["user"]["tipUporabnika"])
                && $_SESSION["user"]["tipUporabnika"] === 2
            ) {
                echo "<div class='knjiga-actions'>";
                echo "<a href='urejaj_gradivo.php?id={$row['idGradiva']}' class='btn btn-edit'>Uredi</a>";
                echo "<form method='POST' action='odstrani_gradivo.php' style='display:inline;'>
                        <input type='hidden' name='idGradiva' value='{$row['idGradiva']}'>
                        <button type='submit' class='btn btn-delete'>Izbriši</button>
                    </form>";
                echo "</div>";

            } elseif (
                isset($_SESSION["user"]["tipUporabnika"])
                && $_SESSION["user"]["tipUporabnika"] === 0
            ) {
                echo "<div class='knjiga-actions'>";
                echo "<form method='POST' action='izposodi_gradivo.php' style='display:inline;'>
                        <input type='hidden' name='idGradiva' value='{$row['idGradiva']}'>
                        <button type='submit' class='btn btn-borrow'>Izposodi</button>
                    </form>";
                echo "<form method='POST' action='rezerviraj_gradivo.php' style='display:inline; margin-left:8px;'>
                        <input type='hidden' name='idGradiva' value='{$row['idGradiva']}'>
                        <button type='submit' class='btn btn-reserve'>Rezerviraj</button>
                    </form>";
                echo "</div>";
            }
            elseif (
                isset($_SESSION["user"]["tipUporabnika"])
                && $_SESSION["user"]["tipUporabnika"] === 1
            ) {
                echo "<div class='knjiga-actions'>";
                echo "<form method='POST' action='izposodi_gradivo.php' style='display:inline;'>
                        <input type='hidden' name='idGradiva' value='{$row['idGradiva']}'>
                        <button type='submit' class='btn btn-borrow'>Izposodi</button>
                    </form>";
                echo "<form method='POST' action='rezerviraj_gradivo.php' style='display:inline; margin-left:8px;'>
                        <input type='hidden' name='idGradiva' value='{$row['idGradiva']}'>
                        <button type='submit' class='btn btn-reserve'>Rezerviraj</button>
                    </form>";
                    $knjizniceQuery = mysqli_query($conn, "SELECT idKnjiznice, ime FROM knjiznice");
                    echo "<form method='POST' action='upravljaj_gradivo.php'>";
                    echo "<input type='hidden' name='idGradiva' value='{$row['idGradiva']}'>";
                    echo "<label for='idKnjiznice'>Knjižnica: </label>";
                    
                    echo "<select name='idKnjiznice' required>";
                    while ($knjiznica = mysqli_fetch_assoc($knjizniceQuery)) {
                        echo "<option value='{$knjiznica['idKnjiznice']}'>{$knjiznica['ime']}</option>";
                    }
                    echo "</select>";

                    echo "<label for='stevilo'>Število enot:</label>";
                    echo "<input type='number' name='stevilo' value='1' required min='1' style='width: 60px;'>";

                    echo "<button type='submit' name='action' value='dodaj' class='btn btn-add' style='margin-left: 5px;'>Dodaj</button>";

                    echo "<button type='submit' name='action' value='zbriši' class='btn btn-remove' style='margin-left: 5px;'>Zbriši</button>";
                    echo "</form>";
                echo "</div>";
            }
        }
    }  else {
    echo "<div class='knjiga-container'>";
        echo "<img src='…'>";
        echo "<div class='text'>";
        echo "<h2>Prišlo je do napake :(</h2>";
        echo "<p>Napačen ID knjige.</p>";
        echo "</div>";
    echo "</div>";
    echo "</body></html>";
    exit;
}
?>


</body>
</html>
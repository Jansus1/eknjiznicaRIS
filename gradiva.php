<?php
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

<?php
include 'db_connect.php';

$sql = "
    SELECT 
        g.idGradiva as idGradiva,
        g.ime as imeGradiva,
        c.ime AS imeZalozbe,
        a.ime AS imeAvtorja,
        a.priimek AS priimekAvtorja,
        g.tipGradiva as tipGradiva
    FROM gradiva g
    JOIN Clan c ON g.idZalozba = c.idClan
    JOIN AVTOR a ON g.idAvtor = a.idAvtor
";

$result = mysqli_query($conn, $sql);

echo "<div class='knjiznice-container'>";

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='knjiznica'>";
        echo "<a href='knjiga.php?id=" . $row['idGradiva'] . "'>";
        echo "<h2>" . $row['imeGradiva'] . "</h2>";
        echo "<p>" . $row['imeAvtorja'] . " " . $row['priimekAvtorja'] . "</p>";
        echo "<p>Zalozba: " . $row['imeZalozbe'] . "</p>";
        echo "<p>Tip gradiva: " . $row['tipGradiva'] . "</p>";
        echo "</div>";
        echo "</a>";
    }
} else {
    echo "Ni povezave s podatkovno bazo.";
}

echo "</div>";

mysqli_close($conn);
?>

</body>
</html>
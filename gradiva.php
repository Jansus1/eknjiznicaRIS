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
        <a href="index.php#onas">O nas</a>
        <a href="knjiznice.php">Lokacije</a>
        <a class="active" href="gradiva.php">Gradiva</a>
        <a href="prijava.php">Prijava/Registracija</a>
        <div class="iskalnik">
            <form action="/action_page.php">
              <input type="text" placeholder="Išči.." name="search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
          </div>
      </div>
<?php
include 'db_connect.php';
#$sql = "SELECT * FROM gradiva";

$sql = "
    SELECT 
        g.ime as imeGradiva,
        z.ime AS imeZalozbe,
        a.ime AS imeAvtorja,
        a.priimek AS priimekAvtorja
    FROM gradiva g
    JOIN ZALOZBA z ON g.idZalozba = z.idZalozba
    JOIN AVTOR a ON g.idAvtor = a.idAvtor
";

$result = mysqli_query($conn, $sql);

echo "<div class='knjiznice-container'>";

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='knjiznica'>";
        echo "<a href='knjiga.php?id=" . $row['idGradiva'] . "'>";
        echo "<h2>" . $row['imeGradiva'] . "</h2>";
        echo "<p>" . $row['imeAvtorja'] . " " . $row['priimekAvtor'] . "</p>";
        echo "<p>Zalozba: " . $row['imeZalozbe'] . "</p>";
        echo "<p>Tip gradiva " . $row['tipGradiva'] . "</p>";
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
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
        <a href="index.php#onas">O nas</a>
        <a class="active"href="knjiznice.php">Lokacije</a>
        <a href="gradiva.php">Gradiva</a>
        <?php if (isset($_SESSION["user"])): ?>
            <a href="profil.php">Profile (<?= htmlspecialchars($_SESSION["user"]["ime"]) ?>)</a>
        <?php else: ?>
            <a href="prijava.php">Prijava / Registracija</a>
        <?php endif; ?>
        <div class="iskalnik">
            <form action="/action_page.php">
              <input type="text" placeholder="Išči.." name="search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
          </div>
      </div>

        <div class="knjiznice">
            <h1>Naše knjižnice</h1>
            </br>
            <p>Na voljo imamo več knjižnic, ki so dostopne vsem uporabnikom.</p>
        </div>
        </br>



<?php
include 'db_connect.php';
$sql = "SELECT * FROM knjiznice";
$result = mysqli_query($conn, $sql);

echo "<div class='knjiznice-container'>";

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='knjiznica'>";
        echo "<h2>" . $row['ime'] . "</h2>";
        echo "<p>Telefon: " . $row['telefon'] . "</p>";
        echo "<p>Naslov: " . $row['naslov'] . "</p>";
        echo "<p>Elektronska pošta: " . $row['email'] . "</p>";
        echo "</div>";
    }
} else {
    echo "Ni povezave s podatkovno bazo.";
}

echo "</div>";

mysqli_close($conn);
?>


</body>
</html>
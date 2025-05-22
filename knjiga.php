<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>eKnjiznica</title>
      <style>
    .knjiga-container {
      display: flex;
      align-items: center;
      gap: 20px;
      padding: 20px;
      max-width: 800px;
      margin: auto;
    }
    .knjiga-container > img {
      width: 350px;
      height: auto;
      border-radius: 8px;
    }
    .knjiga-container > .text {
      font-size: 18px;
    }
  </style>
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
        <a href="gradiva.php">Gradiva</a>
        <a href="prijava.php">Prijava/Registracija</a>
        <div class="iskalnik">
            <form action="/action_page.php">
              <input type="text" placeholder="Išči.." name="search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
          </div>
      </div>

    <br/>
    
<?php
include 'db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    echo "<div class='knjiga-container'>";
    echo "<img src='https://static.s4be.cochrane.org/app/uploads/2017/04/shutterstock_531145954.jpg' alt='Error image'>";
    echo "<div class='text'>";
    echo "<h2>Prišlo je do napake :(</h2>";
    echo "Napačen ID knjige.";

    exit;
}
    
    // Now use $id safely in your SQL
    #$sql = "SELECT * FROM gradiva WHERE idGradiva = $id";
    $sql = "SELECT 
            g.*, 
            a.ime AS imeAvtor, 
            a.priimek AS priimekAvtor,
            z.ime AS imeZalozbe
            FROM 
            gradiva g
            JOIN 
            Avtor a ON g.idAvtor = a.idAvtor
            JOIN 
            Zalozba z ON g.idZalozba = z.idZalozba
            WHERE 
            g.idGradiva = $id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
            echo "<div class='knjiga-container'>";
            echo "<h2>" . $row['ime'] . "</h2>";
            echo "<h3>" . $row['imeAvtor'] . $row['priimekAvtor'] ."</h3>";   
            echo "<h4>" . $row['imeZalozbe'] . "</h4>";
            echo "<br/>";
            echo "<p>Opis " . $row['opis'] . "</p>";
            echo "<br/>";
            echo "<h3>Tip Gradiva: " . $row['tipGradiva'] . "</p>";
            echo "</div>";
            }
        }  else {
    echo "<div class='knjiga-container'>";
    echo "<img src='https://static.s4be.cochrane.org/app/uploads/2017/04/shutterstock_531145954.jpg' alt='Error image'>";
    echo "<div class='text'>";
    echo "<h2>Prišlo je do napake :(</h2>";
    echo "Ni povezave s podatkovno bazo.";

    exit;
}
?>


</body>
</html>
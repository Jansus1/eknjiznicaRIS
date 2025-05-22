<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        $message = "Nova gesla se ne ujemata.";
    } else {
        $stmt = $conn->prepare("SELECT geslo FROM clan WHERE idClan = ?");
        $stmt->bind_param("i", $_SESSION["user"]["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($currentPassword, $user["geslo"])) {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE clan SET geslo = ? WHERE idClan = ?");
            $update->bind_param("si", $newHash, $_SESSION["user"]["id"]);
            if ($update->execute()) {
                $message = "Geslo uspešno spremenjeno.";
            } else {
                $message = "Napaka pri posodabljanju gesla.";
            }
        } else {
            $message = "Trenutno geslo ni pravilno.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>eKnjiznica</title>
</head>
<body>

<div class="form-container">
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
    <h2>Spremeni geslo</h2>
    <form method="POST">
        <label>Trenutno geslo</label>
        <input type="password" name="current_password" required>
        <br>
        <label>Novo geslo</label>
        <input type="password" name="new_password" required>
        <br>
        <label>Potrdi novo geslo</label>
        <input type="password" name="confirm_password" required>
        <br>
        <input type="submit" value="Shrani spremembe">
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</div>

</body>
</html>

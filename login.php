<?php
$servername = "localhost";
$username = "root";
$password = ""; // default password for XAMPP
$dbname = "eknjiznica";

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uporabniskoIme = $_POST["uporabniskoIme"];
    $geslo = $_POST["geslo"];

    $stmt = $pdo->prepare("SELECT * FROM clan WHERE uporabniskoIme = ?");
    $stmt->execute([$uporabniskoIme]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($geslo, $user["geslo"])) {
        echo "Prijava uspešna! Dobrodošel, " . htmlspecialchars($user["ime"]) . "!";
        // Here you could redirect to a dashboard or session_start()
    } else {
        echo "Napačno uporabniško ime ali geslo.";
    }
}
?>
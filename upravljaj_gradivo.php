<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user"]) && $_SESSION["user"]["tipUporabnika"] == 1) {
    $idGradiva = intval($_POST['idGradiva']);
    $idKnjiznice = intval($_POST['idKnjiznice']);
    $stevilo = intval($_POST['stevilo']);
    $action = $_POST['action'];

    $check = mysqli_query($conn, "SELECT * FROM razpolozljivost WHERE idGradiva = $idGradiva AND idKnjiznice = $idKnjiznice");
    if (mysqli_num_rows($check) > 0) {
        if ($action === 'dodaj') {
            mysqli_query($conn, "UPDATE razpolozljivost SET steviloGradiv = steviloGradiv + $stevilo WHERE idGradiva = $idGradiva AND idKnjiznice = $idKnjiznice");
        } elseif ($action === 'zbriši') {
            mysqli_query($conn, "UPDATE razpolozljivost SET steviloGradiv = GREATEST(steviloGradiv - $stevilo, 0) WHERE idGradiva = $idGradiva AND idKnjiznice = $idKnjiznice");
        }
    }

    header("Location: knjiga.php");
    exit;
}
?>
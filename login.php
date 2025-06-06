<?php
require_once "db_connect.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $uporabniskoIme = $_POST["uporabniskoIme"];
    $geslo = $_POST["geslo"];
    if($_POST["login"] == "1") {
        $stmt = $conn->prepare("SELECT * FROM clan WHERE uporabniskoIme = ?");
        $stmt->bind_param("s", $uporabniskoIme);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($geslo, $user["geslo"])) {
            $_SESSION["user"] = [
            "id" => $user["idClan"],
            "ime" => $user["ime"],
            "priimek" => $user["priimek"],
            "uporabniskoIme" => $user["uporabniskoIme"],
            "email" => $user["email"],
            "tipUporabnika" => $user["tipUporabnika"]
            ];
            header("Location: index.php");
            exit;
        } else {
            echo "Napačno uporabniško ime ali geslo.";
        }
    }
    elseif($_POST["login"] == "0") {
        $ime = $_POST["ime"];
        $priimek = $_POST["priimek"];
        $naslov = $_POST["naslov"];
        $potrdiGeslo = $_POST["geslo"];
        $email = $_POST["email"];

        if ($geslo !== $potrdiGeslo) {
            echo "Gesli se ne ujemata.";
            exit;
        }

        $hashedPassword = password_hash($geslo, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO clan 
            (uporabniskoIme, geslo, ime, priimek, naslov, email, izposoje, clanarina, tipUporabnika) 
            VALUES (?, ?, ?, ?, ?, ?, 0, 0, 0)");
        $stmt->bind_param("ssssss", $uporabniskoIme, $hashedPassword, $ime, $priimek, $naslov, $email);

        if ($stmt->execute()) {
            $idClan = $conn->insert_id;
            $_SESSION["user"] = [
            "id" => $idClan,
            "ime" => $ime,
            "priimek" => $priimek,
            "uporabniskoIme" => $uporabniskoIme,
            "email" => $email
            ];
            header("Location: index.php");
            exit;
        } else {
            echo "Uporabniško ime ali email že obstaja.";
            exit;
        }

        $stmt->close();
    }
    else {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>
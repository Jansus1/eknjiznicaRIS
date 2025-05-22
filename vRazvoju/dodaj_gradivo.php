<?php
$pdo = new PDO('mysql:host=localhost;dbname=eknjiznica;charset=utf8', 'root', ''); // Prilagodi prijavne podatke

// Obdelaj sliko
$uploadDir = 'files/';
$slikaIme = basename($_FILES['slika']['name']);
$celaPot = $uploadDir . time() . '_' . $slikaIme;

if (move_uploaded_file($_FILES['slika']['tmp_name'], $celaPot)) {
    // Shrani podatke v bazo
    $stmt = $pdo->prepare("INSERT INTO gradiva (ime, tipGradiva, idKnjiznice, idAvtor, slika) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['ime'],
        $_POST['tipGradiva'],
        $_POST['idKnjiznice'],
        !empty($_POST['idAvtor']) ? $_POST['idAvtor'] : null,
        $celaPot
    ]);
    echo "Gradivo uspešno dodano!";
} else {
    echo "Napaka pri nalaganju slike.";
}
?>

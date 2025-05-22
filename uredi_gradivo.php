<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION["user"])) {
    header('Location: index.php');
    exit;
}
$idGradiva     = (int) ($_POST['idGradiva'] ?? -1);
$title         = trim($_POST['title'] ?? '');
$materialType  = $_POST['material_type'] ?? '';
$libraryId     = (int) ($_POST['library_id'] ?? 0);
$authorId      = (int) ($_POST['author_id'] ?? 0);
$coverImageUrl = trim($_POST['cover_image'] ?? '');
$amount        = (int) ($_POST['amount'] ?? 0);
$description   = trim($_POST['description'] ?? '');

$errors = [];

if ($title === '') {
    $errors[] = 'Ime gradiva je obvezno.';
}
if (!in_array($materialType, ['knjiga','časopis','dvd','usb'], true)) {
    $errors[] = 'Neveljaven tip gradiva.';
}
if ($idGradiva <= -1) {
    $errors[] = 'Napaka pri izbiri gradiva.';
}
if ($libraryId <= 0) {
    $errors[] = 'Izberite knjižnico.';
}
if ($authorId <= 0) {
    $errors[] = 'Izberite avtorja.';
}
if ($coverImageUrl !== '' && !filter_var($coverImageUrl, FILTER_VALIDATE_URL)) {
    $errors[] = 'Neveljaven URL za sliko.';
}

if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
    }
    echo '<p><a href="add_material_form.php">Nazaj na obrazec</a></p>';
    exit;
}

$stmt = $conn->prepare(
    "UPDATE gradiva 
     SET 
        ime        = ?,
        tipGradiva = ?,
        idKnjiznice= ?,
        idAvtor    = ?,
        slika      = ?,
        opis       = ?
     WHERE idGradiva = ?"
);
$stmt->bind_param(
    'ssiissi',
    $title,
    $materialType,
    $libraryId,
    $authorId,
    $coverImageUrl,
    $description,
    $idGradiva
);

if ($stmt->execute()) {
    $stmta = $conn->prepare(
        "UPDATE razpolozljivost 
        SET
        steviloGradiv = ?
        WHERE idGradiva = ?"
    );
    $stmta->bind_param(
        "ii",
        $amount,
        $idGradiva
    );
    if ($stmta->execute()) {
        header('Location: gradiva.php');
        exit;
    }
    else{
        echo '<p style="color:red;">Napaka pri vstavljanju podatkov: '
         . htmlspecialchars($stmt->error) . '</p>';
        echo '<p><a href="zalozba.php">Poskusi znova</a></p>';
        exit;
    }
} else {
    echo '<p style="color:red;">Napaka pri vstavljanju podatkov: '
         . htmlspecialchars($stmt->error) . '</p>';
    echo '<p><a href="zalozba.php">Poskusi znova</a></p>';
    exit;
}
?>
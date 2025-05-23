<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION["user"])) {
    header('Location: index.php');
    exit;
}

$title         = trim($_POST['title'] ?? '');
$materialType  = $_POST['material_type'] ?? '';
$libraryId     = (int) ($_POST['library_id'] ?? 0);
$publisherId   = (int) ($_POST['publisher_id'] ?? 0);
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
if ($libraryId <= 0) {
    $errors[] = 'Izberite knjižnico.';
}
if ($publisherId <= 0) {
    $errors[] = 'Prišlo je do napake z založbo.';
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
    "INSERT INTO gradiva 
      (ime, tipGradiva, idKnjiznice, idZalozba, idAvtor, slika, opis)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    'ssiiiss',
    $title,
    $materialType,
    $libraryId,
    $publisherId,
    $authorId,
    $coverImageUrl,
    $description
);

if ($stmt->execute()) {
    $newGradivaId = $conn->insert_id;

    $availStmt = $conn->prepare(
        "INSERT INTO razpolozljivost
            (idGradiva, idKnjiznice, status, steviloGradiv, steviloIzposojenih, steviloRezerviranih)
         VALUES (?, ?, ?, ?, 0, 0)"
    );

    $initialCount = $amount;
    $status = 'Prosto';

    $availStmt->bind_param(
        'iisi',
        $newGradivaId,
        $libraryId,
        $status,
        $initialCount
    );

    if (!$availStmt->execute()) {
        error_log("Napaka pri vnosu za razpolozljivost: " . $availStmt->error);
    }

    header('Location: gradiva.php?added=1');
    exit;
} else {
    echo '<p style="color:red;">Napaka pri vstavljanju podatkov: '
         . htmlspecialchars($stmt->error) . '</p>';
    echo '<p><a href="zalozba.php">Poskusi znova</a></p>';
    exit;
}
?>
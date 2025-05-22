<?php
session_start();
require_once 'db_connect.php';
$authors = [];
if ($res = $conn->query("SELECT idAvtor, ime, priimek FROM avtor")) {
    while ($row = $res->fetch_assoc()) {
        $authors[] = [
            'id'   => (int)$row['idAvtor'],
            'name' => $row['ime'] . ' ' . $row['priimek']
        ];
    }
    $res->free();
}

$libraries = [];
if ($res = $conn->query("SELECT idKnjiznice, ime FROM knjiznice")) {
    while ($row = $res->fetch_assoc()) {
        $libraries[] = [
            'id'   => (int)$row['idKnjiznice'],
            'name' => $row['ime']
        ];
    }
    $res->free();
}
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
        <a href="knjiznice.php">Lokacije</a>
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

    <div class="vsebina">
        <h2 class="vsebina-title">
            Dobrodošli, založba <?= htmlspecialchars($_SESSION["user"]["ime"]) ?>!
        </h2>
        <p class="vsebina-paragraph">Tukaj lahko dodajate gradiva.</p>
        <form method="POST" action="dodaj_gradivo.php">
            <input type="hidden" name="publisher_id" value="<?= (int)$_SESSION["user"]["id"] ?>">

            <label for="title">Ime gradiva</label>
            <input type="text" id="title" name="title" placeholder="Naslov gradiva" required><br>

            <label for="author_id">Avtor</label>
            <div class="search-wrapper">
            <input type="text" class="search-input" data-target="author_id" placeholder="Išči avtorja…">
            <select id="author_id" name="author_id" class="searchable-select" required>
                <option value="" disabled selected>Izberite avtorja</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>

            <label for="material_type">Tip gradiva</label>
            <select id="material_type" name="material_type" required>
                <option value="book">Knjiga</option>
                <option value="magazine">Časopis</option>
                <option value="dvd">DVD</option>
                <option value="usb">USB</option>
            </select><br>

            <label for="library_id">Za knjižnico</label>
            <div class="search-wrapper">
            <input type="text" class="search-input" data-target="library_id" placeholder="Išči knjižnico…">
            <select id="library_id" name="library_id" class="searchable-select" required>
                <option value="" disabled selected>Izberite knjižnico</option>
                <?php foreach ($libraries as $library): ?>
                    <option value="<?= $library['id'] ?>"><?= htmlspecialchars($library['name']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>

            <label for="cover_image">Povezava do slike</label>
            <input type="url" id="cover_image" name="cover_image" placeholder="https://…"><br>

            <input type="submit" value="Shrani spremembe">
        </form>
    </div>
</body>
<script>
document.querySelectorAll('.search-input').forEach(input => {
  const targetId = input.dataset.target;
  const selectEl = document.getElementById(targetId);

  input.addEventListener('input', () => {
    const filter = input.value.toLowerCase();
    Array.from(selectEl.options).forEach(opt => {
      if (!opt.value) return opt.hidden = false;
      opt.hidden = !opt.text.toLowerCase().includes(filter);
    });
  });
});
</script>

</html>
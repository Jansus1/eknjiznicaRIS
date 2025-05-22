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
<form action="dodaj_gradivo.php" method="POST" enctype="multipart/form-data">
    <label>Ime gradiva:</label>
    <input type="text" name="ime" required><br>

    <label>Tip gradiva:</label>
    <input type="text" name="tipGradiva" required><br>

    <label>ID knjižnice:</label>
    <input type="number" name="idKnjiznice" required><br>

    <label>ID avtorja (opcijsko):</label>
    <input type="number" name="idAvtor"><br>

    <label>Slika gradiva:</label>
    <input type="file" name="slika" accept="image/*" required><br>

    <input type="submit" value="Dodaj gradivo">
</form>


</body>
</html>
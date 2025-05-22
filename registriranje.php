<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>eKnjiznica</title>
</head>
<body class="body-login">
    <div class="left-side">
        <a href="index.php">
            <div class="naslov">
                <img class="glavnaSlika" src="files/image.png" alt="naslov" srcset="">
                <h1>eKnjiznica <small>digitalna knjiznica</small></h1>
            </div>
        </a>
    </div>

    <div class="right-side">
        <h2>Registracija</h2>

        <form class="login-box" method="POST" action="login.php">
        <input type="hidden" name="login" value="0">
        <input type="text" name="uporabniskoIme" placeholder="Uporabniško ime" required>
        <input type="text" name="ime" placeholder="Ime" required>
        <input type="text" name="priimek" placeholder="Priimek" required>
        <input type="text" name="naslov" placeholder="Domači naslov" required>
        </br>
        <input type="password" name="geslo" placeholder="Geslo" required>
        <input type="password" name="potrdiGeslo" placeholder="Potrdi geslo" required>
        <input type="text" name="email" placeholder="Email" required>
        <button type="submit">Registriraj se!</button>
    </div>
</body>
</html>
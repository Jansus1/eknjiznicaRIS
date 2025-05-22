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
        <h2>Login</h2>
        <form class="login-box" method="POST" action="login.php">
        <input type="hidden" name="login" value="1">
        <input type="text" name="uporabniskoIme" placeholder="Uporabniško ime" required>
        <input type="password" name="geslo" placeholder="Geslo" required>
        <button type="submit">Prijavi se!</button>
        </br>
        <small>Še nimate računa?</small>
        <small><a href="registriranje.php">Registrirajte se tukaj!</a></small>
        <!-- <small><a href="nakupClanarine.php">Kupite članarino!</a></small> -->
        </form>
        
        <?php if (isset($_GET['error'])): ?>
        <div class="error"><?php $_GET['error']; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
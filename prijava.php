<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="files/stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>eKnjiznica</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        height: 100vh;
        display: flex;
    }

    .right-side {
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f5f5f5;
    }

    .login-box {
        background-color: #fff;
        padding: 30px;
        border: 2px solid #ddd;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        width: 300px;
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .login-box input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #0066cc;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .login-box input[type="submit"]:hover {
        background-color: #0052a3;
    }
    .left-side {
        background-color: #6e0511;
        width: 50%;
        height: 100vh; /* Full viewport height */
        display: flex;
        justify-content: center; /* center horizontally */
        align-items: center;     /* center vertically */
    }

    .naslov {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #6e0511;
    }

    .naslov > h1 {
        font-size: 2em;
        color: #a9a9a9;
    }

    .naslov > h1 small {
        font-size: 0.5em;
        color: #000000;
    }

    .glavnaSlika {
        width: 2em;
        height: 2em;
        margin-right: 5px;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }




</style>
</head>
<body>
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
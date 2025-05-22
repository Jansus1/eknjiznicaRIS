<?php
session_start();
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
        <!-- <a class="active"href="#kjiznica">O knjižnici</a> to rata ko klikne gor (js da ga rederecta dol do tega odstavka-->
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
        <h2 class="vsebina-title">Dobrodošli v eKnjiznici</h2>
        <p class="vsebina-paragraph">S pomočjo naše spletne in mobilne aplikacije lahko enostavno brskate po celotnem gradivu knjižnice, si ogledate podrobne opise knjig, zvočnih in e-knjig ter rezervirate ali izposodite izbrano gradivo kar od doma.</p>
        <p class="vsebina-paragraph">Člani lahko kadarkoli preverite seznam svojih izposoj, datume vračila in možnost podaljšanja. Rezervacije izvajate v nekaj klikih, sistem pa vas bo obvestil takoj, ko bo gradivo na voljo.</p>
        <ul class="vsebina-list">
            <li>Izposoja in rezervacija gradiva v nekaj korakih</li>
            <li>Pregled vaših aktivnih in preteklih izposoj</li>
            <li>Obvestila o vračilu in podaljšanju</li>
            <li>Iskanje po avtorju, naslovu ali ključnih besedah</li>
        </ul>
        <p class="vsebina-paragraph">Na spletišču in v aplikaciji boste našli tudi pregled lokacij vseh knjižnic, delovni čas, kontaktne podatke in posebne dogodke. Za knjižničarje in zaposlene pa so na voljo dodatna orodja za hitro urejanje gradiva, evidentiranje zamud in upravljanje članstva.</p>
        <p class="vsebina-paragraph">Ne glede na to, ali ste redni obiskovalec, nov član ali gost – eKnjiznica vam omogoča intuitiven dostop do bogatega knjižničnega gradiva 24/7, kjerkoli že ste.</p>
    </div>
</body>
</html>
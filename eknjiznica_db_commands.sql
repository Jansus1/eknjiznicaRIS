CREATE DATABASE eknjiznica;

CREATE TABLE knjiznice (
    idKnjiznice INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(255) NOT NULL,
    naslov VARCHAR(255) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE gradiva (
    idGradiva INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(255) NOT NULL,
    avtor VARCHAR(255) NOT NULL,
    tipGradiva VARCHAR(100) NOT NULL,
    idKnjiznice INT NOT NULL,
    FOREIGN KEY (idKnjiznice) REFERENCES knjiznice(idKnjiznice)
);

CREATE TABLE razpolozljivost (
    idGradiva INT NOT NULL,
    idKnjiznice INT NOT NULL,
    steviloGradiv INT NOT NULL,
    steviloIzposojenih INT NOT NULL,
    steviloRezerviranih INT NOT NULL,
    PRIMARY KEY (idGradiva, idKnjiznice),
    FOREIGN KEY (idGradiva) REFERENCES gradiva(idGradiva),
    FOREIGN KEY (idKnjiznice) REFERENCES knjiznice(idKnjiznice)
);

CREATE TABLE knjizicar (
    idKnjizicar INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(100) NOT NULL,
    priimek VARCHAR(100) NOT NULL,
    naslov VARCHAR(255) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    delovnoMesto VARCHAR(100) NOT NULL
);

CREATE TABLE clan (
    idClan INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(100) NOT NULL,
    priimek VARCHAR(100) NOT NULL,
    naslov VARCHAR(255) NOT NULL,
    izposoje VARCHAR(20) NOT NULL,
    clanarina BOOLEAN NOT NULL,
    jeKnjiznicar BOOLEAN NOT NULL,
    idKnjizicar INT,
    FOREIGN KEY (idKnjizicar) REFERENCES knjizicar(idKnjizicar)
);
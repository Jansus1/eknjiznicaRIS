CREATE DATABASE eknjiznica;

CREATE TABLE knjiznice (
    idKnjiznice INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(255) NOT NULL,
    naslov VARCHAR(255) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);


CREATE TABLE zalozba (
    idZalozba INT PRIMARY KEY AUTO_INCREMENT,
    uporabniskoIme VARCHAR(50) UNIQUE NOT NULL,
    geslo VARCHAR(255) NOT NULL,
    ime VARCHAR(255) NOT NULL,
    naslov VARCHAR(255),
    email VARCHAR(255),
    telefon VARCHAR(20)
);

CREATE TABLE clan (
    idClan INT PRIMARY KEY AUTO_INCREMENT,
    uporabniskoIme VARCHAR(50) UNIQUE NOT NULL,
    geslo VARCHAR(255) NOT NULL,
    ime VARCHAR(100) NOT NULL,
    priimek VARCHAR(100) NOT NULL,
    naslov VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    izposoje VARCHAR(20) NOT NULL,
    clanarina BOOLEAN NOT NULL,
    jeKnjiznicar BOOLEAN NOT NULL
);

CREATE TABLE avtor (
    idAvtor INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(100) NOT NULL,
    priimek VARCHAR(100) NOT NULL
);

CREATE TABLE gradiva (
    idGradiva INT PRIMARY KEY AUTO_INCREMENT,
    ime VARCHAR(255) NOT NULL,
    tipGradiva VARCHAR(100) NOT NULL,
    idKnjiznice INT NOT NULL,
    idZalozba INT,
    FOREIGN KEY (idZalozba) REFERENCES zalozba(idZalozba),
    FOREIGN KEY (idKnjiznice) REFERENCES knjiznice(idKnjiznice),
    idAvtor INT,
    FOREIGN KEY (idAvtor) REFERENCES avtor(idAvtor)
);

CREATE TABLE razpolozljivost (
    idGradiva INT NOT NULL,
    idKnjiznice INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    steviloGradiv INT NOT NULL,
    steviloIzposojenih INT NOT NULL,
    steviloRezerviranih INT NOT NULL,
    PRIMARY KEY (idGradiva, idKnjiznice),
    FOREIGN KEY (idGradiva) REFERENCES gradiva(idGradiva),
    FOREIGN KEY (idKnjiznice) REFERENCES knjiznice(idKnjiznice)
);

CREATE TABLE izposoja (
    idIzposoja INT PRIMARY KEY AUTO_INCREMENT,
    idClan INT NOT NULL,
    idGradiva INT NOT NULL,
    datumIzposoje DATE NOT NULL,
    datumVracila DATE,
    FOREIGN KEY (idClan) REFERENCES clan(idClan),
    FOREIGN KEY (idGradiva) REFERENCES gradiva(idGradiva)
);


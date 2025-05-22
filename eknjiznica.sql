-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 06:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eknjiznica`
--

-- --------------------------------------------------------

--
-- Table structure for table `avtor`
--

CREATE TABLE `avtor` (
  `idAvtor` int(11) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `priimek` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avtor`
--

INSERT INTO `avtor` (`idAvtor`, `ime`, `priimek`) VALUES
(1, 'France', 'Prešeren'),
(2, 'Ivan', 'Cankar');

-- --------------------------------------------------------

--
-- Table structure for table `clan`
--

CREATE TABLE `clan` (
  `idClan` int(11) NOT NULL,
  `uporabniskoIme` varchar(50) NOT NULL,
  `geslo` varchar(255) NOT NULL,
  `ime` varchar(100) NOT NULL,
  `priimek` varchar(100) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `izposoje` varchar(20) NOT NULL,
  `clanarina` tinyint(1) NOT NULL,
  `jeKnjiznicar` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gradiva`
--

CREATE TABLE `gradiva` (
  `idGradiva` int(11) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `tipGradiva` varchar(100) NOT NULL,
  `idKnjiznice` int(11) NOT NULL,
  `idZalozba` int(11) DEFAULT NULL,
  `idAvtor` int(11) DEFAULT NULL,
  `slika` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `izposoja`
--

CREATE TABLE `izposoja` (
  `idIzposoja` int(11) NOT NULL,
  `idClan` int(11) NOT NULL,
  `idGradiva` int(11) NOT NULL,
  `datumIzposoje` date NOT NULL,
  `datumVracila` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `knjiznice`
--

CREATE TABLE `knjiznice` (
  `idKnjiznice` int(11) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `opis` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knjiznice`
--

INSERT INTO `knjiznice` (`idKnjiznice`, `ime`, `naslov`, `telefon`, `email`, `opis`) VALUES
(1, 'Centralna knjižnica', 'Trg republike 1, Ljubljana', '01 123 4567', 'info@centralna.si', ''),
(2, 'Mestna knjižnica Ljubljana', 'Kersnikova ulica 2, 1000 Ljubljana', '01 123 45 67', 'info@mk-lj.si', ''),
(3, 'Knjižnica Maribor', 'Rotovški trg 2, 2000 Maribor', '02 234 56 78', 'info@knjiznica-mb.si', ''),
(4, 'Knjižnica Koper', 'Trg Brolo 1, 6000 Koper', '05 678 90 12', 'info@knjiznica-kp.si', ''),
(5, 'Knjižnica Celje', 'Muzejski trg 1a, 3000 Celje', '03 543 21 09', 'info@knjiznica-celje.si', ''),
(6, 'Knjižnica Novo mesto', 'Rozmanova ulica 28, 8000 Novo mesto', '07 111 22 33', 'info@knjiznica-nm.si', '');

-- --------------------------------------------------------

--
-- Table structure for table `razpolozljivost`
--

CREATE TABLE `razpolozljivost` (
  `idGradiva` int(11) NOT NULL,
  `idKnjiznice` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `steviloGradiv` int(11) NOT NULL,
  `steviloIzposojenih` int(11) NOT NULL,
  `steviloRezerviranih` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zalozba`
--

CREATE TABLE `zalozba` (
  `idZalozba` int(11) NOT NULL,
  `uporabniskoIme` varchar(50) NOT NULL,
  `geslo` varchar(255) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `naslov` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avtor`
--
ALTER TABLE `avtor`
  ADD PRIMARY KEY (`idAvtor`);

--
-- Indexes for table `clan`
--
ALTER TABLE `clan`
  ADD PRIMARY KEY (`idClan`),
  ADD UNIQUE KEY `uporabniskoIme` (`uporabniskoIme`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `gradiva`
--
ALTER TABLE `gradiva`
  ADD PRIMARY KEY (`idGradiva`),
  ADD KEY `idZalozba` (`idZalozba`),
  ADD KEY `idKnjiznice` (`idKnjiznice`),
  ADD KEY `idAvtor` (`idAvtor`);

--
-- Indexes for table `izposoja`
--
ALTER TABLE `izposoja`
  ADD PRIMARY KEY (`idIzposoja`),
  ADD KEY `idClan` (`idClan`),
  ADD KEY `idGradiva` (`idGradiva`);

--
-- Indexes for table `knjiznice`
--
ALTER TABLE `knjiznice`
  ADD PRIMARY KEY (`idKnjiznice`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `razpolozljivost`
--
ALTER TABLE `razpolozljivost`
  ADD PRIMARY KEY (`idGradiva`,`idKnjiznice`),
  ADD KEY `idKnjiznice` (`idKnjiznice`);

--
-- Indexes for table `zalozba`
--
ALTER TABLE `zalozba`
  ADD PRIMARY KEY (`idZalozba`),
  ADD UNIQUE KEY `uporabniskoIme` (`uporabniskoIme`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avtor`
--
ALTER TABLE `avtor`
  MODIFY `idAvtor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clan`
--
ALTER TABLE `clan`
  MODIFY `idClan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gradiva`
--
ALTER TABLE `gradiva`
  MODIFY `idGradiva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `izposoja`
--
ALTER TABLE `izposoja`
  MODIFY `idIzposoja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `knjiznice`
--
ALTER TABLE `knjiznice`
  MODIFY `idKnjiznice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `zalozba`
--
ALTER TABLE `zalozba`
  MODIFY `idZalozba` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gradiva`
--
ALTER TABLE `gradiva`
  ADD CONSTRAINT `gradiva_ibfk_1` FOREIGN KEY (`idZalozba`) REFERENCES `zalozba` (`idZalozba`),
  ADD CONSTRAINT `gradiva_ibfk_2` FOREIGN KEY (`idKnjiznice`) REFERENCES `knjiznice` (`idKnjiznice`),
  ADD CONSTRAINT `gradiva_ibfk_3` FOREIGN KEY (`idAvtor`) REFERENCES `avtor` (`idAvtor`);

--
-- Constraints for table `izposoja`
--
ALTER TABLE `izposoja`
  ADD CONSTRAINT `izposoja_ibfk_1` FOREIGN KEY (`idClan`) REFERENCES `clan` (`idClan`),
  ADD CONSTRAINT `izposoja_ibfk_2` FOREIGN KEY (`idGradiva`) REFERENCES `gradiva` (`idGradiva`);

--
-- Constraints for table `razpolozljivost`
--
ALTER TABLE `razpolozljivost`
  ADD CONSTRAINT `razpolozljivost_ibfk_1` FOREIGN KEY (`idGradiva`) REFERENCES `gradiva` (`idGradiva`),
  ADD CONSTRAINT `razpolozljivost_ibfk_2` FOREIGN KEY (`idKnjiznice`) REFERENCES `knjiznice` (`idKnjiznice`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 13. Apr 2019 um 12:10
-- Server-Version: 10.1.31-MariaDB
-- PHP-Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `seminarportal`
--
CREATE DATABASE IF NOT EXISTS `Testportal` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `Testportal`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dokument`
--

DROP TABLE IF EXISTS `dokument`;
CREATE TABLE `dokument` (
  `ID` int(11) NOT NULL,
  `KURS_ID` int(11) NOT NULL,
  `pfad` varchar(500) DEFAULT NULL,
  `dokumentenname` varchar(100) DEFAULT NULL,
  `dokumententyp` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `dokument`
--

INSERT INTO `dokument` (`ID`, `KURS_ID`, `pfad`, `dokumentenname`, `dokumententyp`) VALUES
(1, 1, '/var/www/html/', 'test', 'jpg'),
(2, 2, '/var/www/html/', 'test', 'jpg'),
(3, 3, '/var/www/html/', 'test', 'jpg'),
(3, 4, '/var/www/html/', 'test', 'jpg'),

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurs`
--

DROP TABLE IF EXISTS `kurs`;
CREATE TABLE `kurs` (
  `ID` int(11) NOT NULL,
  `TRAINER_ID` int(11) DEFAULT NULL,
  `Kursname` varchar(100) DEFAULT NULL,
  `Kursbeschreibung` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kurs`
--

INSERT INTO `kurs` (`ID`, `TRAINER_ID`, `Kursname`, `Kursbeschreibung`) VALUES
(1, 2, 'Informatik', 'Informatik für Berufsschüler'),
(2, 3, 'Englisch', 'Englisch für Auszubildende'),
(3, NULL, 'IT-Technik', 'Netzwerktechnik für die 1.Klasse'),
(4, NULL, 'Mechatronik', 'Mechatronik-Lehrgang');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `KURS_ID` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `userpassword` varchar(1000) DEFAULT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  `trainer` tinyint(4) DEFAULT NULL,
  `vorname` varchar(100) DEFAULT NULL,
  `nachname` varchar(100) DEFAULT NULL,
  `titel` varchar(100) DEFAULT NULL,
  `geschlecht` int(11) DEFAULT NULL,
  `telefonnummer` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `strasse` varchar(100) DEFAULT NULL,
  `strassennummer` varchar(100) DEFAULT NULL,
  `PLZ` varchar(100) DEFAULT NULL,
  `Ort` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`ID`, `KURS_ID`, `username`, `userpassword`, `admin`, `trainer`, `vorname`, `nachname`, `titel`, `geschlecht`, `telefonnummer`, `email`, `strasse`, `strassennummer`, `PLZ`, `Ort`) VALUES
(1, NULL, 'trainertest', '$2y$10$yOus7TJPJCkhWq3Mo5TOiOlBPBHwuPh4XlTmE2cf3VA/FuEcW2G9y', 0, 1, 'Trainer', 'Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'trainerinformatik', '$2y$10$yOus7TJPJCkhWq3Mo5TOiOlBPBHwuPh4XlTmE2cf3VA/FuEcW2G9y', 0, 1, 'Trainer', 'Informatik', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 2, 'trainerenglisch', '$2y$10$yOus7TJPJCkhWq3Mo5TOiOlBPBHwuPh4XlTmE2cf3VA/FuEcW2G9y', 0, 1, 'Trainer', 'Englisch', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, 'admintest', '$2y$10$yOus7TJPJCkhWq3Mo5TOiOlBPBHwuPh4XlTmE2cf3VA/FuEcW2G9y', 1, 0, 'Admin', 'Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 'usertest1', '$2y$10$yOus7TJPJCkhWq3Mo5TOiOlBPBHwuPh4XlTmE2cf3VA/FuEcW2G9y', 0, 0, 'User', 'Informatik-Kurs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 2, 'usertest2', '$2y$10$yOus7TJPJCkhWq3Mo5TOiOlBPBHwuPh4XlTmE2cf3VA/FuEcW2G9y', 0, 0, 'User', 'Englisch-Kurs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);



--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dokument`
--
ALTER TABLE `dokument`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_DOKUMENT_KURS1_idx` (`KURS_ID`);

--
-- Indizes für die Tabelle `kurs`
--
ALTER TABLE `kurs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_KURS_USER1_idx` (`TRAINER_ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_USER_KURS1_idx` (`KURS_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `dokument`
--
ALTER TABLE `dokument`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `kurs`
--
ALTER TABLE `kurs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `dokument`
--
ALTER TABLE `dokument`
  ADD CONSTRAINT `fk_DOKUMENT_KURS1` FOREIGN KEY (`KURS_ID`) REFERENCES `kurs` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `kurs`
--
ALTER TABLE `kurs`
  ADD CONSTRAINT `fk_KURS_USER1` FOREIGN KEY (`TRAINER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_USER_KURS1` FOREIGN KEY (`KURS_ID`) REFERENCES `kurs` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

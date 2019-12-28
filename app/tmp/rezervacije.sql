-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.36-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for rezervacije
DROP DATABASE IF EXISTS `rezervacije`;
CREATE DATABASE IF NOT EXISTS `rezervacije` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `rezervacije`;

-- Dumping structure for table rezervacije.dokumenti
DROP TABLE IF EXISTS `dokumenti`;
CREATE TABLE IF NOT EXISTS `dokumenti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_dokumenti_ugovori` (`ugovor_id`),
  CONSTRAINT `FK_dokumenti_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.dokumenti: ~0 rows (approximately)
/*!40000 ALTER TABLE `dokumenti` DISABLE KEYS */;
/*!40000 ALTER TABLE `dokumenti` ENABLE KEYS */;

-- Dumping structure for table rezervacije.korisnici
DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `korisnicko_ime` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lozinka` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivo` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`korisnicko_ime`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.korisnici: ~3 rows (approximately)
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT IGNORE INTO `korisnici` (`id`, `ime`, `korisnicko_ime`, `lozinka`, `nivo`) VALUES
	(1, 'Administrator', 'admin', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 0),
	(2, 'Stasa', 'kure', '$2y$10$VSXjJDuWaXO3qPqabjtoGOpngfxO9H10u4wZSUiT2yiMU2QsALJm6', 0),
	(4, 'Perica', 'perica', '$2y$10$drwLdsU68RwPKaqAWLxdCeuInYAolGCzQxbpZ/J6Hf..dgT8FADGW', 200);
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;

-- Dumping structure for table rezervacije.logovi
DROP TABLE IF EXISTS `logovi`;
CREATE TABLE IF NOT EXISTS `logovi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tip` enum('brisanje','dodavanje','izmena','upload') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dodavanje',
  `korisnik_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_logovi_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_logovi_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.logovi: ~3 rows (approximately)
/*!40000 ALTER TABLE `logovi` DISABLE KEYS */;
INSERT IGNORE INTO `logovi` (`id`, `opis`, `datum`, `tip`, `korisnik_id`) VALUES
	(1, 'Neki opis', '2019-12-22 12:27:29', 'dodavanje', 1),
	(2, 'Neki opis 2', '2019-10-22 12:27:29', 'dodavanje', 4),
	(3, 'Super log', '2019-09-10 08:58:25', 'izmena', 2);
/*!40000 ALTER TABLE `logovi` ENABLE KEYS */;

-- Dumping structure for table rezervacije.sale
DROP TABLE IF EXISTS `sale`;
CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_kapacitet_mesta` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_kapacitet_stolova` smallint(5) unsigned NOT NULL DEFAULT '0',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `naziv` (`naziv`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.sale: ~3 rows (approximately)
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
INSERT IGNORE INTO `sale` (`id`, `naziv`, `max_kapacitet_mesta`, `max_kapacitet_stolova`, `napomena`) VALUES
	(1, 'GREEN SAPPHIRE HALL', 350, 0, NULL),
	(2, 'DIAMOND HALL', 270, 0, NULL),
	(3, 'CRYSTAL HALL', 800, 0, 'Stasa. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');
/*!40000 ALTER TABLE `sale` ENABLE KEYS */;

-- Dumping structure for table rezervacije.s_meniji
DROP TABLE IF EXISTS `s_meniji`;
CREATE TABLE IF NOT EXISTS `s_meniji` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hladno_predjelo` text COLLATE utf8mb4_unicode_ci,
  `sirevi` text COLLATE utf8mb4_unicode_ci,
  `corba` text COLLATE utf8mb4_unicode_ci,
  `glavno_jelo` text COLLATE utf8mb4_unicode_ci,
  `meso` text COLLATE utf8mb4_unicode_ci,
  `hleb` text COLLATE utf8mb4_unicode_ci,
  `karta_pica` text COLLATE utf8mb4_unicode_ci,
  `cena` decimal(12,2) NOT NULL DEFAULT '0.00',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.s_meniji: ~11 rows (approximately)
/*!40000 ALTER TABLE `s_meniji` DISABLE KEYS */;
INSERT IGNORE INTO `s_meniji` (`id`, `naziv`, `hladno_predjelo`, `sirevi`, `corba`, `glavno_jelo`, `meso`, `hleb`, `karta_pica`, `cena`, `napomena`) VALUES
	(1, 'Italijanski1', 'Suva svinjska pečenica,\r\nBudimska kobasica,\r\nKajmak', 'Dimljeni kačkavalj sa nanom', 'Pileća čorba', 'Juneći gulaš', 'Svinjsko pečenje', 'Domaće lepinje', 'Domaća rakija šljiva\r\nDomaća rakija dunja\r\nDomaća rakija kajsija\r\nDomaća rakija viljamovka', 0.00, 'Slana je jako pršuta'),
	(2, 'Srpski', 'Slanina', 'Mladi neslani', 'Riblja', 'Kupus', '-', 'Beli', 'Zuti sok,\r\nCrni sok,\r\nKisela voda', 0.00, 'Treba dodati nesto ljuto'),
	(5, 'DDDDDDDDDDDD', '', '', '', 'D', '', 'D', 'D', 0.00, 'D'),
	(6, 'CCCCCCCC', 'C', '', '', 'C', '', 'C', 'C', 0.00, ''),
	(7, 'FFFFFFFF', '', '', 'F', 'F', '', 'F', 'F', 0.00, ''),
	(8, 'GGGGGGGG', '', '', 'GG', '', '', 'GG', 'GG', 0.00, 'GGG'),
	(9, 'HHHHHHH', 'HHH', 'HHH', '', '', 'HHH', '', 'HHH\r\nHHH', 0.00, 'HHH'),
	(10, 'JJJJJJJ', 'JJJJ', '', '', 'JJJJ', '', '', 'JJJJ,\r\nJJJJ,\r\nJJJJ', 0.00, 'JJJJ'),
	(11, 'KKKKKKKK', 'KKKK', '', 'KKKKK', '', 'KKKK', '', '', 0.00, 'KKKK'),
	(12, 'LLLLLLLL', '', '', 'LLLL', '', 'LLLLL', 'LLLLLL', 'LLLL,\r\nLL,\r\nLLLL LLL', 0.00, 'LLLLL'),
	(13, 'OOOOOO', 'oooOOO', 'OOO', 'OOOO', '', 'OOOO', 'oOOO', 'oOOOOO', 0.00, 'OOOO');
/*!40000 ALTER TABLE `s_meniji` ENABLE KEYS */;

-- Dumping structure for table rezervacije.s_tip_dogadjaja
DROP TABLE IF EXISTS `s_tip_dogadjaja`;
CREATE TABLE IF NOT EXISTS `s_tip_dogadjaja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `multi_ugovori` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tip` (`tip`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.s_tip_dogadjaja: ~4 rows (approximately)
/*!40000 ALTER TABLE `s_tip_dogadjaja` DISABLE KEYS */;
INSERT IGNORE INTO `s_tip_dogadjaja` (`id`, `tip`, `multi_ugovori`) VALUES
	(1, 'Proslava rođendana', 0),
	(2, 'Proslava venčanja', 0),
	(3, 'Proslava krštenja', 0),
	(4, 'Proslava punoletstva', 0);
/*!40000 ALTER TABLE `s_tip_dogadjaja` ENABLE KEYS */;

-- Dumping structure for table rezervacije.termini
DROP TABLE IF EXISTS `termini`;
CREATE TABLE IF NOT EXISTS `termini` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sala_id` int(10) unsigned NOT NULL,
  `tip_dogadjaja_id` int(10) unsigned NOT NULL,
  `datum` date NOT NULL,
  `pocetak` time DEFAULT NULL,
  `kraj` time DEFAULT NULL,
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zauzet` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_termini_sale` (`sala_id`),
  KEY `FK_termini_korisnici` (`korisnik_id`),
  KEY `FK_termini_s_tip_dogadjaja` (`tip_dogadjaja_id`),
  CONSTRAINT `FK_termini_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_termini_s_tip_dogadjaja` FOREIGN KEY (`tip_dogadjaja_id`) REFERENCES `s_tip_dogadjaja` (`id`),
  CONSTRAINT `FK_termini_sale` FOREIGN KEY (`sala_id`) REFERENCES `sale` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.termini: ~0 rows (approximately)
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;

-- Dumping structure for table rezervacije.ugovori
DROP TABLE IF EXISTS `ugovori`;
CREATE TABLE IF NOT EXISTS `ugovori` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `termin_id` int(10) unsigned NOT NULL,
  `broj_ugovora` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datum` date NOT NULL,
  `meni_id` int(10) unsigned NOT NULL,
  `broj_mesta` smallint(5) unsigned NOT NULL,
  `broj_stolova` smallint(5) unsigned NOT NULL,
  `broj_mesta_po_stolu` smallint(5) unsigned NOT NULL,
  `prezime` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ime` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `prosek_godina` smallint(5) unsigned NOT NULL DEFAULT '0',
  `muzika_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `muzika_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_ugovor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fotograf_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fotograf_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `torta_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `torta_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dekoracija_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dekoracija_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kokteli_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `kokteli_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slatki_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `slatki_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vocni_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vocni_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posebni_zahtevi` text COLLATE utf8mb4_unicode_ci,
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `broj_ugovora` (`broj_ugovora`),
  KEY `FK_ugovori_termini` (`termin_id`),
  KEY `FK_ugovori_korisnici` (`korisnik_id`),
  KEY `FK_ugovori_s_meniji` (`meni_id`),
  CONSTRAINT `FK_ugovori_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_ugovori_s_meniji` FOREIGN KEY (`meni_id`) REFERENCES `s_meniji` (`id`),
  CONSTRAINT `FK_ugovori_termini` FOREIGN KEY (`termin_id`) REFERENCES `termini` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.ugovori: ~0 rows (approximately)
/*!40000 ALTER TABLE `ugovori` DISABLE KEYS */;
/*!40000 ALTER TABLE `ugovori` ENABLE KEYS */;

-- Dumping structure for table rezervacije.uplate
DROP TABLE IF EXISTS `uplate`;
CREATE TABLE IF NOT EXISTS `uplate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL,
  `datum` datetime NOT NULL,
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `nacin_placanja` enum('gotovina','kartica','ček','faktura') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gotovina',
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_uplate_ugovori` (`ugovor_id`),
  KEY `FK_uplate_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_uplate_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_uplate_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.uplate: ~0 rows (approximately)
/*!40000 ALTER TABLE `uplate` DISABLE KEYS */;
/*!40000 ALTER TABLE `uplate` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

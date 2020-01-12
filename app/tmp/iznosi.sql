-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.2.0.5599
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
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_dokumenti_ugovori` (`ugovor_id`),
  KEY `FK_dokumenti_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_dokumenti_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_dokumenti_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.dokumenti: ~2 rows (approximately)
DELETE FROM `dokumenti`;
/*!40000 ALTER TABLE `dokumenti` DISABLE KEYS */;
INSERT INTO `dokumenti` (`id`, `ugovor_id`, `link`, `opis`, `korisnik_id`, `created_at`) VALUES
	(1, 4, 'http://localhost/rezervacije/pub//doc/4_Ugovor_Anita_Rođendan_55c47569e763d905.jpg', 'Ugovor Anita Rođendan', 1, '2020-01-09 08:43:00'),
	(2, 5, 'http://localhost/rezervacije/pub//doc/5_Ugovor_4_14065d6a911ad3ce.jpg', 'Ugovor 4', 1, '2020-01-09 10:54:05');
/*!40000 ALTER TABLE `dokumenti` ENABLE KEYS */;

-- Dumping structure for table rezervacije.korisnici
DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `korisnicko_ime` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lozinka` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivo` int(10) unsigned NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`korisnicko_ime`),
  KEY `FK_korisnici_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_korisnici_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.korisnici: ~4 rows (approximately)
DELETE FROM `korisnici`;
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT INTO `korisnici` (`id`, `ime`, `korisnicko_ime`, `lozinka`, `nivo`, `korisnik_id`, `created_at`) VALUES
	(1, 'Administrator', 'admin', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 0, 1, '2020-01-08 19:47:03'),
	(2, 'Stasa', 'kure', '$2y$10$VSXjJDuWaXO3qPqabjtoGOpngfxO9H10u4wZSUiT2yiMU2QsALJm6', 0, 1, '2020-01-08 19:47:04'),
	(4, 'Obrada', 'obrada', '$2y$10$ngW.W4d16JwT7QEmIoxxY.635ddDj9YEjIJoYzb5K8NB4Eur0irva', 100, 1, '2020-01-08 19:47:05'),
	(5, 'Vlasnik', 'vlasnik', '$2y$10$vh4ycuSltjNx/dlZ0Y8stexgXmZsq9zwdoWFBVWcy69eyIOeOg32i', 200, 1, '2020-01-09 08:30:10');
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.logovi: ~18 rows (approximately)
DELETE FROM `logovi`;
/*!40000 ALTER TABLE `logovi` DISABLE KEYS */;
INSERT INTO `logovi` (`id`, `opis`, `datum`, `tip`, `korisnik_id`) VALUES
	(1, 'Neki opis', '2019-12-22 12:27:29', 'dodavanje', 1),
	(2, 'Neki opis 2', '2019-10-22 12:27:29', 'dodavanje', 4),
	(3, 'Super log', '2019-09-10 08:58:25', 'izmena', 2),
	(4, 'Administrator je dodao salu Gumba sa id brojem 4', '2020-01-02 13:53:06', 'dodavanje', 1),
	(5, 'Administrator je dodao tip događaja Brisko 2 sa id brojem 8', '2020-01-03 12:47:08', 'dodavanje', 1),
	(6, 'Administrator je obrisao tip događaja Brisko 2 sa id brojem 8', '2020-01-03 12:47:19', 'brisanje', 1),
	(7, 'Administrator je izmenio podatke o meniju KKKKKKKK sa id brojem 11', '2020-01-05 11:57:07', 'izmena', 1),
	(8, 'Administrator je izmenio podatke o meniju JJJJJJJ sa id brojem 10', '2020-01-05 12:10:51', 'izmena', 1),
	(9, 'Administrator je izmenio podatke o meniju DDDDDDDDDDDD sa id brojem 5', '2020-01-05 12:11:01', 'izmena', 1),
	(10, 'Administrator je dodao meni NoviM sa id brojem 14', '2020-01-08 20:05:51', 'dodavanje', 1),
	(11, 'Stasa je izmenio podatke o meniju NoviM sa id brojem 14', '2020-01-08 20:10:31', 'izmena', 2),
	(12, 'Stasa je izmenio podatke o meniju NoviM sa id brojem 14', '2020-01-08 20:11:20', 'izmena', 2),
	(13, 'Stasa je izmenio podatke o meniju NoviM sa id brojem 14', '2020-01-08 20:11:55', 'izmena', 2),
	(14, 'Stasa je izmenio podatke o meniju NoviM sa id brojem 14', '2020-01-08 20:12:14', 'izmena', 2),
	(15, 'Administrator je dodao meni Acin meni sa id brojem 15', '2020-01-09 10:56:49', 'dodavanje', 1),
	(16, '8, termini - opis : ASD', '2020-01-10 09:28:45', 'dodavanje', 1),
	(17, '8, termini - opis : ASD 1', '2020-01-10 09:28:58', 'izmena', 1),
	(18, '8, termini - opis : ASD 1', '2020-01-10 09:29:14', 'brisanje', 1);
/*!40000 ALTER TABLE `logovi` ENABLE KEYS */;

-- Dumping structure for table rezervacije.sale
DROP TABLE IF EXISTS `sale`;
CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_kapacitet_mesta` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_kapacitet_stolova` smallint(5) unsigned NOT NULL DEFAULT '0',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `naziv` (`naziv`),
  KEY `FK_sale_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_sale_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.sale: ~3 rows (approximately)
DELETE FROM `sale`;
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
INSERT INTO `sale` (`id`, `naziv`, `max_kapacitet_mesta`, `max_kapacitet_stolova`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'GREEN SAPPHIRE HALL', 600, 50, NULL, 1, '2020-01-08 19:46:58'),
	(2, 'DIAMOND HALL', 270, 24, NULL, 1, '2020-01-08 19:46:58'),
	(3, 'CRYSTAL HALL', 800, 68, 'Stasa. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 1, '2020-01-08 19:46:59');
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
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_s_meniji_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_s_meniji_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.s_meniji: ~4 rows (approximately)
DELETE FROM `s_meniji`;
/*!40000 ALTER TABLE `s_meniji` DISABLE KEYS */;
INSERT INTO `s_meniji` (`id`, `naziv`, `hladno_predjelo`, `sirevi`, `corba`, `glavno_jelo`, `meso`, `hleb`, `karta_pica`, `cena`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'Italijanski1', 'Suva svinjska pečenica,\r\nBudimska kobasica,\r\nKajmak', 'Dimljeni kačkavalj sa nanom', 'Pileća čorba', 'Juneći gulaš', 'Svinjsko pečenje', 'Domaće lepinje', 'Domaća rakija šljiva\r\nDomaća rakija dunja\r\nDomaća rakija kajsija\r\nDomaća rakija viljamovka', 2000.00, 'Slana je jako pršuta', 1, '2020-01-08 19:47:43'),
	(2, 'Srpski', 'Slanina', 'Mladi neslani', 'Riblja', 'Kupus', '-', 'Beli', 'Zuti sok,\r\nCrni sok,\r\nKisela voda', 4200.00, 'Treba dodati nesto ljuto', 1, '2020-01-08 19:47:44'),
	(14, 'NoviM', 'Suva svinjska pečenica,\r\nSuva goveđa pršuta\r\n', 'Kačkavalj,\r\nDimljeni kačkavalj sa nanom\r\n', 'Teleća čorba\r\n', 'Svadbarski kupus,\r\nSarma\r\n', 'Jagnjeće pečenje\r\n', 'Beli hleb\r\n', 'Domaća rakija šljiva,\r\nNegazirana voda Vrnjci 1l,\r\nCoca Cola 1l,\r\nDomaća rakija kajsija\r\n', 1600.00, 'Zbog korisnika', 1, '2020-01-08 20:05:51'),
	(15, 'Acin meni', 'Suva goveđa pršuta,\r\nBudimska kobasica,\r\nItalijanska pršuta', 'Dimljeni kačkavalj sa paprikom', 'Pileća čorba', 'Juneći gulaš', 'Svinjsko pečenje,\r\nRoštilj (pljeskavica, vešalica, pileći file)', 'Beli hleb', 'Domaća rakija kajsija,\r\nNegazirana voda Vrnjci 1l,\r\nCoca Cola 1l,\r\nHeineken 0.4l,\r\nTuborg 0.5l', 1200.00, '', 1, '2020-01-09 10:56:49');
/*!40000 ALTER TABLE `s_meniji` ENABLE KEYS */;

-- Dumping structure for table rezervacije.s_tip_dogadjaja
DROP TABLE IF EXISTS `s_tip_dogadjaja`;
CREATE TABLE IF NOT EXISTS `s_tip_dogadjaja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `multi_ugovori` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tip` (`tip`),
  KEY `FK_s_tip_dogadjaja_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_s_tip_dogadjaja_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.s_tip_dogadjaja: ~6 rows (approximately)
DELETE FROM `s_tip_dogadjaja`;
/*!40000 ALTER TABLE `s_tip_dogadjaja` DISABLE KEYS */;
INSERT INTO `s_tip_dogadjaja` (`id`, `tip`, `multi_ugovori`, `korisnik_id`, `created_at`) VALUES
	(1, 'Proslava rođendana', 0, 1, '2020-01-09 08:27:01'),
	(2, 'Proslava venčanja', 0, 1, '2020-01-09 08:27:02'),
	(3, 'Proslava krštenja', 0, 1, '2020-01-09 08:27:03'),
	(4, 'Proslava punoletstva', 0, 1, '2020-01-09 08:27:04'),
	(5, 'Prednovogodišnja proslava', 1, 1, '2020-01-09 08:27:04'),
	(6, 'Osmomartovska proslava', 1, 1, '2020-01-09 08:27:05');
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_termini_sale` (`sala_id`),
  KEY `FK_termini_korisnici` (`korisnik_id`),
  KEY `FK_termini_s_tip_dogadjaja` (`tip_dogadjaja_id`),
  CONSTRAINT `FK_termini_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_termini_s_tip_dogadjaja` FOREIGN KEY (`tip_dogadjaja_id`) REFERENCES `s_tip_dogadjaja` (`id`),
  CONSTRAINT `FK_termini_sale` FOREIGN KEY (`sala_id`) REFERENCES `sale` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.termini: ~7 rows (approximately)
DELETE FROM `termini`;
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
INSERT INTO `termini` (`id`, `sala_id`, `tip_dogadjaja_id`, `datum`, `pocetak`, `kraj`, `opis`, `zauzet`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 3, 6, '2020-01-09', '10:00:00', '23:59:00', 'Neki opis termina A', 0, 'Neka napoena Termina A', 2, '2019-12-28 14:59:01'),
	(2, 1, 2, '2020-01-09', '10:00:00', '23:00:00', 'Svadba A', 0, 'Nema napomene', 1, '0000-00-00 00:00:00'),
	(3, 1, 2, '2020-01-08', '10:00:00', '23:00:00', 'Neki opis', 0, '', 1, '2020-01-01 15:57:49'),
	(4, 2, 2, '2020-01-08', '09:21:00', '22:22:00', 'Drugi', 1, '', 1, '2020-01-03 22:39:58'),
	(5, 3, 4, '2020-01-08', '08:00:00', '23:00:00', 'Treci', 0, '', 1, '2020-01-03 22:40:34'),
	(6, 3, 1, '2020-01-10', '13:00:00', '23:59:00', 'Anitin 21 rođendan', 1, 'Samo starogradska!', 1, '2020-01-09 08:32:35'),
	(7, 1, 1, '2020-01-11', '10:00:00', '23:00:00', 'Acin rodj', 1, 'Voli samo zabavnjake', 1, '2020-01-09 10:51:17');
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;

-- Dumping structure for table rezervacije.ugovori
DROP TABLE IF EXISTS `ugovori`;
CREATE TABLE IF NOT EXISTS `ugovori` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `termin_id` int(10) unsigned NOT NULL,
  `broj_ugovora` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datum` date NOT NULL,
  `meni_id` int(10) unsigned NOT NULL,
  `broj_mesta` smallint(5) unsigned NOT NULL,
  `broj_stolova` smallint(5) unsigned NOT NULL,
  `broj_mesta_po_stolu` smallint(5) unsigned NOT NULL,
  `prezime` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ime` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prosek_godina` smallint(5) unsigned NOT NULL DEFAULT '0',
  `muzika_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `muzika_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_ugovor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `muzika_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `fotograf_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fotograf_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fotograf_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `torta_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `torta_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `torta_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `dekoracija_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dekoracija_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dekoracija_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `kokteli_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `kokteli_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kokteli_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `slatki_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `slatki_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slatki_sto_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `vocni_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vocni_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vocni_sto_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `posebni_zahtevi` text COLLATE utf8mb4_unicode_ci,
  `posebni_zahtevi_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `broj_ugovora` (`broj_ugovora`),
  KEY `FK_ugovori_termini` (`termin_id`),
  KEY `FK_ugovori_korisnici` (`korisnik_id`),
  KEY `FK_ugovori_s_meniji` (`meni_id`),
  CONSTRAINT `FK_ugovori_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_ugovori_s_meniji` FOREIGN KEY (`meni_id`) REFERENCES `s_meniji` (`id`),
  CONSTRAINT `FK_ugovori_termini` FOREIGN KEY (`termin_id`) REFERENCES `termini` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.ugovori: ~4 rows (approximately)
DELETE FROM `ugovori`;
/*!40000 ALTER TABLE `ugovori` DISABLE KEYS */;
INSERT INTO `ugovori` (`id`, `termin_id`, `broj_ugovora`, `datum`, `meni_id`, `broj_mesta`, `broj_stolova`, `broj_mesta_po_stolu`, `prezime`, `ime`, `telefon`, `email`, `prosek_godina`, `muzika_chk`, `muzika_opis`, `muzika_ugovor`, `iznos`, `muzika_iznos`, `fotograf_chk`, `fotograf_opis`, `fotograf_iznos`, `torta_chk`, `torta_opis`, `torta_iznos`, `dekoracija_chk`, `dekoracija_opis`, `dekoracija_iznos`, `kokteli_chk`, `kokteli_opis`, `kokteli_iznos`, `slatki_sto_chk`, `slatki_sto_opis`, `slatki_sto_iznos`, `vocni_sto_chk`, `vocni_sto_opis`, `vocni_sto_iznos`, `posebni_zahtevi`, `posebni_zahtevi_iznos`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 3, '3366/2019', '2019-12-28', 1, 320, 32, 10, 'Dongo', 'Longo', '063003311', 'dongo@dd.com', 32, 1, NULL, NULL, 1200000.00, 0.00, 0, NULL, 0.00, 1, NULL, 0.00, 0, NULL, 0.00, 0, NULL, 0.00, 0, NULL, 0.00, 1, NULL, 0.00, NULL, 0.00, 'Nema', 4, '2019-12-28 15:00:31'),
	(3, 5, '421/2019', '2020-01-03', 14, 255, 19, 14, 'Bongo', 'Kongo', '063/219458', 'kongo@bongo.com', 28, 0, '', '', 8000000.00, 0.00, 0, '', 0.00, 0, '', 0.00, 1, '', 0.00, 0, '', 0.00, 0, '', 0.00, 1, 'Ohoho vocni sto', 0.00, 'DD', 0.00, 'NN', 1, NULL),
	(4, 6, '12/2020', '2020-01-07', 14, 330, 33, 10, 'Savić', 'Anita', '065/201333', 'anita@tahoo.com', 20, 1, '', '', 528000.00, 0.00, 1, '', 0.00, 0, '', 0.00, 1, '', 0.00, 1, '', 0.00, 1, '', 0.00, 1, '', 0.00, 'Čokoladna fontana', 0.00, 'Plaća u tri rate', 1, '2020-01-09 08:35:28'),
	(5, 7, '12', '2020-01-09', 14, 230, 23, 10, 'S', 'S', '+381652050070', 'stashakg@gmail.com', 33, 1, '', '', 368000.00, 0.00, 1, '', 0.00, 1, '', 0.00, 0, '', 0.00, 1, 'Super', 0.00, 1, '', 0.00, 0, '', 0.00, 'Nema', 0.00, 'Nema', 1, '2020-01-09 10:53:05');
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_uplate_ugovori` (`ugovor_id`),
  KEY `FK_uplate_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_uplate_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_uplate_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table rezervacije.uplate: ~6 rows (approximately)
DELETE FROM `uplate`;
/*!40000 ALTER TABLE `uplate` DISABLE KEYS */;
INSERT INTO `uplate` (`id`, `ugovor_id`, `datum`, `iznos`, `nacin_placanja`, `opis`, `korisnik_id`, `created_at`) VALUES
	(1, 1, '2020-01-02 09:17:46', 200000.00, 'gotovina', 'Mika uplatio', 4, '2020-01-02 09:18:05'),
	(2, 1, '2019-12-22 14:00:46', 200000.00, 'gotovina', 'Sima uplatio', 4, '2020-01-02 09:18:05'),
	(3, 1, '2019-12-16 00:00:00', 150000.00, 'faktura', 'KAPARA', 1, NULL),
	(4, 4, '2020-01-08 00:00:00', 140000.00, 'gotovina', 'Prva rata', 1, '2020-01-09 08:37:17'),
	(5, 4, '2020-01-09 00:00:00', 200000.00, 'gotovina', 'Druga rata', 1, '2020-01-09 08:37:39'),
	(6, 5, '2020-01-09 00:00:00', 168000.00, 'gotovina', 'Prva rata', 1, '2020-01-09 10:54:47');
/*!40000 ALTER TABLE `uplate` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
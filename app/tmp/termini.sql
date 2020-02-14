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


-- Dumping database structure for termini
DROP DATABASE IF EXISTS `termini`;
CREATE DATABASE IF NOT EXISTS `termini` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `termini`;

-- Dumping structure for table termini.dokumenti
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.dokumenti: ~1 rows (approximately)
/*!40000 ALTER TABLE `dokumenti` DISABLE KEYS */;
/*!40000 ALTER TABLE `dokumenti` ENABLE KEYS */;

-- Dumping structure for table termini.korisnici
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.korisnici: ~3 rows (approximately)
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT IGNORE INTO `korisnici` (`id`, `ime`, `korisnicko_ime`, `lozinka`, `nivo`, `korisnik_id`, `created_at`) VALUES
	(0, 'SuperAdmin', 'ChaShaOne', '$2y$10$LVaboM6Y5ECZz25SGgCFV.ItGqucsYamVbbNAW9.wdIapxkzQJouq', 1000, 1, '2020-01-20 08:27:55'),
	(1, 'Admin', 'admin', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 0, 1, '2020-01-08 19:47:03');
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;

-- Dumping structure for table termini.logovi
DROP TABLE IF EXISTS `logovi`;
CREATE TABLE IF NOT EXISTS `logovi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stari` text COLLATE utf8mb4_unicode_ci,
  `tip` enum('brisanje','dodavanje','izmena','upload') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dodavanje',
  `korisnik_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_logovi_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_logovi_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.logovi: ~22 rows (approximately)
/*!40000 ALTER TABLE `logovi` DISABLE KEYS */;
INSERT IGNORE INTO `logovi` (`id`, `opis`, `datum`, `stari`, `tip`, `korisnik_id`) VALUES
	(1, '1, s_tip_dogadjaja - tip: Svadba', '2020-01-28 16:02:37', NULL, 'dodavanje', 1),
	(2, '2, s_tip_dogadjaja - tip: 8. mart', '2020-01-28 16:02:48', NULL, 'dodavanje', 1),
	(3, '1, termini - opis: opis', '2020-01-28 16:03:26', NULL, 'dodavanje', 1),
	(4, '1, termini - opis: opis', '2020-01-28 16:07:04', 'Podaci iz modela: sala_id:1, tip_dogadjaja_id:1, datum:2020-01-28, pocetak:10:00:00, kraj:20:00:00, opis:opis, zauzet:0, napomena:', 'izmena', 1),
	(5, '1, termini - opis: opis', '2020-01-28 16:11:18', 'Podaci iz modela: sala_id:1, tip_dogadjaja_id:1, datum:2020-01-28, pocetak:10:00:00, kraj:20:00:00, opis:opis, zauzet:0, napomena:', 'izmena', 1),
	(6, '1, termini - opis: opis', '2020-01-28 16:12:21', 'Podaci iz modela: sala_id:1, tip_dogadjaja_id:1, datum:2020-01-28, pocetak:10:00:00, kraj:20:00:00, opis:opis, zauzet:0, napomena:', 'izmena', 1),
	(7, '1, termini - opis: opis', '2020-01-28 16:15:50', 'Podaci iz modela: sala_id:1, tip_dogadjaja_id:1, datum:2020-01-28, pocetak:10:00:00, kraj:20:00:00, opis:opis, zauzet:0, napomena:', 'izmena', 1),
	(8, '1, termini - opis: opis', '2020-01-28 16:33:16', 'Podaci iz modela: sala_id:1, tip_dogadjaja_id:1, datum:2020-01-28, pocetak:10:00:00, kraj:20:00:00, opis:opis, zauzet:0, napomena:', 'izmena', 1),
	(9, '1, s_meniji - naziv: Set meni 1', '2020-01-28 16:38:46', NULL, 'dodavanje', 1),
	(10, '1, ugovori - broj_ugovora: ', '2020-01-28 16:39:39', NULL, 'dodavanje', 1),
	(11, '1, ugovori - broj_ugovora: 34567', '2020-01-28 16:50:48', 'Podaci iz modela: termin_id:1, broj_ugovora:, datum:2020-01-28, meni_id:1, broj_mesta:500, broj_stolova:42, broj_mesta_po_stolu:12, prezime:dfgdfgd, ime:dfgfbxb, telefon:, email:, prosek_godina:0, muzika_chk:0, muzika_opis:, muzika_ugovor:, iznos:1000000.00, muzika_iznos:0.00, fotograf_chk:0, fotograf_opis:, fotograf_iznos:0.00, torta_chk:0, torta_opis:, torta_iznos:0.00, dekoracija_chk:0, dekoracija_opis:, dekoracija_iznos:0.00, kokteli_chk:0, kokteli_opis:, kokteli_iznos:0.00, slatki_sto_chk:0, slatki_sto_opis:, slatki_sto_iznos:0.00, vocni_sto_chk:0, vocni_sto_opis:, vocni_sto_iznos:0.00, posebni_zahtevi:, posebni_zahtevi_iznos:0.00, napomena:', 'izmena', 1),
	(12, '1, ugovori - broj_ugovora: ', '2020-01-28 16:53:03', 'Podaci iz modela: termin_id:1, broj_ugovora:34567, datum:2020-01-28, meni_id:1, broj_mesta:500, broj_stolova:42, broj_mesta_po_stolu:12, prezime:dfgdfgd, ime:dfgfbxb, telefon:, email:, prosek_godina:0, muzika_chk:0, muzika_opis:, muzika_ugovor:, iznos:1000000.00, muzika_iznos:0.00, fotograf_chk:0, fotograf_opis:, fotograf_iznos:0.00, torta_chk:0, torta_opis:, torta_iznos:0.00, dekoracija_chk:0, dekoracija_opis:, dekoracija_iznos:0.00, kokteli_chk:0, kokteli_opis:, kokteli_iznos:0.00, slatki_sto_chk:0, slatki_sto_opis:, slatki_sto_iznos:0.00, vocni_sto_chk:0, vocni_sto_opis:, vocni_sto_iznos:0.00, posebni_zahtevi:, posebni_zahtevi_iznos:0.00, napomena:', 'izmena', 1),
	(13, '1, ugovori - broj_ugovora: ', '2020-01-28 17:23:03', 'Podaci iz modela: termin_id:1, broj_ugovora:, datum:2020-01-28, meni_id:1, broj_mesta:500, broj_stolova:42, broj_mesta_po_stolu:12, prezime:dfgdfgd, ime:dfgfbxb, telefon:, email:, prosek_godina:0, muzika_chk:0, muzika_opis:, muzika_ugovor:, iznos:1000000.00, muzika_iznos:0.00, fotograf_chk:0, fotograf_opis:, fotograf_iznos:0.00, torta_chk:0, torta_opis:, torta_iznos:0.00, dekoracija_chk:0, dekoracija_opis:, dekoracija_iznos:0.00, kokteli_chk:0, kokteli_opis:, kokteli_iznos:0.00, slatki_sto_chk:0, slatki_sto_opis:, slatki_sto_iznos:0.00, vocni_sto_chk:0, vocni_sto_opis:, vocni_sto_iznos:0.00, posebni_zahtevi:, posebni_zahtevi_iznos:0.00, napomena:', 'izmena', 1),
	(14, '1, uplate - datum: 2020-01-29 00:00:00', '2020-01-28 17:29:57', NULL, 'dodavanje', 0),
	(15, '1, uplate - datum: 2020-01-29 00:00:00', '2020-01-28 17:31:06', 'Podaci iz modela: ugovor_id:1, datum:2020-01-29 00:00:00, iznos:1000000.00, nacin_placanja:gotovina, opis:rata', 'brisanje', 0),
	(16, '2, uplate - datum: 2020-01-30 00:00:00', '2020-01-28 17:38:41', NULL, 'dodavanje', 0),
	(17, '2, uplate - datum: 2020-01-30 00:00:00', '2020-01-28 17:42:01', 'Podaci iz modela: ugovor_id:1, datum:2020-01-30 00:00:00, iznos:1000000.00, nacin_placanja:gotovina, opis:r', 'brisanje', 0),
	(18, '3, uplate - datum: 2019-12-05 00:00:00', '2020-01-28 17:52:53', NULL, 'dodavanje', 0),
	(19, '1, termini - opis: opis', '2020-01-30 19:09:45', 'Podaci iz modela: sala_id:1, tip_dogadjaja_id:1, datum:2020-01-28, pocetak:10:00:00, kraj:20:00:00, opis:opis, zauzet:1, napomena:', 'izmena', 1),
	(20, '2, s_meniji - naziv: Set meni 2', '2020-01-30 20:27:44', NULL, 'dodavanje', 1),
	(21, '3, s_meniji - naziv: Set meni 3', '2020-01-30 20:29:03', NULL, 'dodavanje', 1),
	(22, '3, s_meniji - naziv: Set meni 3', '2020-01-30 20:30:02', 'Podaci iz modela: naziv:Set meni 3, hladno_predjelo:, sirevi:, corba:, glavno_jelo:, meso:, hleb:, karta_pica:, cena:4000.00, napomena:', 'brisanje', 1),
	(23, '2, s_meniji - naziv: Set meni 2', '2020-01-30 20:30:05', 'Podaci iz modela: naziv:Set meni 2, hladno_predjelo:, sirevi:, corba:, glavno_jelo:, meso:, hleb:, karta_pica:, cena:3000.00, napomena:', 'brisanje', 1),
	(24, '4, s_meniji - naziv: Set meni 2', '2020-01-30 20:30:37', NULL, 'dodavanje', 1),
	(25, '4, s_meniji - naziv: Set meni 2', '2020-01-30 20:31:09', 'Podaci iz modela: naziv:Set meni 2, hladno_predjelo:, sirevi:, corba:, glavno_jelo:, meso:, hleb:, karta_pica:, cena:3000.00, napomena:', 'brisanje', 1);
/*!40000 ALTER TABLE `logovi` ENABLE KEYS */;

-- Dumping structure for table termini.sale
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

-- Dumping data for table termini.sale: ~4 rows (approximately)
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
INSERT IGNORE INTO `sale` (`id`, `naziv`, `max_kapacitet_mesta`, `max_kapacitet_stolova`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'GREEN SAPPHIRE HALL', 600, 50, NULL, 1, '2020-01-08 19:46:58'),
	(2, 'DIAMOND HALL', 270, 24, NULL, 1, '2020-01-08 19:46:58'),
	(3, 'CRYSTAL HALL', 800, 68, NULL, 1, '2020-01-08 19:46:59');
/*!40000 ALTER TABLE `sale` ENABLE KEYS */;

-- Dumping structure for table termini.s_meniji
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.s_meniji: ~1 rows (approximately)
/*!40000 ALTER TABLE `s_meniji` DISABLE KEYS */;
INSERT IGNORE INTO `s_meniji` (`id`, `naziv`, `hladno_predjelo`, `sirevi`, `corba`, `glavno_jelo`, `meso`, `hleb`, `karta_pica`, `cena`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'Set meni 1', 'Njeguški pršut,\r\nItalijanska pršuta,\r\nKajmak', 'Kačkavalj,\r\nDimljeni kačkavalj', 'Teleća čorba', 'Svadbarski kupus', 'Jagnjeće pečenje', 'Beli hleb', 'Domaća rakija šljiva,\r\nGazirana voda Vrnjci 1l', 2000.00, '', 1, '2020-01-28 16:38:46');
/*!40000 ALTER TABLE `s_meniji` ENABLE KEYS */;

-- Dumping structure for table termini.s_tip_dogadjaja
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.s_tip_dogadjaja: ~2 rows (approximately)
/*!40000 ALTER TABLE `s_tip_dogadjaja` DISABLE KEYS */;
INSERT IGNORE INTO `s_tip_dogadjaja` (`id`, `tip`, `multi_ugovori`, `korisnik_id`, `created_at`) VALUES
	(1, 'Svadba', 0, 1, '2020-01-28 16:02:37'),
	(2, '8. mart', 1, 1, '2020-01-28 16:02:48');
/*!40000 ALTER TABLE `s_tip_dogadjaja` ENABLE KEYS */;

-- Dumping structure for table termini.termini
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.termini: ~0 rows (approximately)
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
INSERT IGNORE INTO `termini` (`id`, `sala_id`, `tip_dogadjaja_id`, `datum`, `pocetak`, `kraj`, `opis`, `zauzet`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 1, 2, '2020-01-28', '10:00:00', '20:00:00', 'opis', 1, '', 1, '2020-01-28 16:03:26');
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;

-- Dumping structure for table termini.ugovori
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
  `fizicko_pravno` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `naziv_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mb_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pib_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mesto_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresa_firme` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banka_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `racun_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `muzika_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_ugovor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
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
  KEY `FK_ugovori_termini` (`termin_id`),
  KEY `FK_ugovori_korisnici` (`korisnik_id`),
  KEY `FK_ugovori_s_meniji` (`meni_id`),
  CONSTRAINT `FK_ugovori_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_ugovori_s_meniji` FOREIGN KEY (`meni_id`) REFERENCES `s_meniji` (`id`),
  CONSTRAINT `FK_ugovori_termini` FOREIGN KEY (`termin_id`) REFERENCES `termini` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.ugovori: ~0 rows (approximately)
/*!40000 ALTER TABLE `ugovori` DISABLE KEYS */;
INSERT IGNORE INTO `ugovori` (`id`, `termin_id`, `broj_ugovora`, `datum`, `meni_id`, `broj_mesta`, `broj_stolova`, `broj_mesta_po_stolu`, `prezime`, `ime`, `telefon`, `email`, `prosek_godina`, `fizicko_pravno`, `naziv_firme`, `mb_firme`, `pib_firme`, `mesto_firme`, `adresa_firme`, `banka_firme`, `racun_firme`, `muzika_chk`, `muzika_opis`, `muzika_ugovor`, `muzika_iznos`, `iznos`, `fotograf_chk`, `fotograf_opis`, `fotograf_iznos`, `torta_chk`, `torta_opis`, `torta_iznos`, `dekoracija_chk`, `dekoracija_opis`, `dekoracija_iznos`, `kokteli_chk`, `kokteli_opis`, `kokteli_iznos`, `slatki_sto_chk`, `slatki_sto_opis`, `slatki_sto_iznos`, `vocni_sto_chk`, `vocni_sto_opis`, `vocni_sto_iznos`, `posebni_zahtevi`, `posebni_zahtevi_iznos`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 1, '', '2020-01-28', 1, 500, 42, 12, 'dfgdfgd', 'dfgfbxb', '', '', 0, 1, 'rtretetet', '222', '123', 'krg', 'fgfhfhg 45', 'sddfgdsf', '33', 0, '', '', 0.00, 1035000.00, 1, 'foto', 15000.00, 0, '', 0.00, 1, 'dekor', 20000.00, 0, '', 0.00, 0, '', 0.00, 0, '', 0.00, '', 0.00, '', 1, '2020-01-28 16:39:39');
/*!40000 ALTER TABLE `ugovori` ENABLE KEYS */;

-- Dumping structure for table termini.uplate
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
  `kapara` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_uplate_ugovori` (`ugovor_id`),
  KEY `FK_uplate_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_uplate_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_uplate_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.uplate: ~0 rows (approximately)
/*!40000 ALTER TABLE `uplate` DISABLE KEYS */;
INSERT IGNORE INTO `uplate` (`id`, `ugovor_id`, `datum`, `iznos`, `nacin_placanja`, `opis`, `korisnik_id`, `created_at`, `kapara`) VALUES
	(3, 1, '2019-12-05 00:00:00', 500000.00, 'kartica', 'rt', 0, '2020-01-28 17:52:53', 0);
/*!40000 ALTER TABLE `uplate` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

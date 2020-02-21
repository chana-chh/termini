-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for termini
CREATE DATABASE IF NOT EXISTS `termini` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `termini`;

-- Dumping structure for table termini.dokumenti
DROP TABLE IF EXISTS `dokumenti`;
CREATE TABLE IF NOT EXISTS `dokumenti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT 0,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_dokumenti_ugovori` (`ugovor_id`),
  KEY `FK_dokumenti_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_dokumenti_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_dokumenti_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.dokumenti: ~0 rows (approximately)
/*!40000 ALTER TABLE `dokumenti` DISABLE KEYS */;
/*!40000 ALTER TABLE `dokumenti` ENABLE KEYS */;

-- Dumping structure for table termini.komintenti
DROP TABLE IF EXISTS `komintenti`;
CREATE TABLE IF NOT EXISTS `komintenti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(255) NOT NULL,
  `kategorija` enum('Muzika','Fotograf','Torta','Dekoracija','Kokteli','Slatki sto','Voćni sto','Trubači','Animator') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.komintenti: ~0 rows (approximately)
/*!40000 ALTER TABLE `komintenti` DISABLE KEYS */;
/*!40000 ALTER TABLE `komintenti` ENABLE KEYS */;

-- Dumping structure for table termini.korisnici
DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prezime` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `korisnicko_ime` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lozinka` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivo` int(10) unsigned NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`korisnicko_ime`),
  KEY `FK_korisnici_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_korisnici_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.korisnici: ~2 rows (approximately)
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT IGNORE INTO `korisnici` (`id`, `ime`, `prezime`, `email`, `korisnicko_ime`, `lozinka`, `nivo`, `korisnik_id`, `created_at`) VALUES
	(0, 'SuperAdmin', '', '', 'ChaShaOne', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 1000, 1, '2020-01-20 08:27:55'),
	(1, 'Admin', '', '', 'admin', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 0, 1, '2020-01-08 19:47:03');
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;

-- Dumping structure for table termini.logovi
DROP TABLE IF EXISTS `logovi`;
CREATE TABLE IF NOT EXISTS `logovi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `datum` timestamp NOT NULL DEFAULT current_timestamp(),
  `izmene` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tip` enum('brisanje','dodavanje','izmena','upload') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dodavanje',
  `korisnik_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_logovi_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_logovi_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.logovi: ~0 rows (approximately)
/*!40000 ALTER TABLE `logovi` DISABLE KEYS */;
/*!40000 ALTER TABLE `logovi` ENABLE KEYS */;

-- Dumping structure for table termini.meniji
DROP TABLE IF EXISTS `meniji`;
CREATE TABLE IF NOT EXISTS `meniji` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stavke` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cena` decimal(15,2) NOT NULL DEFAULT 0.00,
  `napomena` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_s_meniji_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_s_meniji_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.meniji: ~0 rows (approximately)
/*!40000 ALTER TABLE `meniji` DISABLE KEYS */;
/*!40000 ALTER TABLE `meniji` ENABLE KEYS */;

-- Dumping structure for table termini.sale
DROP TABLE IF EXISTS `sale`;
CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_kapacitet_mesta` smallint(5) unsigned NOT NULL DEFAULT 0,
  `max_kapacitet_stolova` smallint(5) unsigned NOT NULL DEFAULT 0,
  `napomena` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `naziv` (`naziv`),
  KEY `FK_sale_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_sale_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.sale: ~0 rows (approximately)
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale` ENABLE KEYS */;

-- Dumping structure for table termini.sobe
DROP TABLE IF EXISTS `sobe`;
CREATE TABLE IF NOT EXISTS `sobe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(150) NOT NULL,
  `cena` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.sobe: ~0 rows (approximately)
/*!40000 ALTER TABLE `sobe` DISABLE KEYS */;
/*!40000 ALTER TABLE `sobe` ENABLE KEYS */;

-- Dumping structure for table termini.stavke_menija
DROP TABLE IF EXISTS `stavke_menija`;
CREATE TABLE IF NOT EXISTS `stavke_menija` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(150) NOT NULL,
  `cena` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `kategorija` enum('Predjelo','Sirevi','Čorba','Glavno jelo','Meso','Hleb','Piće') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.stavke_menija: ~0 rows (approximately)
/*!40000 ALTER TABLE `stavke_menija` DISABLE KEYS */;
/*!40000 ALTER TABLE `stavke_menija` ENABLE KEYS */;

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
  `zauzet` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `napomena` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `vaznost` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_termini_sale` (`sala_id`),
  KEY `FK_termini_korisnici` (`korisnik_id`),
  KEY `FK_termini_s_tip_dogadjaja` (`tip_dogadjaja_id`),
  CONSTRAINT `FK_termini_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_termini_s_tip_dogadjaja` FOREIGN KEY (`tip_dogadjaja_id`) REFERENCES `tipovi_dogadjaja` (`id`),
  CONSTRAINT `FK_termini_sale` FOREIGN KEY (`sala_id`) REFERENCES `sale` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.termini: ~0 rows (approximately)
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;

-- Dumping structure for table termini.tipovi_dogadjaja
DROP TABLE IF EXISTS `tipovi_dogadjaja`;
CREATE TABLE IF NOT EXISTS `tipovi_dogadjaja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `multi_ugovori` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tip` (`tip`),
  KEY `FK_s_tip_dogadjaja_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_s_tip_dogadjaja_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.tipovi_dogadjaja: ~0 rows (approximately)
/*!40000 ALTER TABLE `tipovi_dogadjaja` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipovi_dogadjaja` ENABLE KEYS */;

-- Dumping structure for table termini.ugovori
DROP TABLE IF EXISTS `ugovori`;
CREATE TABLE IF NOT EXISTS `ugovori` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `termin_id` int(10) unsigned NOT NULL,
  `broj_ugovora` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datum` date NOT NULL,
  `broj_mesta` smallint(5) unsigned NOT NULL,
  `broj_stolova` smallint(5) unsigned NOT NULL,
  `broj_mesta_po_stolu` smallint(5) unsigned NOT NULL,
  `prezime` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ime` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fizicko_pravno` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `naziv_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mb_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pib_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mesto_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresa_firme` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banka_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `racun_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iznos_menija` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `muzika_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `muzika_kom` int(10) unsigned NOT NULL,
  `muzika_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `fotograf_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `fotograf_kom` int(10) unsigned NOT NULL,
  `fotograf_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fotograf_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `torta_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `torta_kom` int(10) unsigned NOT NULL,
  `torta_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `torta_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `dekoracija_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `dekoracija_kom` int(10) unsigned NOT NULL,
  `dekoracija_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dekoracija_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `kokteli_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `kokteli_kom` int(10) unsigned NOT NULL,
  `kokteli_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kokteli_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `slatki_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `slatki_sto_kom` int(10) unsigned NOT NULL,
  `slatki_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slatki_sto_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `vocni_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `vocni_sto_kom` int(10) unsigned NOT NULL,
  `vocni_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vocni_sto_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `animator_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `animator_kom` int(10) unsigned NOT NULL,
  `animator_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `animator_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `trubaci_chk` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `trubaci_kom` int(10) unsigned NOT NULL,
  `trubaci_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trubaci_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `posebni_zahtevi_kom` int(10) unsigned NOT NULL,
  `posebni_zahtevi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posebni_zahtevi_iznos` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `iznos_dodatno` decimal(15,2) unsigned NOT NULL DEFAULT 0.00,
  `napomena` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_ugovori_termini` (`termin_id`),
  KEY `FK_ugovori_korisnici` (`korisnik_id`),
  KEY `FK_ugovori_komintenti` (`muzika_kom`),
  KEY `FK_ugovori_komintenti_2` (`fotograf_kom`),
  KEY `FK_ugovori_komintenti_3` (`torta_kom`),
  KEY `FK_ugovori_komintenti_4` (`dekoracija_kom`),
  KEY `FK_ugovori_komintenti_5` (`kokteli_kom`),
  KEY `FK_ugovori_komintenti_6` (`slatki_sto_kom`),
  KEY `FK_ugovori_komintenti_7` (`vocni_sto_kom`),
  KEY `FK_ugovori_komintenti_8` (`posebni_zahtevi_kom`),
  KEY `FK_ugovori_komintenti_9` (`animator_kom`),
  KEY `FK_ugovori_komintenti_10` (`trubaci_kom`),
  CONSTRAINT `FK_ugovori_komintenti` FOREIGN KEY (`muzika_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_10` FOREIGN KEY (`trubaci_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_2` FOREIGN KEY (`fotograf_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_3` FOREIGN KEY (`torta_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_4` FOREIGN KEY (`dekoracija_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_5` FOREIGN KEY (`kokteli_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_6` FOREIGN KEY (`slatki_sto_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_7` FOREIGN KEY (`vocni_sto_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_8` FOREIGN KEY (`posebni_zahtevi_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_komintenti_9` FOREIGN KEY (`animator_kom`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovori_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_ugovori_termini` FOREIGN KEY (`termin_id`) REFERENCES `termini` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.ugovori: ~0 rows (approximately)
/*!40000 ALTER TABLE `ugovori` DISABLE KEYS */;
/*!40000 ALTER TABLE `ugovori` ENABLE KEYS */;

-- Dumping structure for table termini.ugovor_meni
DROP TABLE IF EXISTS `ugovor_meni`;
CREATE TABLE IF NOT EXISTS `ugovor_meni` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT 0,
  `meni_id` int(10) unsigned NOT NULL DEFAULT 0,
  `komada` int(10) unsigned NOT NULL DEFAULT 0,
  `popust` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ugovor_id_meni_id` (`ugovor_id`,`meni_id`),
  KEY `FK_ugovor_meni_meniji` (`meni_id`),
  CONSTRAINT `FK_ugovor_meni_meniji` FOREIGN KEY (`meni_id`) REFERENCES `meniji` (`id`),
  CONSTRAINT `FK_ugovor_meni_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.ugovor_meni: ~0 rows (approximately)
/*!40000 ALTER TABLE `ugovor_meni` DISABLE KEYS */;
/*!40000 ALTER TABLE `ugovor_meni` ENABLE KEYS */;

-- Dumping structure for table termini.ugovor_soba
DROP TABLE IF EXISTS `ugovor_soba`;
CREATE TABLE IF NOT EXISTS `ugovor_soba` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT 0,
  `soba_id` int(10) unsigned NOT NULL DEFAULT 0,
  `komada` int(10) unsigned NOT NULL DEFAULT 0,
  `popust` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ugovor_id_soba_id` (`ugovor_id`,`soba_id`),
  KEY `FK_ugovor_soba_sobe` (`soba_id`),
  CONSTRAINT `FK_ugovor_soba_sobe` FOREIGN KEY (`soba_id`) REFERENCES `sobe` (`id`),
  CONSTRAINT `FK_ugovor_soba_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.ugovor_soba: ~0 rows (approximately)
/*!40000 ALTER TABLE `ugovor_soba` DISABLE KEYS */;
/*!40000 ALTER TABLE `ugovor_soba` ENABLE KEYS */;

-- Dumping structure for table termini.uplate
DROP TABLE IF EXISTS `uplate`;
CREATE TABLE IF NOT EXISTS `uplate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL,
  `datum` datetime NOT NULL,
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  `nacin_placanja` enum('gotovina','kartica','ček','faktura') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gotovina',
  `svrha_placanja` enum('Muzika','Fotograf','Torta','Dekoracija','Kokteli','Slatki sto','Voćni sto','Trubači','Animator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Muzika',
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `kapara` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_uplate_ugovori` (`ugovor_id`),
  KEY `FK_uplate_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_uplate_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_uplate_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.uplate: ~0 rows (approximately)
/*!40000 ALTER TABLE `uplate` DISABLE KEYS */;
/*!40000 ALTER TABLE `uplate` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

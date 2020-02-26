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
CREATE DATABASE IF NOT EXISTS `termini` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `termini`;

-- Dumping structure for table termini.dokumenti
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

-- Dumping data for table termini.dokumenti: ~0 rows (approximately)
/*!40000 ALTER TABLE `dokumenti` DISABLE KEYS */;
/*!40000 ALTER TABLE `dokumenti` ENABLE KEYS */;

-- Dumping structure for table termini.komintenti
CREATE TABLE IF NOT EXISTS `komintenti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(255) NOT NULL,
  `kategorija` enum('Muzika','Fotograf','Torta','Dekoracija','Kokteli','Slatki sto','Voćni sto','Trubači','Animator','Posebni zahtevi','Ostalo','Bez komitenta') NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `napomena` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_komintenti_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_komintenti_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.komintenti: ~3 rows (approximately)
/*!40000 ALTER TABLE `komintenti` DISABLE KEYS */;
INSERT IGNORE INTO `komintenti` (`id`, `naziv`, `kategorija`, `korisnik_id`, `created_at`, `napomena`) VALUES
	(1, 'FotoDragan', 'Fotograf', 1, '2020-02-24 08:44:50', NULL),
	(2, 'Mirka - torte', 'Torta', 1, '2020-02-24 08:45:08', NULL),
	(3, 'Flora', 'Dekoracija', 1, '2020-02-24 08:45:22', 'Ugovor ističe u martu 2020. Telefon 065 334455'),
	(4, 'Neki bend', 'Muzika', 1, '2020-02-24 23:11:39', 'Muzicari koji piju'),
	(5, 'Bez komitenta', 'Muzika', 0, '2020-02-25 23:13:24', NULL);
/*!40000 ALTER TABLE `komintenti` ENABLE KEYS */;

-- Dumping structure for table termini.korisnici
CREATE TABLE IF NOT EXISTS `korisnici` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prezime` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- Dumping data for table termini.korisnici: ~2 rows (approximately)
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT IGNORE INTO `korisnici` (`id`, `ime`, `prezime`, `email`, `korisnicko_ime`, `lozinka`, `nivo`, `korisnik_id`, `created_at`) VALUES
	(0, 'SuperAdmin', '', '', 'ChaShaOne', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 1000, 1, '2020-01-20 08:27:55'),
	(1, 'Admin', '', '', 'admin', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 0, 1, '2020-01-08 19:47:03');
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;

-- Dumping structure for table termini.logovi
CREATE TABLE IF NOT EXISTS `logovi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `izmene` text COLLATE utf8mb4_unicode_ci,
  `tip` enum('brisanje','dodavanje','izmena','upload') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dodavanje',
  `korisnik_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_logovi_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_logovi_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.logovi: ~31 rows (approximately)
/*!40000 ALTER TABLE `logovi` DISABLE KEYS */;
INSERT IGNORE INTO `logovi` (`id`, `opis`, `datum`, `izmene`, `tip`, `korisnik_id`) VALUES
	(1, '1, sale - naziv: PROBA', '2020-02-24 08:37:04', '', 'dodavanje', 1),
	(2, '1, sobe - naziv: Jednokrevetna, cena: 1000.00, ', '2020-02-24 08:37:23', '', 'dodavanje', 1),
	(3, '2, sobe - naziv: Dvokrevetna, cena: 2000.00, ', '2020-02-24 08:37:33', '', 'dodavanje', 1),
	(4, '3, sobe - naziv: Trokrevetna, cena: 3000.00, ', '2020-02-24 08:37:42', '', 'dodavanje', 1),
	(5, '2, sale - naziv: PROBA II', '2020-02-24 08:37:57', '', 'dodavanje', 1),
	(6, '3, sale - naziv: PROBA III', '2020-02-24 08:38:06', '', 'dodavanje', 1),
	(7, '1, komintenti - naziv: FotoDragan, kategorija: Fotograf, ', '2020-02-24 08:44:50', '', 'dodavanje', 1),
	(8, '2, komintenti - naziv: Mirka - torte, kategorija: Torta, ', '2020-02-24 08:45:09', '', 'dodavanje', 1),
	(9, '3, komintenti - naziv: Flora, kategorija: Dekoracija, ', '2020-02-24 08:45:22', '', 'dodavanje', 1),
	(10, '3, komintenti - naziv: Flora', '2020-02-24 08:54:34', 'a:0:{}', 'izmena', 1),
	(11, '3, komintenti - naziv: Flora', '2020-02-24 09:00:05', 'a:1:{s:8:"napomena";a:2:{s:14:"stara_vrednost";N;s:13:"nova_vrednost";s:47:"Ugovor ističe u martu 2020. Telefon 065 334455";}}', 'izmena', 1),
	(12, '9, termini - opis: Probni događaj I (rođendan)', '2020-02-24 09:02:13', '', 'dodavanje', 1),
	(13, '9, termini - opis: Probni događaj I (rođendan)', '2020-02-24 21:58:54', 'a:0:{}', 'izmena', 1),
	(14, '9, termini - opis: Probni događaj I (rođendan)', '2020-02-24 21:58:57', 'a:1:{s:6:"zauzet";a:2:{s:14:"stara_vrednost";i:1;s:13:"nova_vrednost";i:0;}}', 'izmena', 1),
	(15, '9, termini - opis: Probni događaj I (rođendan)', '2020-02-24 21:59:04', 'a:0:{}', 'izmena', 1),
	(16, '9, termini - opis: Probni događaj I (rođendan)', '2020-02-24 21:59:17', 'a:1:{s:6:"zauzet";a:2:{s:14:"stara_vrednost";i:1;s:13:"nova_vrednost";i:0;}}', 'izmena', 1),
	(17, '4, komintenti - naziv: Neki bend, kategorija: Muzika, ', '2020-02-24 23:11:39', '', 'dodavanje', 1),
	(18, '1, meniji - naziv: Meni 1', '2020-02-25 21:08:11', 'a:2:{s:6:"stavke";a:2:{s:14:"stara_vrednost";s:1:"1";s:13:"nova_vrednost";s:3:"1,2";}s:4:"cena";a:2:{s:14:"stara_vrednost";s:7:"2000.00";s:13:"nova_vrednost";s:7:"3000.00";}}', 'izmena', 1),
	(19, '2, meniji - naziv: Meni 2', '2020-02-25 21:08:20', 'a:1:{s:4:"cena";a:2:{s:14:"stara_vrednost";s:7:"3000.00";s:13:"nova_vrednost";s:7:"2000.00";}}', 'izmena', 1),
	(20, '3, meniji - naziv: Meni 3', '2020-02-25 21:08:30', 'a:1:{s:4:"cena";a:2:{s:14:"stara_vrednost";s:7:"4000.00";s:13:"nova_vrednost";s:7:"2000.00";}}', 'izmena', 1),
	(21, '3, meniji - naziv: Meni 3', '2020-02-25 21:08:41', 'a:1:{s:4:"cena";a:2:{s:14:"stara_vrednost";s:7:"2000.00";s:13:"nova_vrednost";s:7:"2500.00";}}', 'izmena', 1),
	(22, '10, termini - opis: Test dodavanja ugovora', '2020-02-25 22:56:27', '', 'dodavanje', 1),
	(23, '5, ugovori - broj_ugovora: ', '2020-02-25 23:25:30', '', 'dodavanje', 1),
	(24, '10, termini - opis: Test dodavanja ugovora', '2020-02-26 09:17:38', 'a:1:{s:6:"zauzet";a:2:{s:14:"stara_vrednost";i:1;s:13:"nova_vrednost";i:0;}}', 'izmena', 1),
	(25, '5, ugovori - broj_ugovora: ', '2020-02-26 10:39:24', 'a:0:{}', 'izmena', 1),
	(26, '5, ugovori - broj_ugovora: ', '2020-02-26 10:42:12', 'a:6:{s:10:"broj_mesta";a:2:{s:14:"stara_vrednost";i:181;s:13:"nova_vrednost";i:180;}s:12:"broj_stolova";a:2:{s:14:"stara_vrednost";i:14;s:13:"nova_vrednost";i:15;}s:19:"broj_mesta_po_stolu";a:2:{s:14:"stara_vrednost";i:13;s:13:"nova_vrednost";i:12;}s:12:"adresa_firme";a:2:{s:14:"stara_vrednost";s:16:"Crvenog krsta 12";s:13:"nova_vrednost";s:17:"Crvenog krsta 122";}s:14:"animator_iznos";a:2:{s:14:"stara_vrednost";s:4:"0.00";s:13:"nova_vrednost";s:7:"3000.00";}s:13:"iznos_dodatno";a:2:{s:14:"stara_vrednost";s:8:"18000.00";s:13:"nova_vrednost";s:8:"21000.00";}}', 'izmena', 1),
	(27, '5, ugovori - broj_ugovora: ', '2020-02-26 10:58:26', 'a:5:{s:11:"naziv_firme";a:2:{s:14:"stara_vrednost";s:0:"";s:13:"nova_vrednost";s:9:"ChaShaOne";}s:8:"mb_firme";a:2:{s:14:"stara_vrednost";s:0:"";s:13:"nova_vrednost";s:10:"9876543210";}s:9:"pib_firme";a:2:{s:14:"stara_vrednost";s:0:"";s:13:"nova_vrednost";s:10:"0123456789";}s:11:"banka_firme";a:2:{s:14:"stara_vrednost";s:0:"";s:13:"nova_vrednost";s:12:"Banca Intesa";}s:11:"racun_firme";a:2:{s:14:"stara_vrednost";s:0:"";s:13:"nova_vrednost";s:20:"160-0000000000001-99";}}', 'izmena', 1),
	(28, '5, ugovori - broj_ugovora: ', '2020-02-26 11:11:27', 'a:1:{s:20:"posebni_zahtevi_opis";a:2:{s:14:"stara_vrednost";s:5:"nesto";s:13:"nova_vrednost";s:13:"nesto posebno";}}', 'izmena', 1),
	(29, '5, ugovori - broj_ugovora: ', '2020-02-26 11:34:57', 'a:0:{}', 'izmena', 1),
	(30, '1, stavke_menija - naziv: Losos', '2020-02-26 17:34:00', 'a:2:{s:14:"vreme_pripreme";a:2:{s:14:"stara_vrednost";i:0;s:13:"nova_vrednost";i:35;}s:8:"kolicina";a:2:{s:14:"stara_vrednost";i:0;s:13:"nova_vrednost";i:250;}}', 'izmena', 1),
	(31, '2, ugovor_meni - ugovor_id: 5, meni_id: 1, ', '2020-02-26 19:37:44', '', 'dodavanje', 1),
	(32, '3, ugovor_meni - ugovor_id: 5, meni_id: 2, ', '2020-02-26 20:39:00', '', 'dodavanje', 1),
	(33, '4, ugovor_meni - ugovor_id: 5, meni_id: 3, ', '2020-02-26 20:39:28', '', 'dodavanje', 1),
	(34, '1, ugovor_soba - ugovor_id: 5, soba_id: 1, ', '2020-02-26 21:12:36', '', 'dodavanje', 1);
/*!40000 ALTER TABLE `logovi` ENABLE KEYS */;

-- Dumping structure for table termini.meniji
CREATE TABLE IF NOT EXISTS `meniji` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stavke` text COLLATE utf8mb4_unicode_ci,
  `cena` decimal(15,2) NOT NULL DEFAULT '0.00',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_s_meniji_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_s_meniji_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.meniji: ~3 rows (approximately)
/*!40000 ALTER TABLE `meniji` DISABLE KEYS */;
INSERT IGNORE INTO `meniji` (`id`, `naziv`, `stavke`, `cena`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'Meni 1', '1,2', 3000.00, 'Napomena A', 1, '2020-02-24 08:43:22'),
	(2, 'Meni 2', '2', 2000.00, 'Napomena B', 1, '2020-02-24 08:43:22'),
	(3, 'Meni 3', '2', 2500.00, 'Luksuzni meni', 1, '2020-02-24 08:43:22');
/*!40000 ALTER TABLE `meniji` ENABLE KEYS */;

-- Dumping structure for table termini.sale
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

-- Dumping data for table termini.sale: ~3 rows (approximately)
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
INSERT IGNORE INTO `sale` (`id`, `naziv`, `max_kapacitet_mesta`, `max_kapacitet_stolova`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'PROBA', 300, 30, '', 1, '2020-02-24 08:37:04'),
	(2, 'PROBA II', 200, 20, '', 1, '2020-02-24 08:37:57'),
	(3, 'PROBA III', 100, 10, '', 1, '2020-02-24 08:38:06');
/*!40000 ALTER TABLE `sale` ENABLE KEYS */;

-- Dumping structure for table termini.sobe
CREATE TABLE IF NOT EXISTS `sobe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(150) NOT NULL,
  `cena` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_sobe_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_sobe_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.sobe: ~3 rows (approximately)
/*!40000 ALTER TABLE `sobe` DISABLE KEYS */;
INSERT IGNORE INTO `sobe` (`id`, `naziv`, `cena`, `korisnik_id`, `created_at`) VALUES
	(1, 'Jednokrevetna', 1000.00, 1, '2020-02-24 08:37:23'),
	(2, 'Dvokrevetna', 2000.00, 1, '2020-02-24 08:37:33'),
	(3, 'Trokrevetna', 3000.00, 1, '2020-02-24 08:37:42');
/*!40000 ALTER TABLE `sobe` ENABLE KEYS */;

-- Dumping structure for table termini.stavke_menija
CREATE TABLE IF NOT EXISTS `stavke_menija` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(150) NOT NULL,
  `cena` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `kategorija` enum('Predjelo','Sirevi','Čorba','Glavno jelo','Meso','Hleb','Piće') NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `vreme_pripreme` int(10) unsigned DEFAULT '0',
  `kolicina` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_stavke_menija_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_stavke_menija_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.stavke_menija: ~2 rows (approximately)
/*!40000 ALTER TABLE `stavke_menija` DISABLE KEYS */;
INSERT IGNORE INTO `stavke_menija` (`id`, `naziv`, `cena`, `kategorija`, `korisnik_id`, `created_at`, `vreme_pripreme`, `kolicina`) VALUES
	(1, 'Losos', 1000.00, 'Predjelo', 0, '2020-02-24 09:38:30', 35, 250),
	(2, 'Pršuta', 2000.00, 'Predjelo', 0, '2020-02-24 09:38:30', 0, 0);
/*!40000 ALTER TABLE `stavke_menija` ENABLE KEYS */;

-- Dumping structure for table termini.termini
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
  `vaznost` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_termini_sale` (`sala_id`),
  KEY `FK_termini_korisnici` (`korisnik_id`),
  KEY `FK_termini_s_tip_dogadjaja` (`tip_dogadjaja_id`),
  CONSTRAINT `FK_termini_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_termini_s_tip_dogadjaja` FOREIGN KEY (`tip_dogadjaja_id`) REFERENCES `tipovi_dogadjaja` (`id`),
  CONSTRAINT `FK_termini_sale` FOREIGN KEY (`sala_id`) REFERENCES `sale` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.termini: ~0 rows (approximately)
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
INSERT IGNORE INTO `termini` (`id`, `sala_id`, `tip_dogadjaja_id`, `datum`, `pocetak`, `kraj`, `opis`, `zauzet`, `napomena`, `korisnik_id`, `created_at`, `vaznost`) VALUES
	(9, 1, 1, '2020-02-25', '10:00:00', '22:00:00', 'Probni događaj I (rođendan)', 0, 'Važnost 3 dana', 1, '2020-02-24 09:02:13', '2020-02-27'),
	(10, 2, 3, '2020-02-29', '08:00:00', '23:59:00', 'Test dodavanja ugovora', 1, '', 1, '2020-02-25 22:56:27', '2020-03-03');
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;

-- Dumping structure for table termini.tipovi_dogadjaja
CREATE TABLE IF NOT EXISTS `tipovi_dogadjaja` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tip` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `multi_ugovori` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tip` (`tip`),
  KEY `FK_s_tip_dogadjaja_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_s_tip_dogadjaja_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.tipovi_dogadjaja: ~4 rows (approximately)
/*!40000 ALTER TABLE `tipovi_dogadjaja` DISABLE KEYS */;
INSERT IGNORE INTO `tipovi_dogadjaja` (`id`, `tip`, `multi_ugovori`, `korisnik_id`, `created_at`) VALUES
	(1, 'Proslava rođendana', 1, 1, '2020-02-24 08:39:28'),
	(2, 'Svadba', 0, 1, '2020-02-24 08:39:28'),
	(3, 'Simpozijum', 1, 1, '2020-02-24 08:39:28'),
	(4, 'Konferencija', 1, 1, '2020-02-24 08:39:28');
/*!40000 ALTER TABLE `tipovi_dogadjaja` ENABLE KEYS */;

-- Dumping structure for table termini.ugovori
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
  `fizicko_pravno` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `naziv_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mb_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pib_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mesto_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresa_firme` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banka_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `racun_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `muzika_kom` int(10) unsigned DEFAULT NULL,
  `muzika_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muzika_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `fotograf_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fotograf_kom` int(10) unsigned DEFAULT NULL,
  `fotograf_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fotograf_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `torta_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `torta_kom` int(10) unsigned DEFAULT NULL,
  `torta_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `torta_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `dekoracija_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dekoracija_kom` int(10) unsigned DEFAULT NULL,
  `dekoracija_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dekoracija_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `kokteli_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `kokteli_kom` int(10) unsigned DEFAULT NULL,
  `kokteli_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kokteli_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `slatki_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `slatki_sto_kom` int(10) unsigned DEFAULT NULL,
  `slatki_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slatki_sto_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `vocni_sto_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vocni_sto_kom` int(10) unsigned DEFAULT NULL,
  `vocni_sto_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vocni_sto_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `animator_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `animator_kom` int(10) unsigned DEFAULT NULL,
  `animator_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `animator_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `trubaci_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `trubaci_kom` int(10) unsigned DEFAULT NULL,
  `trubaci_opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trubaci_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `posebni_zahtevi_chk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `posebni_zahtevi_kom` int(10) unsigned DEFAULT NULL,
  `posebni_zahtevi_opis` text COLLATE utf8mb4_unicode_ci,
  `posebni_zahtevi_iznos` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `iznos_meni` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `iznos_sobe` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `iznos_dodatno` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table termini.ugovori: ~2 rows (approximately)
/*!40000 ALTER TABLE `ugovori` DISABLE KEYS */;
INSERT IGNORE INTO `ugovori` (`id`, `termin_id`, `broj_ugovora`, `datum`, `broj_mesta`, `broj_stolova`, `broj_mesta_po_stolu`, `prezime`, `ime`, `telefon`, `email`, `fizicko_pravno`, `naziv_firme`, `mb_firme`, `pib_firme`, `mesto_firme`, `adresa_firme`, `banka_firme`, `racun_firme`, `muzika_chk`, `muzika_kom`, `muzika_opis`, `muzika_iznos`, `fotograf_chk`, `fotograf_kom`, `fotograf_opis`, `fotograf_iznos`, `torta_chk`, `torta_kom`, `torta_opis`, `torta_iznos`, `dekoracija_chk`, `dekoracija_kom`, `dekoracija_opis`, `dekoracija_iznos`, `kokteli_chk`, `kokteli_kom`, `kokteli_opis`, `kokteli_iznos`, `slatki_sto_chk`, `slatki_sto_kom`, `slatki_sto_opis`, `slatki_sto_iznos`, `vocni_sto_chk`, `vocni_sto_kom`, `vocni_sto_opis`, `vocni_sto_iznos`, `animator_chk`, `animator_kom`, `animator_opis`, `animator_iznos`, `trubaci_chk`, `trubaci_kom`, `trubaci_opis`, `trubaci_iznos`, `posebni_zahtevi_chk`, `posebni_zahtevi_kom`, `posebni_zahtevi_opis`, `posebni_zahtevi_iznos`, `iznos_meni`, `iznos_sobe`, `iznos_dodatno`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 9, '01/2020', '2020-02-24', 150, 15, 10, 'Petar', 'Petrović', '03444567', 'pera@ptt.us', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 0, NULL, NULL, 0.00, 150000.00, 0.00, 0.00, 'Prvi ugovor za testiranje', 1, '2020-02-24 09:13:39'),
	(5, 10, '', '2020-02-26', 180, 15, 12, 'Canic', 'Nenad', '065 555 999', 'chana@test.com', 1, 'ChaShaOne', '9876543210', '0123456789', 'Kragujevac', 'Crvenog krsta 122', 'Banca Intesa', '160-0000000000001-99', 1, 5, '', 1000.00, 1, 1, '', 5000.00, 1, 5, 'Organizator', 1000.00, 1, 3, '', 6000.00, 0, 5, '', 0.00, 0, 5, '', 0.00, 0, 5, '', 0.00, 1, 5, '', 3000.00, 0, 5, '', 0.00, 1, 5, 'nesto posebno', 5000.00, 0.00, 0.00, 21000.00, 'nap', 1, '2020-02-25 23:25:30');
/*!40000 ALTER TABLE `ugovori` ENABLE KEYS */;

-- Dumping structure for table termini.ugovor_meni
CREATE TABLE IF NOT EXISTS `ugovor_meni` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `meni_id` int(10) unsigned NOT NULL DEFAULT '0',
  `komada` int(10) unsigned NOT NULL DEFAULT '0',
  `popust` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cena_sa_popustom` decimal(15,2) NOT NULL DEFAULT '0.00',
  `iznos` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ugovor_id_meni_id` (`ugovor_id`,`meni_id`),
  KEY `FK_ugovor_meni_meniji` (`meni_id`),
  CONSTRAINT `FK_ugovor_meni_meniji` FOREIGN KEY (`meni_id`) REFERENCES `meniji` (`id`),
  CONSTRAINT `FK_ugovor_meni_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.ugovor_meni: ~2 rows (approximately)
/*!40000 ALTER TABLE `ugovor_meni` DISABLE KEYS */;
INSERT IGNORE INTO `ugovor_meni` (`id`, `ugovor_id`, `meni_id`, `komada`, `popust`, `cena_sa_popustom`, `iznos`) VALUES
	(1, 1, 1, 150, 100.00, 0.00, 0.00),
	(2, 5, 1, 180, 100.00, 2900.00, 522000.00),
	(3, 5, 2, 100, 200.00, 1800.00, 180000.00),
	(4, 5, 3, 100, -500.00, 3000.00, 300000.00);
/*!40000 ALTER TABLE `ugovor_meni` ENABLE KEYS */;

-- Dumping structure for table termini.ugovor_soba
CREATE TABLE IF NOT EXISTS `ugovor_soba` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `soba_id` int(10) unsigned NOT NULL DEFAULT '0',
  `komada` int(10) unsigned NOT NULL DEFAULT '0',
  `popust` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cena_sa_popustom` decimal(15,2) NOT NULL DEFAULT '0.00',
  `iznos` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ugovor_id_soba_id` (`ugovor_id`,`soba_id`),
  KEY `FK_ugovor_soba_sobe` (`soba_id`),
  CONSTRAINT `FK_ugovor_soba_sobe` FOREIGN KEY (`soba_id`) REFERENCES `sobe` (`id`),
  CONSTRAINT `FK_ugovor_soba_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table termini.ugovor_soba: ~0 rows (approximately)
/*!40000 ALTER TABLE `ugovor_soba` DISABLE KEYS */;
INSERT IGNORE INTO `ugovor_soba` (`id`, `ugovor_id`, `soba_id`, `komada`, `popust`, `opis`, `cena_sa_popustom`, `iznos`) VALUES
	(1, 5, 1, 5, 50.00, NULL, 950.00, 4750.00);
/*!40000 ALTER TABLE `ugovor_soba` ENABLE KEYS */;

-- Dumping structure for table termini.uplate
CREATE TABLE IF NOT EXISTS `uplate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL,
  `datum` datetime NOT NULL,
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `nacin_placanja` enum('gotovina','kartica','ček','faktura') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gotovina',
  `svrha_placanja` enum('Muzika','Fotograf','Torta','Dekoracija','Kokteli','Slatki sto','Voćni sto','Trubači','Animator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Muzika',
  `opis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kapara` tinyint(3) unsigned NOT NULL DEFAULT '0',
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

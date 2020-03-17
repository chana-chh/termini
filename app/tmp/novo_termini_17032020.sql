/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

DROP DATABASE IF EXISTS `termini`;
CREATE DATABASE IF NOT EXISTS `termini` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `termini`;

DROP TABLE IF EXISTS `dodatne_usluge`;
CREATE TABLE IF NOT EXISTS `dodatne_usluge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL,
  `komitent_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cena` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opis` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_ugovor_dodatne_ugovori` (`ugovor_id`),
  KEY `FK_dodatne_usluge_komintenti` (`komitent_id`),
  CONSTRAINT `FK_dodatne_usluge_komintenti` FOREIGN KEY (`komitent_id`) REFERENCES `komintenti` (`id`),
  CONSTRAINT `FK_ugovor_dodatne_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `dodatne_usluge`;
/*!40000 ALTER TABLE `dodatne_usluge` DISABLE KEYS */;
INSERT INTO `dodatne_usluge` (`id`, `ugovor_id`, `komitent_id`, `status`, `cena`, `opis`) VALUES
	(1, 7, 2, 1, 550000.00, 'dsaf'),
	(2, 7, 3, 1, 35000.00, 'Asdfert');
/*!40000 ALTER TABLE `dodatne_usluge` ENABLE KEYS */;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `dokumenti`;
/*!40000 ALTER TABLE `dokumenti` DISABLE KEYS */;
INSERT INTO `dokumenti` (`id`, `ugovor_id`, `link`, `opis`, `korisnik_id`, `created_at`) VALUES
	(1, 5, 'http://localhost/termini/pub/doc/5_test_5218771d0ef7bb42.pdf', 'test', 1, '2020-02-29 15:08:54');
/*!40000 ALTER TABLE `dokumenti` ENABLE KEYS */;

DROP TABLE IF EXISTS `kategorije`;
CREATE TABLE IF NOT EXISTS `kategorije` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_kategorije_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_kategorije_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `kategorije`;
/*!40000 ALTER TABLE `kategorije` DISABLE KEYS */;
INSERT INTO `kategorije` (`id`, `naziv`, `korisnik_id`, `created_at`) VALUES
	(1, 'Organizator', 1, '2020-03-13 12:51:18'),
	(2, 'Torte', 1, '2020-03-13 12:51:18'),
	(3, 'Dekoracije', 1, '2020-03-13 12:51:18'),
	(4, 'Animator', 1, '2020-03-13 12:51:18'),
	(5, 'Fotograf', 1, '2020-03-13 12:51:18');
/*!40000 ALTER TABLE `kategorije` ENABLE KEYS */;

DROP TABLE IF EXISTS `komintenti`;
CREATE TABLE IF NOT EXISTS `komintenti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(255) NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `kategorija_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `napomena` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_komintenti_korisnici` (`korisnik_id`),
  KEY `FK_komintenti_kategorije` (`kategorija_id`),
  CONSTRAINT `FK_komintenti_kategorije` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorije` (`id`),
  CONSTRAINT `FK_komintenti_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

DELETE FROM `komintenti`;
/*!40000 ALTER TABLE `komintenti` DISABLE KEYS */;
INSERT INTO `komintenti` (`id`, `naziv`, `korisnik_id`, `kategorija_id`, `created_at`, `napomena`) VALUES
	(1, 'Organizator', 1, 1, '2020-02-24 08:44:50', NULL),
	(2, 'Mirka - torte', 1, 2, '2020-02-24 08:45:08', NULL),
	(3, 'Flora', 1, 3, '2020-02-24 08:45:22', 'Ugovor ističe u martu 2020. Telefon 065 334455'),
	(4, 'Neki animator', 1, 4, '2020-02-24 23:11:39', 'Ludaci'),
	(5, 'FotoDragan', 0, 5, '2020-02-25 23:13:24', NULL),
	(6, 'Chanky', 0, 3, '2020-03-13 13:26:31', 'A!');
/*!40000 ALTER TABLE `komintenti` ENABLE KEYS */;

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`korisnicko_ime`),
  KEY `FK_korisnici_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_korisnici_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `korisnici`;
/*!40000 ALTER TABLE `korisnici` DISABLE KEYS */;
INSERT INTO `korisnici` (`id`, `ime`, `prezime`, `email`, `korisnicko_ime`, `lozinka`, `nivo`, `korisnik_id`, `created_at`) VALUES
	(0, 'SuperAdmin', '', '', 'ninja', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 1000, 1, '2020-01-20 08:27:55'),
	(1, 'Admin', '', '', 'admin', '$2y$10$RWD9bVOhe1GlWER7DVKMAukc2/OAwpoAvC/8A.wYOpGtqMFTezQHm', 0, 1, '2020-01-08 19:47:03');
/*!40000 ALTER TABLE `korisnici` ENABLE KEYS */;

DROP TABLE IF EXISTS `logovi`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `logovi`;
/*!40000 ALTER TABLE `logovi` DISABLE KEYS */;
/*!40000 ALTER TABLE `logovi` ENABLE KEYS */;

DROP TABLE IF EXISTS `meniji`;
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

DELETE FROM `meniji`;
/*!40000 ALTER TABLE `meniji` DISABLE KEYS */;
INSERT INTO `meniji` (`id`, `naziv`, `stavke`, `cena`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'Meni 1', '1,2,3', 3000.00, 'Napomena A', 1, '2020-02-24 08:43:22'),
	(2, 'Meni 2', '2', 2000.00, 'Napomena B', 1, '2020-02-24 08:43:22'),
	(3, 'Meni 3', '2', 2500.00, 'Luksuzni meni', 1, '2020-02-24 08:43:22');
/*!40000 ALTER TABLE `meniji` ENABLE KEYS */;

DROP TABLE IF EXISTS `podsetnici`;
CREATE TABLE IF NOT EXISTS `podsetnici` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `tekst` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reseno` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_podsetnici_ugovori` (`ugovor_id`),
  KEY `FK_podsetnici_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_podsetnici_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_podsetnici_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `podsetnici`;
/*!40000 ALTER TABLE `podsetnici` DISABLE KEYS */;
INSERT INTO `podsetnici` (`id`, `ugovor_id`, `datum`, `tekst`, `reseno`, `korisnik_id`, `created_at`) VALUES
	(1, 5, '2020-03-08', 'Podsetnik za 8. mart\r\nUraditi nesto', 0, 1, '2020-02-28 23:11:48'),
	(2, 5, '2020-03-05', 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus eligendi voluptas cumque aliquam quod. Veritatis consequuntur ducimus atque aliquam nihil?', 1, 1, '2020-02-28 23:15:53');
/*!40000 ALTER TABLE `podsetnici` ENABLE KEYS */;

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

DELETE FROM `sale`;
/*!40000 ALTER TABLE `sale` DISABLE KEYS */;
INSERT INTO `sale` (`id`, `naziv`, `max_kapacitet_mesta`, `max_kapacitet_stolova`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 'PROBA', 300, 30, '', 1, '2020-02-24 08:37:04'),
	(2, 'PROBA II', 200, 20, '', 1, '2020-02-24 08:37:57'),
	(3, 'PROBA III', 100, 10, '', 1, '2020-02-24 08:38:06');
/*!40000 ALTER TABLE `sale` ENABLE KEYS */;

DROP TABLE IF EXISTS `sobe`;
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

DELETE FROM `sobe`;
/*!40000 ALTER TABLE `sobe` DISABLE KEYS */;
INSERT INTO `sobe` (`id`, `naziv`, `cena`, `korisnik_id`, `created_at`) VALUES
	(1, 'Jednokrevetna', 1000.00, 1, '2020-02-24 08:37:23'),
	(2, 'Dvokrevetna', 2000.00, 1, '2020-02-24 08:37:33'),
	(3, 'Trokrevetna', 3000.00, 1, '2020-02-24 08:37:42');
/*!40000 ALTER TABLE `sobe` ENABLE KEYS */;

DROP TABLE IF EXISTS `stavke_menija`;
CREATE TABLE IF NOT EXISTS `stavke_menija` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `naziv` varchar(150) NOT NULL,
  `cena` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `kategorija` enum('Predjelo','Sirevi','Čorba','Glavno jelo','Jela od mesa','Hleb','Piće') NOT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `vreme_pripreme` int(10) unsigned DEFAULT '0',
  `kolicina` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_stavke_menija_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_stavke_menija_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

DELETE FROM `stavke_menija`;
/*!40000 ALTER TABLE `stavke_menija` DISABLE KEYS */;
INSERT INTO `stavke_menija` (`id`, `naziv`, `cena`, `kategorija`, `korisnik_id`, `created_at`, `vreme_pripreme`, `kolicina`) VALUES
	(1, 'Losos', 1000.00, 'Predjelo', 0, '2020-02-24 09:38:30', 35, 250),
	(2, 'Pršuta', 2000.00, 'Predjelo', 0, '2020-02-24 09:38:30', 0, 220),
	(3, 'Sirac nekit', 500.00, 'Predjelo', 1, '2020-03-10 14:30:05', 10, 190),
	(4, 'Kiso kupus', 750.00, 'Predjelo', 0, '2020-03-10 14:44:24', 150, 2100),
	(5, 'Kobaja', 100.00, 'Jela od mesa', 0, '2020-03-11 09:59:17', 10, 100);
/*!40000 ALTER TABLE `stavke_menija` ENABLE KEYS */;

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
  `vaznost` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_termini_sale` (`sala_id`),
  KEY `FK_termini_korisnici` (`korisnik_id`),
  KEY `FK_termini_s_tip_dogadjaja` (`tip_dogadjaja_id`),
  CONSTRAINT `FK_termini_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_termini_s_tip_dogadjaja` FOREIGN KEY (`tip_dogadjaja_id`) REFERENCES `tipovi_dogadjaja` (`id`),
  CONSTRAINT `FK_termini_sale` FOREIGN KEY (`sala_id`) REFERENCES `sale` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `termini`;
/*!40000 ALTER TABLE `termini` DISABLE KEYS */;
INSERT INTO `termini` (`id`, `sala_id`, `tip_dogadjaja_id`, `datum`, `pocetak`, `kraj`, `opis`, `zauzet`, `napomena`, `korisnik_id`, `created_at`, `vaznost`) VALUES
	(9, 1, 1, '2020-02-25', '10:00:00', '22:00:00', 'Probni događaj I (rođendan)', 0, 'Važnost 3 dana', 1, '2020-02-24 09:02:13', '2020-02-27'),
	(10, 2, 3, '2020-02-29', '08:00:00', '23:59:00', 'Test dodavanja ugovora', 1, '', 1, '2020-02-25 22:56:27', '2020-03-03');
/*!40000 ALTER TABLE `termini` ENABLE KEYS */;

DROP TABLE IF EXISTS `tipovi_dogadjaja`;
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

DELETE FROM `tipovi_dogadjaja`;
/*!40000 ALTER TABLE `tipovi_dogadjaja` DISABLE KEYS */;
INSERT INTO `tipovi_dogadjaja` (`id`, `tip`, `multi_ugovori`, `korisnik_id`, `created_at`) VALUES
	(1, 'Proslava rođendana', 1, 1, '2020-02-24 08:39:28'),
	(2, 'Svadba', 0, 1, '2020-02-24 08:39:28'),
	(3, 'Simpozijum', 1, 1, '2020-02-24 08:39:28'),
	(4, 'Konferencija', 1, 1, '2020-02-24 08:39:28');
/*!40000 ALTER TABLE `tipovi_dogadjaja` ENABLE KEYS */;

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
  `fizicko_pravno` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `naziv_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mb_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pib_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mesto_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresa_firme` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banka_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `racun_firme` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iznos_meni` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `iznos_dodatno` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `iznos_sobe` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `aneks_broj_mesta` smallint(5) unsigned NOT NULL,
  `aneks_iznos_meni` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `aneks_iznos_dodatno` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `aneks_iznos_sobe` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `napomena` text COLLATE utf8mb4_unicode_ci,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_ugovori_termini` (`termin_id`),
  KEY `FK_ugovori_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_ugovori_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_ugovori_termini` FOREIGN KEY (`termin_id`) REFERENCES `termini` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `ugovori`;
/*!40000 ALTER TABLE `ugovori` DISABLE KEYS */;
INSERT INTO `ugovori` (`id`, `termin_id`, `broj_ugovora`, `datum`, `broj_mesta`, `broj_stolova`, `broj_mesta_po_stolu`, `prezime`, `ime`, `telefon`, `email`, `fizicko_pravno`, `naziv_firme`, `mb_firme`, `pib_firme`, `mesto_firme`, `adresa_firme`, `banka_firme`, `racun_firme`, `iznos_meni`, `iznos_dodatno`, `iznos_sobe`, `aneks_broj_mesta`, `aneks_iznos_meni`, `aneks_iznos_dodatno`, `aneks_iznos_sobe`, `napomena`, `korisnik_id`, `created_at`) VALUES
	(1, 9, '01/2020', '2020-02-24', 150, 15, 10, 'Petar', 'Petrović', '03444567', 'pera@ptt.us', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 150000.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 'Prvi ugovor za testiranje', 1, '2020-02-24 09:13:39'),
	(5, 10, '', '2020-02-26', 120, 12, 10, 'Canic', 'Nenad', '065 555 999', 'chana@test.com', 1, 'ChaShaOne', '9876543210', '0123456789', 'Kragujevac', 'Crvenog krsta 122', 'Banca Intesa', '160-0000000000001-99', 473800.00, 25000.00, 25000.00, 180, 473000.00, 25000.00, 25000.00, 'nap', 1, '2020-02-25 23:25:30'),
	(6, 10, '335/2020', '2020-03-12', 20, 2, 10, 'Peric', 'Pera', '', '', 0, '', '', '', '', '', '', '', 38000.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, '', 0, '2020-03-12 12:01:42'),
	(7, 10, '', '2020-03-17', 60, 6, 10, 'Chabar', 'Karim', '', '', 0, '', '', '', '', '', '', '', 0.00, 0.00, 2000.00, 0, 0.00, 0.00, 0.00, '', 0, '2020-03-17 12:27:57');
/*!40000 ALTER TABLE `ugovori` ENABLE KEYS */;

DROP TABLE IF EXISTS `ugovor_meni`;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

DELETE FROM `ugovor_meni`;
/*!40000 ALTER TABLE `ugovor_meni` DISABLE KEYS */;
INSERT INTO `ugovor_meni` (`id`, `ugovor_id`, `meni_id`, `komada`, `popust`, `cena_sa_popustom`, `iznos`) VALUES
	(1, 1, 1, 150, 100.00, 0.00, 0.00),
	(5, 5, 1, 100, 200.00, 2800.00, 280000.00),
	(6, 5, 2, 70, -100.00, 2100.00, 147000.00),
	(7, 5, 3, 20, 200.00, 2300.00, 46000.00),
	(8, 6, 2, 20, 100.00, 1900.00, 38000.00);
/*!40000 ALTER TABLE `ugovor_meni` ENABLE KEYS */;

DROP TABLE IF EXISTS `ugovor_soba`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

DELETE FROM `ugovor_soba`;
/*!40000 ALTER TABLE `ugovor_soba` DISABLE KEYS */;
INSERT INTO `ugovor_soba` (`id`, `ugovor_id`, `soba_id`, `komada`, `popust`, `opis`, `cena_sa_popustom`, `iznos`) VALUES
	(2, 5, 1, 5, 0.00, NULL, 1000.00, 5000.00),
	(3, 5, 2, 10, 200.00, NULL, 1800.00, 18000.00),
	(4, 7, 1, 2, 0.00, NULL, 1000.00, 2000.00);
/*!40000 ALTER TABLE `ugovor_soba` ENABLE KEYS */;

DROP TABLE IF EXISTS `uplate`;
CREATE TABLE IF NOT EXISTS `uplate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ugovor_id` int(10) unsigned NOT NULL,
  `datum` datetime NOT NULL,
  `iznos` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `svrha_placanja` enum('Troškovi proslave','Avans','Muzika','Fotograf','Torta','Dekoracija','Kokteli','Slatki sto','Voćni sto','Trubači','Animator','Posebni zahtevi','Ostalo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Muzika',
  `nacin_placanja` enum('gotovina','kartica','ček','faktura') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gotovina',
  `opis` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uplatilac` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `korisnik_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_uplate_ugovori` (`ugovor_id`),
  KEY `FK_uplate_korisnici` (`korisnik_id`),
  CONSTRAINT `FK_uplate_korisnici` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`),
  CONSTRAINT `FK_uplate_ugovori` FOREIGN KEY (`ugovor_id`) REFERENCES `ugovori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `uplate`;
/*!40000 ALTER TABLE `uplate` DISABLE KEYS */;
/*!40000 ALTER TABLE `uplate` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

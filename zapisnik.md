# FRAMEWORK

- Dokumentovati sve klase i metode (napraviti dokumentaciju na nekom sajtu).
- <del> Provaliti kako da se postavi .gitignore da ignorise sadrzaj ses foldera. </del>
- Osmisliti kako da se vraca nazad na stranu sa koje se doslo (dugme nazad ukloniti, jer vraca na dodavanje, izmenu) i/ili
dodati onoliko dagmadi za vracanje sa koliko mesta moze da se dodje na stranu.




## app

### Classes
- Srediti Logger da loguje sta je izmenjeno
- Srediti da Config u index.php preuzima i cfg.php i premestiti sva podesavanja u cfg.php
- Procesljati Mailer (koje su sve opcije PhpMailer-a)
- App\Classes\Nivo ??? (object)[] StdClass
- Validator dodati jos neke provere (vece od ...)

### Controllers
- Proveriti sta sve jos moze da se doda u Controller.php pa da se koristi u drugim kontrolerima

### Models
- Kada se sredi Logger.php ukloniti __toString() iz modela

### Views
- organizovati sve poglede po folderima

## pub

### css
- srediti stilove narocito print.css (minifikovati sta nije)

### js
- proveriti ajax u app.js, mislim da je laksi za koriscenje (csrf)


# TODO

- Filter po salama
	- view: kalendar_po_salama
- Pozadina događaja u beneton bojama
	- FullCalendar
- Tekstualni prikaz dostupnosti
	- FullCalendar
- Brzi skok na mesec, godinu padajuća sa padajuće liste
	- view: na svakom kalendaru combo mesec, godina
- Obaveštenja korisnika o isteku rezervacije
	- opcije: trajanje_rezervacije
- Obaveštenje personalizovana (vlasnik izmena cena i otkaz termina)
	- opcije: email_obavestenja
		- db: s_vrste_email_obavestenja (korisnik->nivo - kom nivou su dostupna obavestenja neke vrste)
- Šifarnik uplatilaca
	- db: s_tip_uplate (fotograf, kapara, torta ...)
- Izveštaj po dodatnim uslugama
	- proveriti sta su stvarno uplate za dodatne usluge (fotograf, kapara, torta ...)
- Prikaz termina po statusu izveštaja


# KONCEPTI

1. Posebno obratiti paznju na vlasnika i njegove potrebe za podacima

2. Razmisliti o ulogama (nivoima) i dodati dashboard za administratora

3. Izbeci modale za izmenu i dodavanje. (veliki posao - automatizovati)

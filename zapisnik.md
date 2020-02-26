# FRAMEWORK

- Dokumentovati sve klase i metode (napraviti dokumentaciju na nekom sajtu).

!!!

	Zastita od neplacanja:
	- db: opcije->datum_isteka
	- kod prijavljivanja korisnika provera da li je isteko datum, ako je isteko datum pusta samo super admina
	- super admin moze da unese novi datum

	Treba napraviti listu termina (da ne bude samo kalendar).
	Proveriti sva brisanja (negde, mislim da je meni, se ne proverava da li je za nesto vezano)
!!!

Prosecna starost je nekako vise za napomene, ja izbriso

Trenutno zakljucavanje termina po pravilu ako su cekirane sve dodatne usluge nema smisla ako se ne doda i da su uneti meniji jer bez obzira na dodatne usluge ako nema menija ne moze da bude zakljucano.

Automatski bih zakljucavao smo na osnovu toga da li postoji neka uplata.

Kod uplata mora da postoje sledeca polja (ko placa, sta placa, kad placa, koliko placa)

Termin zakazan za 29.02.2020, rok isteka 7 dana, kad se udje u pregled termina pise da je ponuda istekla 03.03.2020 (trenutno je 25.02.2020).

DOPUNA UGOVORA
- treba u kontroleru proveriti da li ima vise menija nego zvanica
- da li da se na pogledu izracunava ukupan iznos za sve manije ili da se u kontroleru automatski ovaj iznos upise u ugovor

# TODO

- Dugme nazad ukloniti (vraca na dodavanje, izmenu) i dodati onoliko dagmadi za vracanje sa koliko mesta moze da se dodje na stranu.
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
	Izbor sa selecta (filter) na kalendaru


# KONCEPTI

1. Posebno obratiti paznju na vlasnika i njegove potrebe za podacima
2. Razmisliti o ulogama (nivoima) i dodati dashboard za administratora
3. Izbeci modale za izmenu i dodavanje. (veliki posao - automatizovati)
4. Na pocetnoj strani za prijavljene korisnike (zakazivace) dodati termine za danas, isticanje ponuda za neki dan, podsetnike ...
5. Na pocetnoj strani za goste postaviti formu upita za slobodan termin (honeypot)


# Hotel Kragujevac

1. Seminari: email pa licno
2. Broj osoba, sala, datum, vreme
3. Opcija ponude 7 dana (opcije) na kraju proslave aneks ugovora sa tacnim brojem (+- 5%)
4. Meni i dodatne usluge, pice, pecenje (nase, njino)
	- db: stavke_menija (id, naziv, cena, kategorija-predjelo, corba, glavno jelo ... enum ili s_kategorije_stavki_menija) ovo da bude kompletna ponuda da se ugura torta vocni i slatki sto, posebni zahtevi ...
5. Popust na ugovoru (iznos ili %)
6. Skup svatova (jbg nisam pazio na casu da li je poseban ugovor ili isti?)
7. Zakup prostora (ovde je nesto zbrzano pa nemam pojma sta sam pisao)
8. Muzika (koji bend) SOKOJ
9. Fotograf (kako resiti ove likove koji imaju ugovor sa hotelom)
10. Cvece, torta, dekoracija ...
11. Avans (kapara) napraviti kategorije placanja (avans, fotograf, proslavljac ...)
12. Kopija LK ??? (ovo nikako na web)
13. posebni zahtevi za klopu, decji meni (napraviti multi menije za ugovor)
	- db: ugovor_meni (id, ugovor_id, meni_id, komada)
14. Grupne proslave (nova godina, 8. mart ...) ugovori o placanju na rate, fkture ???
15. Seminari i sl. - rok ponude (cesto ce da varira pa treba turiti jedno polje ili u termin ili u ugovor)
16. Sa kim sve hotel ima ugovor (foto, torta, dekor ...) ovo da bude tabela u bazi, ali gde ce se vezati nemam pojma
17. Trubaci - gde ih turiti
18. Dodati polje sa stvarnim brojem gostiju (ne menjati unet broj)
19. Sheme rasporeda stolova
20. Sala - shema rasporeda - na shemi svaki sto ima broj i vezan je broj stolica za taj sto
21. Srediti izvestaj za osoblje
22. Torta u koje vreme - dodati polje uz tortu
23. Izvestaj za vlasnika po zaposlenima (ko je koliko zakazao ...)
24. Email ponude za seminare, a ja bih turio i za sve ostalo
25. Podsetnici
	- db: podsetnici (id, ugovor_id/termin_id, tekst, datum_vreme)
26. Sobe
	- db: tipovi_soba (id, naziv, cena) - jednokrevetna, dvokrevetna
	- db: ugovor_soba (id, ugovor_id, tip_sobe_id, komada)
27. Recepcija ???
28. Sve cene vodoti duplo u eurima i dinarima
29. Najave dnevne, nedeljne, mesecne (kao izvestaj za osoblje)

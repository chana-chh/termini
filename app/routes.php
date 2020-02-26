<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\UserLevelMiddleware;

$app->group('', function () {
    $this->get('/prijava', '\App\Controllers\AuthController:getPrijava')->setName('prijava');
    $this->post('/prijava', '\App\Controllers\AuthController:postPrijava');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/', '\App\Controllers\HomeController:getHome')->setName('pocetna');
    $this->get('/kalendar[/{datum}]', '\App\Controllers\HomeController:getKalendar')->setName('kalendar');
    $this->get('/kalendar/filter/pretraga', '\App\Controllers\HomeController:getKalendarPretraga')->setName('kalendar.pretraga');
    $this->post('/kalendar/filter/pretraga', '\App\Controllers\HomeController:postKalendarPretraga');

    $this->get('/odjava', '\App\Controllers\AuthController:getOdjava')->setName('odjava');
    // Promena lozinke
    $this->get('/promena', '\App\Controllers\AuthController:getPromena')->setName('promena');
    $this->post('/promena', '\App\Controllers\AuthController:postPromena')->setName('promena');
})->add(new AuthMiddleware($container));

// SUPER ADMIN
$app->group('', function () {
})->add(new UserLevelMiddleware($container, []));

// ADMIN
$app->group('', function () {
    // Korisnici
    $this->get('/admin/korisnik-lista', '\App\Controllers\KorisnikController:getKorisnikLista')->setName('admin.korisnik.lista');
    $this->post('/admin/korisnik-brisanje', '\App\Controllers\KorisnikController:postKorisnikBrisanje')->setName('admin.korisnik.brisanje');
    $this->post('/admin/korisnik-dodavanje', '\App\Controllers\KorisnikController:postKorisnikDodavanje')->setName('admin.korisnik.dodavanje');
    $this->get('/admin/korisnik-izmena/{id}', '\App\Controllers\KorisnikController:getKorisnikIzmena')->setName('admin.korisnik.izmena.get');
    $this->post('/admin/korisnik-izmena', '\App\Controllers\KorisnikController:postKorisnikIzmena')->setName('admin.korisnik.izmena.post');
    //Sale
    $this->get('/admin/sale', '\App\Controllers\SalaController:getSale')->setName('sale');
    $this->post('/admin/sale/dodavanje', '\App\Controllers\SalaController:postSalaDodavanje')->setName('sale.dodavanje');
    $this->post('/admin/sale/brisanje', '\App\Controllers\SalaController:postSalaBrisanje')->setName('sale.brisanje');
    $this->post('/admin/sale/detalj', '\App\Controllers\SalaController:postSalaDetalj')->setName('sale.detalj');
    $this->post('/admin/sale/izmena', '\App\Controllers\SalaController:postSalaIzmena')->setName('sale.izmena');
    //Komitenti
    $this->get('/admin/komitent', '\App\Controllers\KomitentController:getKomitenti')->setName('komitenti');
    $this->get('/admin/komitent/pretraga', '\App\Controllers\KomitentController:getKomitentiPretraga')->setName('komitenti.pretraga');
    $this->post('/admin/komitent/pretraga', '\App\Controllers\KomitentController:postKomitentiPretraga');
    $this->post('/admin/komitent/dodavanje', '\App\Controllers\KomitentController:postKomitentiDodavanje')->setName('komitenti.dodavanje');
    $this->post('/admin/komitent/brisanje', '\App\Controllers\KomitentController:postKomitentiBrisanje')->setName('komitenti.brisanje');
    $this->post('/admin/komitent/detalj', '\App\Controllers\KomitentController:postKomitentiDetalj')->setName('komitenti.detalj');
    $this->post('/admin/komitent/izmena', '\App\Controllers\KomitentController:postKomitentiIzmena')->setName('komitenti.izmena');
    //Sobe
    $this->get('/admin/sobe', '\App\Controllers\SobaController:getSobe')->setName('sobe');
    $this->get('/admin/sobe/pretraga', '\App\Controllers\SobaController:getSobePretraga')->setName('sobe.pretraga');
    $this->post('/admin/sobe/pretraga', '\App\Controllers\SobaController:postSobePretraga');
    $this->post('/admin/sobe/dodavanje', '\App\Controllers\SobaController:postSobeDodavanje')->setName('sobe.dodavanje');
    $this->post('/admin/sobe/brisanje', '\App\Controllers\SobaController:postSobeBrisanje')->setName('sobe.brisanje');
    $this->post('/admin/sobe/detalj', '\App\Controllers\SobaController:postSobeDetalj')->setName('sobe.detalj');
    $this->post('/admin/sobe/izmena', '\App\Controllers\SobaController:postSobeIzmena')->setName('sobe.izmena');
    //Tipovi događaja
    $this->get('/admin/tip', '\App\Controllers\TipDogadjajaController:getTipove')->setName('tip_dogadjaja');
    $this->post('/admin/tip/dodavanje', '\App\Controllers\TipDogadjajaController:postTipDodavanje')->setName('tip_dogadjaja.dodavanje');
    $this->post('/admin/tip/brisanje', '\App\Controllers\TipDogadjajaController:postTipBrisanje')->setName('tip_dogadjaja.brisanje');
    $this->post('/admin/tip/detalj', '\App\Controllers\TipDogadjajaController:postTipDetalj')->setName('tip_dogadjaja.detalj');
    $this->post('/admin/tip/izmena', '\App\Controllers\TipDogadjajaController:postTipIzmena')->setName('tip_dogadjaja.izmena');
    //Logovi
    $this->get('/admin/logovi', '\App\Controllers\LogController:getLog')->setName('logovi');
    $this->get('/admin/logovi/pretraga', '\App\Controllers\LogController:getLogPretraga')->setName('logovi.pretraga');
    $this->post('/admin/logovi/pretraga', '\App\Controllers\LogController:postLogPretraga');
    // Stavke menija
    $this->get('/admin/stavka-menija', '\App\Controllers\StavkaMenijaController:getStavkaMenija')->setName('stavke_menija');
    $this->get('/admin/stavka-menija/pretraga', '\App\Controllers\StavkaMenijaController:getStavkaMenijaPretraga')->setName('stavke_menija.pretraga');
    $this->post('/admin/stavka-menija/pretraga', '\App\Controllers\StavkaMenijaController:postStavkaMenijaPretraga');
    $this->post('/admin/stavka-menija/dodavanje', '\App\Controllers\StavkaMenijaController:postStavkaMenijaDodavanje')->setName('stavke_menija.dodavanje');
    $this->post('/admin/stavka-menija/brisanje', '\App\Controllers\StavkaMenijaController:postStavkaMenijaBrisanje')->setName('stavke_menija.brisanje');
    $this->get('/admin/stavka-menija/izmena/{id}', '\App\Controllers\StavkaMenijaController:getStavkaMenijaIzmena')->setName('stavke_menija.izmena.get');
    $this->post('/admin/stavka-menija/izmena', '\App\Controllers\StavkaMenijaController:postStavkaMenijaIzmena')->setName('stavke_menija.izmena.post');

})->add(new UserLevelMiddleware($container, [0]));

// VLASNIK
$app->group('', function () {
    $this->get('/vlasnik/kalendar[/{datum}]', '\App\Controllers\VlasnikController:getKalendarVlasnik')->setName('vlasnik.kalendar');
    $this->get('/vlasnik/termin[/{id}]', '\App\Controllers\VlasnikController:getTerminVlasnik')->setName('vlasnik.termin');
    $this->get('/vlasnik/ugovori/detalj/{id}', '\App\Controllers\VlasnikController:getUgovorVlasnik')->setName('vlasnik.ugovor.detalj');
    $this->get('/vlasnik/uplate/{id}', '\App\Controllers\VlasnikController:getUplateVlasnik')->setName('vlasnik.uplate');
    $this->get('/vlasnik/ugovori', '\App\Controllers\VlasnikController:getUgovorListaVlasnik')->setName('vlasnik.ugovori');
    $this->get('/vlasnik/ugovori/pretraga', '\App\Controllers\VlasnikController:getUgovorPretragaVlasnik')->setName('vlasnik.ugovori.pretraga');
    $this->post('/vlasnik/ugovori/pretraga', '\App\Controllers\VlasnikController:postUgovorPretragaVlasnik');
    // izvestaji
    $this->get('/izvestaji/po-salama', '\App\Controllers\IzvestajiController:getPoSalama')->setName('izvestaji.Komitenti');
    $this->post('/izvestaji/po-salama', '\App\Controllers\IzvestajiController:postPoSalama')->setName('izvestaji.Komitenti.post');
    $this->get('/izvestaji/po-salama-lista', '\App\Controllers\IzvestajiController:getPoSalamaLista')->setName('izvestaji.sale.lista');
    $this->get('/izvestaji/po-tipovima', '\App\Controllers\IzvestajiController:getPoTipovima')->setName('izvestaji.tipovi');
    $this->post('/izvestaji/po-tipovima', '\App\Controllers\IzvestajiController:postPoTipovima')->setName('izvestaji.tipovi.post');
    $this->get('/izvestaji/po-tipovima-lista', '\App\Controllers\IzvestajiController:getPoTipovimaLista')->setName('izvestaji.tipovi.lista');
    $this->get('/izvestaji/po-vtsti', '\App\Controllers\IzvestajiController:getPoVrstiPlacanja')->setName('izvestaji.vrste');
    $this->post('/izvestaji/po-vtsti', '\App\Controllers\IzvestajiController:postPoVrstiPlacanja')->setName('izvestaji.vrste.post');
    $this->get('/izvestaji/po-vtsti-lista', '\App\Controllers\IzvestajiController:getPoVrstiPlacanjaLista')->setName('izvestaji.vrste.lista');
})->add(new UserLevelMiddleware($container, [100]));

// ZAKAZIVACI
$app->group('', function () {
    // Ugovori
    $this->get('/ugovori', '\App\Controllers\UgovorController:getUgovor')->setName('ugovori');
    $this->get('/ugovori/pretraga', '\App\Controllers\UgovorController:getUgovorPretraga')->setName('ugovori.pretraga');
    $this->post('/ugovori/pretraga', '\App\Controllers\UgovorController:postUgovorPretraga');
    $this->get('/termin/ugovori/dodavanje/{termin_id}', '\App\Controllers\UgovorController:getUgovorDodavanje')->setName('termin.dodavanje.ugovor');
    $this->post('/termin/ugovori/dodavanje', '\App\Controllers\UgovorController:postUgovorDodavanje')->setName('termin.dodavanje.ugovor.post');
    $this->get('/termin/ugovori/izmena/{id}', '\App\Controllers\UgovorController:getUgovorIzmena')->setName('termin.ugovor.izmena.get');
    $this->post('/termin/ugovori/izmena', '\App\Controllers\UgovorController:postUgovorIzmena')->setName('termin.ugovor.izmena.post');
    $this->post('/termin/ugovori/brisanje', '\App\Controllers\UgovorController:postUgovorBrisanje')->setName('termin.ugovor.brisanje');
    $this->get('/termin/ugovori/detalj/{id}', '\App\Controllers\UgovorController:getUgovorDetalj')->setName('termin.ugovor.detalj.get');
    $this->get('/termin/ugovori/uplate/{id}', '\App\Controllers\UgovorController:getUgovorUplateDetalj')->setName('ugovor.uplate.lista');
    $this->get('/termin/ugovori/sobe/{id}', '\App\Controllers\UgovorController:getUgovorSobe')->setName('ugovor.sobe.lista');
    $this->post('/termin/ugovori/sobe/dodavanje', '\App\Controllers\UgovorController:postSobaUgovorDodavanje')->setName('ugovor.sobe.dodavanje');
    $this->get('/termin/ugovori/dopuna/{id}', '\App\Controllers\UgovorController:getUgovorDopuna')->setName('ugovor.dopuna.get');
    $this->post('/termin/ugovori/dopuna/meni', '\App\Controllers\UgovorController:postUgovorDopunaMeni')->setName('ugovor.dopuna.meni');
    $this->post('/termin/ugovori/dopuna/soba', '\App\Controllers\UgovorController:postUgovorDopunaSoba')->setName('ugovor.dopuna.soba');
    // Stampa ugovora
    $this->get('/ugovori/stampa/fizicka/single/{id}', '\App\Controllers\StampaController:getUgovorStampaFizickaSingle')->setName('ugovori.stampa.fizicka.single');
    $this->get('/ugovori/stampa/pravna/single/{id}', '\App\Controllers\StampaController:getUgovorStampaPravnaSingle')->setName('ugovori.stampa.pravna.single');
    // Uplate
    $this->post('/ugovori/uplata/detalj', '\App\Controllers\UplataController:postUplataIzmenaAjax')->setName('ugovor.uplata.detalj');
    $this->post('/ugovori/izmena/uplata', '\App\Controllers\UplataController:postUplataIzmena')->setName('ugovor.izmena.uplata');
    $this->post('/ugovori/brisanje/uplata', '\App\Controllers\UplataController:postUplataBrisanje')->setName('ugovor.brisanje.uplata');
    $this->post('/termin/ugovori/uplata', '\App\Controllers\UplataController:postUplataDodavanje')->setName('termin.ugovor.uplata');
    // Dokumenti
    $this->post('/termin/dokument/dodavanje', '\App\Controllers\DokumentController:postDokumentDodavanje')->setName('dokument.dodavanje');
    $this->post('/termin/dokument/brisanje', '\App\Controllers\DokumentController:postDokumentiBrisanje')->setName('dokument.brisanje');
    $this->post('/termin/dokument/detalj', '\App\Controllers\DokumentController:postDokumentDetalj')->setName('dokument.detalj');
    $this->post('/termin/dokument/izmena', '\App\Controllers\DokumentController:postDokumentIzmena')->setName('dokument.izmena');
    // Termini
    $this->get('/termin/pregled[/{datum}]', '\App\Controllers\TerminController:getTerminPregled')->setName('termin.pregled.get');
    $this->get('/termin/detalj[/{id}]', '\App\Controllers\TerminController:getTerminDetalj')->setName('termin.detalj.get');
    $this->get('/termin/dodavanje[/{datum}]', '\App\Controllers\TerminController:getTerminDodavanje')->setName('termin.dodavanje.get');
    $this->post('/termin/dodavanje', '\App\Controllers\TerminController:postTerminDodavanje')->setName('termin.dodavanje.post');
    $this->get('/termin/izmena/{id}', '\App\Controllers\TerminController:getTerminIzmena')->setName('termin.izmena.get');
    $this->post('/termin/izmena', '\App\Controllers\TerminController:postTerminIzmena')->setName('termin.izmena.post');
    $this->post('/termin/brisanje', '\App\Controllers\TerminController:postTerminBrisanje')->setName('termin.brisanje.post');
    $this->post('/termin/zakljucivanje', '\App\Controllers\TerminController:postTerminZakljucivanje')->setName('termin.zakljucivanje.post');
    //Meniji
    $this->get('/admin/meni', '\App\Controllers\MeniController:getMeni')->setName('meni');
    $this->get('/admin/meni/dodavanje', '\App\Controllers\MeniController:getMeniDodavanje')->setName('meni.dodavanje.get');
    $this->post('/admin/meni/dodavanje', '\App\Controllers\MeniController:postMeniDodavanje')->setName('meni.dodavanje.post');
    $this->get('/admin/meni/izmena/{id}', '\App\Controllers\MeniController:getMeniIzmena')->setName('meni.izmena.get');
    $this->post('/admin/meni/izmena', '\App\Controllers\MeniController:postMeniIzmena')->setName('meni.izmena.post');
    $this->post('/admin/meni/brisanje', '\App\Controllers\MeniController:postMeniBrisanje')->setName('meni.brisanje');
    $this->get('/admin/meni/detalj/{id}', '\App\Controllers\MeniController:getMeniDetalj')->setName('meni.detalj');
    $this->get('/admin/meni/pretraga', '\App\Controllers\MeniController:getMeniPretraga')->setName('meni.pretraga');
    $this->post('/admin/meni/pretraga', '\App\Controllers\MeniController:postMeniPretraga');
    $this->post('/admin/meni/ajax', '\App\Controllers\MeniController:ajaxMeni')->setName('meni.dodavanje.ajax');
})->add(new UserLevelMiddleware($container, [0,200]));

// OSOBLJE
$app->group('', function () {
    $this->get('/osoblje/kalendar[/{datum}]', '\App\Controllers\OsobljeController:getKalendarOsoblje')->setName('osoblje.kalendar');
    $this->get('/osoblje/termin[/{id}]', '\App\Controllers\OsobljeController:getTerminOsoblje')->setName('osoblje.termin');
})->add(new UserLevelMiddleware($container, [0,300]));

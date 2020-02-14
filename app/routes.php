<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\UserLevelMiddleware;

$app->get('/', '\App\Controllers\HomeController:getHome')->setName('pocetna');
$app->get('/kalendar[/{datum}]', '\App\Controllers\HomeController:getKalendar')->setName('kalendar');

$app->group('', function () {
    $this->get('/prijava', '\App\Controllers\AuthController:getPrijava')->setName('prijava');
    $this->post('/prijava', '\App\Controllers\AuthController:postPrijava');
})->add(new GuestMiddleware($container));

$app->group('', function () {
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
    $this->post('/admin/korisnik-izmena', '\App\Controllers\KorisnikController:postKorisnikIzmena')->setName('admin.korisnik.izmena');
    $this->post('/admin/korisnik-detalj', '\App\Controllers\KorisnikController:postKorisnikDetalj')->setName('admin.korisnik.detalj');
    //Sale
    $this->get('/admin/sale', '\App\Controllers\SalaController:getSale')->setName('sale');
    $this->post('/admin/sale/dodavanje', '\App\Controllers\SalaController:postSalaDodavanje')->setName('sale.dodavanje');
    $this->post('/admin/sale/brisanje', '\App\Controllers\SalaController:postSalaBrisanje')->setName('sale.brisanje');
    $this->post('/admin/sale/detalj', '\App\Controllers\SalaController:postSalaDetalj')->setName('sale.detalj');
    $this->post('/admin/sale/izmena', '\App\Controllers\SalaController:postSalaIzmena')->setName('sale.izmena');
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
    $this->get('/izvestaji/po-salama', '\App\Controllers\IzvestajiController:getPoSalama')->setName('izvestaji.sale');
    $this->post('/izvestaji/po-salama', '\App\Controllers\IzvestajiController:postPoSalama')->setName('izvestaji.sale.post');
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

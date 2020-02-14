<?php

namespace App\Models;

use App\Classes\Model;

class Ugovor extends Model
{
    protected $table = 'ugovori';

    public function termin()
    {
        return $this->belongsTo('App\Models\Termin', 'termin_id');
    }

    public function punoIme()
    {
        return "{$this->prezime} {$this->ime}";
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function meni()
    {
        return $this->belongsTo('App\Models\Meni', 'meni_id');
    }

    public function kapara()
    {
        $sql = "SELECT iznos FROM uplate WHERE ugovor_id = {$this->id} AND opis = 'kapara';";
        $kapara = $this->fetch($sql);
        $iznos = empty($kapara) ? 0 : $kapara[0]->iznos;
        return (float) $iznos;
    }

    public function uplate()
    {
        return $this->hasMany('App\Models\Uplata', 'ugovor_id');
    }

    public function uplateSuma()
    {
        $sql = "SELECT SUM(iznos) AS uplateSuma FROM uplate WHERE ugovor_id = {$this->id};";
        return (float) $this->fetch($sql)[0]->uplateSuma;
    }

    public function uplateDug()
    {
        return $this->iznos - $this->uplateSuma();
    }

    public function dokumenti()
    {
        return $this->hasMany('App\Models\Dokument', 'ugovor_id');
    }

    public function zakljucavanje()
    {
        $zakljucati = false;

        if (!empty($this->uplate())) {
            $zakljucati = true;
        }

        if (
            $this->muzika_chk === 1 &&
            $this->fotograf_chk === 1 &&
            $this->torta_chk === 1 &&
            $this->dekoracija_chk === 1 &&
            $this->kokteli_chk === 1 &&
            $this->slatki_sto_chk === 1 &&
            $this->vocni_sto_chk === 1
        ) {
            $zakljucati = true;
        }

        return $zakljucati;
    }

    public function multiUgovor()
    {
        return $this->termin()->multiUgovori();
    }

    public function cenaUsluga()
    {
        $cena = 0.00;
        $cena =
            $this->muzika_iznos +
            $this->fotograf_iznos +
            $this->torta_iznos +
            $this->dekoracija_iznos +
            $this->kokteli_iznos +
            $this->slatki_sto_iznos +
            $this->vocni_sto_iznos +
            $this->posebni_zahtevi_iznos;
            
        return $cena;
    }

    public function __toString()
    {
        return 'Podaci iz modela: termin_id:' . $this->termin_id .
                ', broj_ugovora:' . $this->broj_ugovora .
                ', datum:' . $this->datum .
                ', meni_id:' . $this->meni_id .
                ', broj_mesta:' . $this->broj_mesta .
                ', broj_stolova:' . $this->broj_stolova .
                ', broj_mesta_po_stolu:' . $this->broj_mesta_po_stolu .
                ', prezime:' . $this->prezime .
                ', ime:' . $this->ime .
                ', telefon:' . $this->telefon .
                ', email:' . $this->email .
                ', prosek_godina:' . $this->prosek_godina .
                ', muzika_chk:' . $this->muzika_chk .
                ', muzika_opis:' . $this->muzika_opis .
                ', muzika_ugovor:' . $this->muzika_ugovor .
                ', iznos:' . $this->iznos .
                ', muzika_iznos:' . $this->muzika_iznos .
                ', fotograf_chk:' . $this->fotograf_chk .
                ', fotograf_opis:' . $this->fotograf_opis .
                ', fotograf_iznos:' . $this->fotograf_iznos .
                ', torta_chk:' . $this->torta_chk .
                ', torta_opis:' . $this->torta_opis .
                ', torta_iznos:' . $this->torta_iznos .
                ', dekoracija_chk:' . $this->dekoracija_chk .
                ', dekoracija_opis:' . $this->dekoracija_opis .
                ', dekoracija_iznos:' . $this->dekoracija_iznos .
                ', kokteli_chk:' . $this->kokteli_chk .
                ', kokteli_opis:' . $this->kokteli_opis .
                ', kokteli_iznos:' . $this->kokteli_iznos .
                ', slatki_sto_chk:' . $this->slatki_sto_chk .
                ', slatki_sto_opis:' . $this->slatki_sto_opis .
                ', slatki_sto_iznos:' . $this->slatki_sto_iznos .
                ', vocni_sto_chk:' . $this->vocni_sto_chk .
                ', vocni_sto_opis:' . $this->vocni_sto_opis .
                ', vocni_sto_iznos:' . $this->vocni_sto_iznos .
                ', posebni_zahtevi:' . $this->posebni_zahtevi .
                ', posebni_zahtevi_iznos:' . $this->posebni_zahtevi_iznos .
                ', napomena:' . $this->napomena;
    }
}

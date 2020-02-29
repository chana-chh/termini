<?php

namespace App\Models;

use App\Classes\Model;
use App\Models\Komitent;

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

    public function sobe()
    {
        return $this->belongsToMany('App\Models\Soba', 'ugovor_soba', 'ugovor_id', 'soba_id');
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
        return $this->aneksUkupanIznos() - $this->uplateSuma();
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

        return $zakljucati;
    }

    public function multiUgovor()
    {
        return $this->termin()->multiUgovori();
    }

    public function sobeSuma()
    {
        $rezultat = 0.00;
        $sql = "SELECT ugovor_soba.*,sobe.cena,sum(ugovor_soba.komada * (sobe.cena - ugovor_soba.popust)) AS rezultat
                FROM ugovor_soba JOIN sobe
                on ugovor_soba.soba_id = sobe.id
                WHERE ugovor_soba.ugovor_id = {$this->id}
                GROUP BY ugovor_soba.ugovor_id;";
        $promenjiva = $this->fetch($sql);
        if ($promenjiva != null) {
            return (float) $this->fetch($sql)[0]->rezultat;
        }
        return 0;
    }

    public function sobaUgovor()
    {
        return $this->hasMany('App\Models\SobaUgovor', 'ugovor_id');
    }

    public function ukupanIznosSoba()
    {
        $iznos = 0;
        foreach ($this->sobaUgovor() as $soba) {
            $iznos += $soba->iznos;
        }
        return $iznos;
    }

    public function meniUgovor()
    {
        return $this->hasMany('App\Models\MeniUgovor', 'ugovor_id');
    }

    public function ukupanBrojMenija()
    {
        $broj = 0;
        foreach ($this->meniUgovor() as $meni) {
            $broj += $meni->komada;
        }
        return $broj;
    }

    public function ukupanIznosMenija()
    {
        $iznos = 0;
        foreach ($this->meniUgovor() as $meni) {
            $iznos += $meni->iznos;
        }
        return $iznos;
    }

    public function podsetnici($korisnik_id = null)
    {
        $sql = "SELECT * FROM podsetnici WHERE ugovor_id = {$this->id} ORDER BY datum DESC";
        if ($korisnik_id !== null) {
            $sql = "SELECT * FROM podsetnici WHERE ugovor_id = {$this->id} AND korisnik_id = {$korisnik_id} ORDER BY datum DESC";
        }
        return $this->fetch($sql);
    }

    public function ukupanIznosDodatno()
    {
        $iznos = 0;
        $iznos += (float) $this->muzika_iznos;
        $iznos += (float) $this->fotograf_iznos;
        $iznos += (float) $this->torta_iznos;
        $iznos += (float) $this->dekoracija_iznos;
        $iznos += (float) $this->kokteli_iznos;
        $iznos += (float) $this->slatki_sto_iznos;
        $iznos += (float) $this->vocni_sto_iznos;
        $iznos += (float) $this->animator_iznos;
        $iznos += (float) $this->trubaci_iznos;
        $iznos += (float) $this->posebni_zahtevi_iznos;
        return $iznos;
    }

    public function ukupanIznos()
    {
        return (float) ($this->iznos_meni + $this->iznos_sobe + $this->iznos_dodatno);
    }

    public function aneksUkupanIznos()
    {
        return (float) ($this->aneks_iznos_meni + $this->aneks_iznos_sobe + $this->aneks_iznos_dodatno);
    }

    public function komitent($naziv)
    {
        $kom = new Komitent();
        return $kom->find($this->$naziv);
    }
}

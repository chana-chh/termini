<?php

namespace App\Models;

use App\Classes\Model;

class Termin extends Model
{
    protected $table = 'termini';

    public function tip()
    {
        return $this->belongsTo('App\Models\TipDogadjaja', 'tip_dogadjaja_id');
    }

    public function zakljucen()
    {
        return $this->zauzet == 1 ? true : false;
    }

    public function multiUgovori()
    {
        return $this->tip()->multi_ugovori == 1 ? true : false;
    }

    public function zakljucavanje()
    {
        if ($this->multiUgovori()) {
            return true;
        }

        if (empty($this->ugovori())) {
            return false;
        }

        if ($this->ugovori()[0]->zakljucavanje()) {
            return true;
        }

        return false;
    }

    public function sala()
    {
        return $this->belongsTo('App\Models\Sala', 'sala_id');
    }

    public function ugovori()
    {
        return $this->hasMany('App\Models\Ugovor', 'termin_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function popunjenaMesta()
    {
        $sql = "SELECT SUM(broj_mesta) AS pm FROM ugovori WHERE termin_id = {$this->id};";
        return (int) $this->fetch($sql)[0]->pm;
    }

    public function popunjeniStolovi()
    {
        $sql = "SELECT SUM(broj_stolova) AS ps FROM ugovori WHERE termin_id = {$this->id};";
        return (int) $this->fetch($sql)[0]->ps;
    }

    public function slobodnaMesta()
    {
        return (int) ($this->sala()->max_kapacitet_mesta - $this->popunjenaMesta());
    }

    public function slobodniStolovi()
    {
        return (int) ($this->sala()->max_kapacitet_stolova - $this->popunjeniStolovi());
    }
    
    //Privremeno izmenjeno dok ne odlucimo kako ce se racunati !!!
    public function cenaTermina()
    {
        $dodatne = 0;
        $meni_sobe = 0;
        $ukupno = 0;
        //meni i sobe
        $sql = "SELECT SUM(iznos_meni + iznos_sobe) AS cena FROM ugovori WHERE termin_id = {$this->id};";
        $meni_sobe = $this->fetch($sql)[0]->cena;
        //dodatne usluge
        foreach ($this->ugovori() as $ugovor) {
            $dodatne =+ $ugovor->ukupanIznosDodatno();
        }
        $ukupno = $dodatne + $meni_sobe;
        return (int) $ukupno;
    }

    public function status()
    {
        if ($this->zauzet == 0 && empty($this->ugovori())) {
            return 2;
        }

        return $this->zauzet;
    }

    public function statusIkonica()
    {
        if ($this->status() == 0) {
            return 'fas fa-calendar-plus text-danger';
        }

        if ($this->status() == 1) {
            return 'fas fa-calendar-check text-success';
        }

        if ($this->status() == 2) {
            return 'fas fa-question-circle text-primary';
        }

        return null;
    }

    public function statusBoja()
    {
        if ($this->status() == 0) {
            return '#F8D7DA';
        }

        if ($this->status() == 1) {
            return '#D4EDDA';
        }

        if ($this->status() == 2) {
            return '#CCE5FF';
        }

        return null;
    }
}

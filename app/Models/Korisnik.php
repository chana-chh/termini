<?php

namespace App\Models;

use App\Classes\Model;

class Korisnik extends Model
{
    protected $table = 'korisnici';

    public function findByUsername(string $username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE korisnicko_ime = :kime LIMIT 1;";
        $params = [':kime' => $username];
        return $this->fetch($sql, $params)[0];
    }

    public function logovi()
    {
        return $this->hasMany('App\Models\Log', 'korisnik_id');
    }

    public function dokumenti()
    {
        return $this->hasMany('App\Models\Dokument', 'korisnik_id');
    }

    public function sale()
    {
        return $this->hasMany('App\Models\Sala', 'korisnik_id');
    }

    public function meniji()
    {
        return $this->hasMany('App\Models\Meni', 'korisnik_id');
    }

    public function tipoviDogadjaja()
    {
        return $this->hasMany('App\Models\TipDogadjaja', 'korisnik_id');
    }

    public function termini()
    {
        return $this->hasMany('App\Models\Termin', 'korisnik_id');
    }

    public function ugovori()
    {
        return $this->hasMany('App\Models\Ugovor', 'korisnik_id');
    }

    public function uplate()
    {
        return $this->hasMany('App\Models\Uplata', 'korisnik_id');
    }

    public function __toString()
    {
        return 'Podaci iz modela: ime:' . $this->ime . ', korisnicko_ime:' . $this->korisnicko_ime. ', nivo:' . $this->nivo;
    }
}

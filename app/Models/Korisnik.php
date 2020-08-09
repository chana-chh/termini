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

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1;";
        $params = [':email' => $email];
        return $this->fetch($sql, $params)[0];
    }

    public function imePrezime()
    {
        return "{$this->ime} {$this->prezime}";
    }

    public function prezimeIme()
    {
        return "{$this->prezime} {$this->ime}";
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

    public function podsetnici()
    {
        return $this->hasMany('App\Models\Podsetnik', 'korisnik_id');
    }
}

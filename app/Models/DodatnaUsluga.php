<?php

namespace App\Models;

use App\Classes\Model;

class DodatnaUsluga extends Model
{
    protected $table = 'dodatne_usluge';

    public function ugovor()
    {
        return $this->belongsTo('App\Models\Ugovor', 'ugovor_id');
    }

    public function komitent()
    {
        return $this->belongsTo('App\Models\Komitent', 'komitent_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

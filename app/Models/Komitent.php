<?php

namespace App\Models;

use App\Classes\Model;

class Komitent extends Model
{
    protected $table = 'komintenti';

    //Relacije
    public function kategorija()
    {
        return $this->belongsTo('App\Models\Kategorija', 'kategorija_id');
    }

    public function dodatneUsluge()
    {
        return $this->hasMany('App\Models\DodatnaUsluga', 'komitent_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

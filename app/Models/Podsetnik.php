<?php

namespace App\Models;

use App\Classes\Model;

class Podsetnik extends Model
{
    protected $table = 'podsetnici';

    public function ugovor()
    {
        return $this->belongsTo('App\Models\Ugovor', 'ugovor_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

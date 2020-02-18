<?php

namespace App\Models;

use App\Classes\Model;

class Sala extends Model
{
    protected $table = 'sale';

    public function termini()
    {
        return $this->hasMany('App\Models\Termin', 'sala_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

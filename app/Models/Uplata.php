<?php

namespace App\Models;

use App\Classes\Model;

class Uplata extends Model
{
    protected $table = 'uplate';

    public function ugovor()
    {
        return $this->belongsTo('App\Models\Ugovor', 'ugovor_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function __toString()
    {
        return 'Podaci iz modela: ugovor_id:' . $this->ugovor_id . 
                ', datum:' . $this->datum .
                ', iznos:' . $this->iznos .
                ', nacin_placanja:' . $this->nacin_placanja .
                ', opis:' . $this->opis;
    }
}

<?php

namespace App\Models;

use App\Classes\Model;

class TipDogadjaja extends Model
{
    protected $table = 's_tip_dogadjaja';

    public function termini()
    {
        return $this->hasMany('App\Models\Termin', 'tip_dogadjaja_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function __toString()
    {
        return 'Podaci iz modela: tip:' . $this->tip . ', multi_ugovori:' . $this->multi_ugovori;
    }
}

<?php

namespace App\Models;

use App\Classes\Model;

class Meni extends Model
{
    protected $table = 's_meniji';

    public function ugovor()
    {
        return $this->hasMany('App\Models\Ugovor', 'meni_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function __toString()
    {
        return 'Podaci iz modela: naziv:' . $this->naziv .
        		', hladno_predjelo:' . $this->hladno_predjelo .
        		', sirevi:' . $this->sirevi .
        		', corba:' . $this->corba .
        		', glavno_jelo:' . $this->glavno_jelo .
        		', meso:' . $this->meso .
        		', hleb:' . $this->hleb .
        		', karta_pica:' . $this->karta_pica .
        		', cena:' . $this->cena .
        		', napomena:' . $this->napomena;
    }
}

<?php

namespace App\Models;

use App\Classes\Model;

class Dokument extends Model
{
    protected $table = 'dokumenti';

    public function ugovor()
    {
        return $this->belongsTo('App\Models\Ugovor', 'ugovor_id');
    }

    public function __toString()
    {
        return 'Podaci iz modela: ugovor_id:' . $this->ugovor_id . ', link:' . $this->link. ', opis:' . $this->opis;
    }
}

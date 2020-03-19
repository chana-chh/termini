<?php

namespace App\Models;

use App\Classes\Model;

class Komitent extends Model
{
    protected $table = 'komintenti';

    public function kategorija()
    {
        return $this->belongsTo('App\Models\Kategorija', 'kategorija_id');
    }

    public function dodatneUsluge()
    {
        return $this->hasMany('App\Models\DodatnaUsluga', 'komitent_id');
    }
}

<?php

namespace App\Models;

use App\Classes\Model;

class Meni extends Model
{
    protected $table = 'meniji';

    public function ugovor()
    {
        return $this->hasMany('App\Models\Ugovor', 'meni_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function stavke()
    {
        $in = "({$this->stavke})";
        $sql = "SELECT * FROM stavke_menija WHERE id IN {$in}";
        $rez = $this->fetch($sql,null, 'App\Models\StavkaMenija');
        return $rez;
    }
}

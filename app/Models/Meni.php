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

    public function stavke($kategorija = '')
    {
        $in = "({$this->stavke})";
        if ($kategorija === '') {
            $sql = "SELECT * FROM stavke_menija WHERE id IN {$in}";
        } else {
            $sql = "SELECT * FROM stavke_menija WHERE id IN {$in} AND kategorija = '{$kategorija}'";
        }
        $rez = $this->fetch($sql, null, 'App\Models\StavkaMenija');
        return $rez;
    }

    public function kategorije()
    {
        $in = "({$this->stavke})";
        $sql = "SELECT kategorija FROM stavke_menija WHERE id IN {$in} GROUP BY kategorija";
        $rez = $this->fetch($sql, null, 'App\Models\StavkaMenija');
        return $rez;
    }

}

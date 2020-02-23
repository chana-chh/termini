<?php

namespace App\Models;

use App\Classes\Model;

class StavkaMenija extends Model
{
    protected $table = 'stavke_menija';

    public function stavkeZaKategoriju($kat)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE kategorija = '{$kat}'";
        return $this->fetch($sql);
    }

    public function stavkePoKategorijama()
    {
        $kategorije = $this->enumOrSetList('kategorija');
        $rez = [];
        foreach ($kategorije as $kat) {
            $rez[$kat] = $this->stavkeZaKategoriju($kategorije);
        }
        return $rez;
    }
}

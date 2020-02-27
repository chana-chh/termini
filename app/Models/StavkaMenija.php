<?php

namespace App\Models;

use App\Classes\Model;

class StavkaMenija extends Model
{
    protected $table = 'stavke_menija';

    public function sveKategorije()
    {
        return $this->enumOrSetList('kategorija');
    }

    public function stavkeZaKategoriju($kat)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE kategorija = '{$kat}'";
        return $this->fetch($sql);
    }
}

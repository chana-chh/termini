<?php

namespace App\Models;

use App\Classes\Model;

class Kategorija extends Model
{
    protected $table = 'kategorije';

    public function komitent()
    {
        return $this->hasMany('App\Models\Komitent', 'kategorija_id');
    }
}

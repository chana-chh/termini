<?php

namespace App\Models;

use App\Classes\Model;

class MeniUgovor extends Model
{
    protected $table = 'ugovor_meni';

    public function meni()
    {
        return $this->belongsTo('App\Models\Meni', 'meni_id');
    }
}

<?php

namespace App\Models;

use App\Classes\Model;

class SobaUgovor extends Model
{
    protected $table = 'ugovor_soba';

    public function soba()
    {
        return $this->belongsTo('App\Models\Soba', 'soba_id');
    }
}

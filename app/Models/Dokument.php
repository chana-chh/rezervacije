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
}

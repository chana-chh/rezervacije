<?php

namespace App\Models;

use App\Classes\Model;
use App\Classes\Db;

class Sala extends Model
{
    protected $table = 'sale';

    public function termini()
    {
        return $this->hasMany('App\Models\Termin', 'sala_id');
    }
}

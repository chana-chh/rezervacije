<?php

namespace App\Models;

use App\Classes\Model;
use App\Classes\Db;

class Termin extends Model
{
    protected $table = 'termini';

    public function tip()
    {
        return $this->belongsTo('App\Models\TipDogadjaja', 'tip_dogadjaja_id');
    }

    public function sala()
    {
        return $this->belongsTo('App\Models\Sala', 'sala_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

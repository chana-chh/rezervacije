<?php

namespace App\Models;

use App\Classes\Model;
use App\Classes\Db;

class TipDogadjaja extends Model
{
    protected $table = 's_tip_dogadjaja';

    public function termini()
    {
        return $this->hasMany('App\Models\Termin', 'tip_dogadjaja_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

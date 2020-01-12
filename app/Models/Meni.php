<?php

namespace App\Models;

use App\Classes\Model;
use App\Classes\Db;

class Meni extends Model
{
    protected $table = 's_meniji';

    public function ugovor()
    {
        return $this->hasMany('App\Models\Ugovor', 'meni_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

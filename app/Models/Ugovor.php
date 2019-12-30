<?php

namespace App\Models;

use App\Classes\Model;

class Ugovor extends Model
{
    protected $table = 'ugovori';

    public function termin()
    {
        return $this->belongsTo('App\Models\Ugovor', 'termin_id');
    }

    public function punoIme()
    {
        return "{$this->prezime} {$this->ime}";
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }
}

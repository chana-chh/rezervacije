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

    public function zakljucen()
    {
        return $this->zauzet == 1 ? true : false;
    }

    public function multiUgovori()
    {
        return $this->tip()->multi_ugovori == 1 ? true : false;
    }

    public function sala()
    {
        return $this->belongsTo('App\Models\Sala', 'sala_id');
    }

    public function ugovori()
    {
        return $this->hasMany('App\Models\Ugovor', 'termin_id');
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function popunjenaMesta()
    {
        $sql = "SELECT SUM(broj_mesta) AS pm FROM ugovori WHERE termin_id = {$this->id};";
        return (int) $this->fetch($sql)[0]->pm;
    }

    public function popunjeniStolovi()
    {
        $sql = "SELECT SUM(broj_stolova) AS ps FROM ugovori WHERE termin_id = {$this->id};";
        return (int) $this->fetch($sql)[0]->ps;
    }

    public function slobodnaMesta()
    {
        return (int) ($this->sala()->max_kapacitet_mesta - $this->popunjenaMesta());
    }

    public function slobodniStolovi()
    {
        return (int) ($this->sala()->max_kapacitet_stolova - $this->popunjeniStolovi());
    }
    public function cenaTermina()
    {
        $sql = "SELECT SUM(iznos) AS cena FROM ugovori WHERE termin_id = {$this->id};";
        return (int) $this->fetch($sql)[0]->cena;
    }
}

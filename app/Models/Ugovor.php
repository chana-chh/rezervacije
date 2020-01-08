<?php

namespace App\Models;

use App\Classes\Model;

class Ugovor extends Model
{
    protected $table = 'ugovori';

    public function termin()
    {
        return $this->belongsTo('App\Models\Termin', 'termin_id');
    }

    public function punoIme()
    {
        return "{$this->prezime} {$this->ime}";
    }

    public function korisnik()
    {
        return $this->belongsTo('App\Models\Korisnik', 'korisnik_id');
    }

    public function meni()
    {
        return $this->belongsTo('App\Models\Meni', 'meni_id');
    }

    public function kapara()
    {
        $sql = "SELECT iznos FROM uplate WHERE ugovor_id = {$this->id} AND opis = 'kapara';";
        $kapara = $this->fetch($sql);
        $iznos = empty($kapara) ? 0 : $kapara[0]->iznos;
        return (float) $iznos;
    }

    public function uplate()
    {
        return $this->hasMany('App\Models\Uplata', 'ugovor_id');
    }

    public function uplateSuma()
    {
        $sql = "SELECT SUM(iznos) AS uplateSuma FROM uplate WHERE ugovor_id = {$this->id};";
        return (float) $this->fetch($sql)[0]->uplateSuma;
    }

    public function uplateDug()
    {
        return $this->iznos - $this->uplateSuma();
    }

    public function dokumenti(){
        return $this->hasMany('App\Models\Dokument', 'ugovor_id');
    }
}

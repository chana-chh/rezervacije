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

    public function dokumenti()
    {
        return $this->hasMany('App\Models\Dokument', 'ugovor_id');
    }

    public function zakljucavanje()
    {
        $zakljucati = false;
        if (!empty($this->uplate())) {
            $zakljucati = true;
        }
        if (
            $this->muzika_chk === 1 &&
            $this->fotograf_chk === 1 &&
            $this->torta_chk === 1 &&
            $this->dekoracija_chk === 1 &&
            $this->kokteli_chk === 1 &&
            $this->slatki_sto_chk === 1 &&
            $this->vocni_sto_chk === 1
        ) {
            $zakljucati = true;
        }
        return $zakljucati;
    }

    public function cenaUsluga()
    {
        $cena = 0.00;
            $cena =
            $this->muzika_iznos +
            $this->fotograf_iznos +
            $this->torta_iznos +
            $this->dekoracija_iznos +
            $this->kokteli_iznos +
            $this->slatki_sto_iznos +
            $this->vocni_sto_iznos +
            $this->posebni_zahtevi_iznos;
        return $cena;
    }
}

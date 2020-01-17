<?php

namespace App\Classes;

use App\Models\Log;
use App\Models\Korisnik;

class Logger
{
    private $korisnik;
    private $model;
    const DODAVANJE = "dodavanje";
    const IZMENA = "izmena";
    const BRISANJE = "brisanje";
    const UPLOAD = "upload";

    public function __construct($korisnik)
    {
        $this->korisnik = $korisnik;
        $this->model = new Log();
    }

    public function log($tip, $model, $polje, $model_stari = null)
    {
        $data = [
            'opis' => "{$model->id}, {$model->table()} - {$polje} : {$model->$polje}",
            'tip' => $tip,
            'stari' => $model_stari, // $model_stari === null ? '' : serialize(get_object_vars($model_stari)
            'korisnik_id' => $this->korisnik->id,
        ];

        $this->model->insert($data);
    }
}

<?php

namespace App\Classes;

use App\Models\Log;
use App\Models\Korisnik;

class Logger
{
    private $korisnik;
    private $model;
    public const DODAVANJE = "dodavanje";
    public const IZMENA = "izmena";
    public const BRISANJE = "brisanje";
    public const UPLOAD = "upload";

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
            'korisnik_id' => $this->korisnik->id,
        ];

        $this->model->insert($data);
    }
}

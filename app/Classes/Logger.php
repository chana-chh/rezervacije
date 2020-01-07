<?php

namespace App\Classes;

use App\Models\Log;

class Logger
{
    private $korsnik;
    public const DODAVANJE = "dodavanje";
    public const IZMENA = "izmena";
    public const BROSANJE = "brisanje";

    public function log(Logger $tip, Model $model, string $polje)
    {
        // TODO: napraviti ovo

        /*
            ubaciti u container (dic.php)
            u Controller.php napraviti metodu log koja koristi ovo
                korisnik i vreme se popunjavaju automatski
                tabela i id idu iz modela
                polje iz modela koje je zgodno da se ubaci u opis
                (kod izmene bi bilo dobro da bude izmenjeni podatak ako je moguce)
                array_dif([novo],[staro]) dobiju se razlicite vrednosti iz novo
        */
    }
}

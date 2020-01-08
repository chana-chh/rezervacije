<?php

namespace App\Controllers;

use App\Models\Termin;

class PregledController extends Controller
{
    public function getKalendar($request, $response)
    {
        $model_termin = new Termin();
        $termini = $model_termin->all();

        $data = [];

        foreach ($termini as $termin) {
            $ikonica = "";
            if ($termin->zauzet == 1) {
                $ikonica = 'fas fa-calendar-check text-success';
            }

            if ($termin->zauzet == 0) {
                $ikonica = 'fas fa-calendar-plus text-danger';
            }

            $data[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
                "advancedTitle" => $termin->tip()->tip . ' će se odžati u ' . $termin->sala()->naziv,
            ];
        }

            $data = json_encode($data);

        $this->render($response, 'kalendar.twig', compact('data'));
    }
}
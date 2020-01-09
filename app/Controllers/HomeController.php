<?php

namespace App\Controllers;
use App\Classes\Auth;
use App\Models\Termin;

class HomeController extends Controller
{
    public function getHome($request, $response)
    {
        $k = new Auth();

        if ($k->isLoggedIn() && $k->user()->nivo == 200) {
            $this->getKalendar($request, $response);
        }else{
            $this->render($response, 'home.twig');
        }
        
    }

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

            if ($termin->zauzet == 0 && !empty($termin->ugovori())) {
                $ikonica = 'fas fa-calendar-plus text-danger';
            }

            if ($termin->zauzet == 0 && empty($termin->ugovori())) {
                $ikonica = 'fas fa-question-circle text-primary';
            }

            $data[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
                "advancedTitle" => 'Ovaj događaj je ' . $termin->tip()->tip . ' i odžatiće se u ' . $termin->sala()->naziv . '. Trenutni broj zvanica je ' . $termin->popunjenaMesta() . ', a cena termina je: ' . number_format($termin->cenaTermina(), 2, ',', '.') . ' dinara',
                "advancedDetalj" => 'Ovaj događaj je ' . $termin->tip()->tip . '. Trenutni broj zvanica je ' . $termin->popunjenaMesta() . ', a cena termina je: ' . number_format($termin->cenaTermina(), 2, ',', '.') . ' dinara',
            ];
        }

            $data = json_encode($data);

        $this->render($response, 'kalendar.twig', compact('data'));
    }
}

<?php

namespace App\Controllers;

use App\Models\Termin;
use App\Models\Sala;

class TerminController extends Controller
{
    public function getTerminPregled($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $url_termin_dodavanje = $this->router->pathFor('termin.dodavanje.get');

        $modelTermin = new Termin();
        $termini = $modelTermin->all();

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
            ];
        }

        $data = json_encode($data);

        $this->render($response, 'termin/pregled.twig', compact('data', 'url_termin_dodavanje'));
    }

    public function getTerminDodavanje($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $model_sala = new Sala();
        $sale = $model_sala->all();

        $this->render($response, 'termin/dodavanje.twig', compact('sale', 'datum'));
    }

    public function postTerminDodavanje($request, $response)
    {
        $data = $request->getParams();
        $datum = isset($data['datum']) ? $data['datum'] : null;

        return $response->withRedirect($this->router->pathFor('termin.pregled.get', ['datum' => $datum]));
    }
}

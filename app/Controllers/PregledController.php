<?php

namespace App\Controllers;

use App\Models\Sala;

class PregledController extends Controller
{
    public function getKalendar($request, $response, $args)
    {
        $model_sala = new Sala();
        $sale = $model_sala->all();

        $datum = isset($args['datum']) ? $args['datum'] : null;

        $url = $this->router->pathFor('termin.dodavanje.get');

        $this->render($response, 'kalendar.twig', compact('sale', 'url', 'datum'));
    }
}

<?php

namespace App\Controllers;

use App\Models\Sala;

class PregledController extends Controller
{
    public function getKalendar($request, $response, $args)
    {
        $model_sala = new Sala();
        $sale = $model_sala->all();

        // dd($args);

        $this->render($response, 'kalendar.twig', compact('sale'));
    }
}

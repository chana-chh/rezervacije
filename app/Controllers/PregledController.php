<?php

namespace App\Controllers;

use App\Models\Sala;

class PregledController extends Controller
{
    public function getKalendar($request, $response)
    {
        $model_sala = new Sala();
        $sale = $model_sala->all();

        $this->render($response, 'kalendar.twig', compact('sale'));
    }
}

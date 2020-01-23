<?php

namespace App\Controllers;

use App\Models\Ugovor;

class StampaController extends Controller
{
    public function getUgovorStampaFizickaSingle($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model = new Ugovor();
        $ugovor = $model->find($id);

        $this->render($response, 'stampa/ugovor_fizicka.twig', compact('ugovor'));
    }
}

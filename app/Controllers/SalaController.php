<?php

namespace App\Controllers;

use App\Models\Sala;

class SalaController extends Controller
{
    public function getSale($request, $response)
    {
        $model = new Sala();
        $sale = $model->all();
       
        $this->render($response, 'sale.twig', compact('sale'));
    }
}

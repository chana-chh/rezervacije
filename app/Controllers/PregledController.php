<?php

namespace App\Controllers;

use App\Models\Sala;

class PregledController extends Controller
{
    public function getKalendar($request, $response)
    {
        $this->render($response, 'kalendar.twig');
    }
}

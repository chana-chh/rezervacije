<?php

namespace App\Controllers;

use App\Models\Termin;
use App\Models\Sala;

class TerminController extends Controller
{
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

        return $response->withRedirect($this->router->pathFor('kalendar', ['datum' => $datum]));
    }
}

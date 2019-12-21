<?php

namespace App\Controllers;

use App\Models\Termin;
use App\Models\Sala;

class TerminController extends Controller
{
    public function getTerminDodavanje($request, $response)
    {
        $model_sala = new Sala();
        $sale = $model_sala->all();

        $this->render($response, 'termin/dodavanje.twig', compact('sale'));
    }

    public function postTerminDodavanje($request, $response)
    {
        // $data = $request->getParams();
        // unset($data['csrf_name']);
        // unset($data['csrf_value']);

        // $validation_rules = [
        //     'naziv' => [
        //         'required' => true,
        //         'minlen' => 5,
        //         'maxlen' => 50,
        //         'alnum' => true,
        //         'unique' => 's_meniji.naziv'
        //     ]
        // ];

        // $this->validator->validate($data, $validation_rules);

        // if ($this->validator->hasErrors()) {
        //     $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja menija.');
        //     return $response->withRedirect($this->router->pathFor('meni'));
        // } else {
        //     $this->flash->addMessage('success', 'Nov meni je uspešno dodat.');
        //     $modelMenija = new Meni();
        //     $modelMenija->insert($data);
        //     return $response->withRedirect($this->router->pathFor('meni'));
        // }
    }
}

<?php

namespace App\Controllers;

use App\Models\Termin;
use App\Models\Sala;
use App\Models\TipDogadjaja;

class TerminController extends Controller
{
    public function getTerminPregled($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $url_termin_dodavanje = $this->router->pathFor('termin.dodavanje.get');

        $model_termin = new Termin();
        $termini = $model_termin->all();

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
        $model_tip = new TipDogadjaja();
        $tipovi = $model_tip->all();

        $this->render($response, 'termin/dodavanje.twig', compact('sale', 'tipovi', 'datum'));
    }

    public function postTerminDodavanje($request, $response)
    {
        $datum = isset($data['datum']) ? $data['datum'] : null;

        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'sala' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'pocetak' => [
                'required' => true
            ],
            'kraj' => [
                'required' => true
            ],
        ];
        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja termina.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
        } else {
            // upisujem u bazu
            $model_termin = new Termin();
            $data['korisnik_id'] = $this->auth->user()->id;
            $data['created_at'] = time();
            $model_termin->insert($data);
            $this->flash->addMessage('success', 'Termin je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.pregled.get'));
        }


        return $response->withRedirect($this->router->pathFor('termin.pregled.get', ['datum' => $datum]));
    }
}

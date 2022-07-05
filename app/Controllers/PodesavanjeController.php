<?php

namespace App\Controllers;

use App\Models\Podesavanje;

class PodesavanjeController extends Controller
{
    public function getIzmena($request, $response)
    {
        $model_podesavanje = new Podesavanje();
        $podesavanje = $model_podesavanje->find(1);

        $this->render($response, 'podesavanja/izmena.twig', compact('podesavanje'));
    }

    public function postIzmena($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $odlozeni = isset($data['odlozeni']) ? 1 : 0;
        $data['odlozeni'] = $odlozeni;

        $validation_rules = [
            'period_osoblje' => [
                'required' => true,
                'min' => 1,
                'max' => 36,
            ],
            'period_zakazivaci' => [
                'required' => true,
                'min' => 1,
                'max' => 36,
            ],
            'odlozeni' => [
                'required' => true,
            ]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podešavanja.');
            return $response->withRedirect($this->router->pathFor('podesavanja'));
        } else {
            $this->flash->addMessage('success', 'Podešavanja su uspešno sačuvana.');
            $model = new Podesavanje();
            $model->update($data, 1);
            return $response->withRedirect($this->router->pathFor('podesavanja'));
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\Sala;
Use App\Classes\Db;

class SalaController extends Controller
{
    public function getSale($request, $response)
    {
        $model = new Sala();
        $sale = $model->all();
       
        $this->render($response, 'sale.twig', compact('sale'));
    }

    public function postSalaDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 70,
                'alnum' => true,
                'unique' => 'sale.naziv'
            ],
            'max_kapacitet' => [
                'required' => true
            ]
        ];


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja sale.');
            return $response->withRedirect($this->router->pathFor('sale'));
        } else {
            $this->flash->addMessage('success', 'Nova sala je uspešno dodata.');
            $modelSale = new Sala();
            $modelSale->insert($data);
            return $response->withRedirect($this->router->pathFor('sale'));
        }
    }

    public function postSalaBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $modelSale = new Sala();
        $success = $modelSale->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Sala je uspešno obrisana.");
            return $response->withRedirect($this->router->pathFor('sale'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja sale.");
            return $response->withRedirect($this->router->pathFor('sale'));
        }
    }
}

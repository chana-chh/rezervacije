<?php

namespace App\Controllers;

use App\Models\Meni;
Use App\Classes\Db;

class MeniController extends Controller
{
    public function getMeni($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $modelMeni = new Meni;
        $sql = "SELECT * FROM s_meniji";
        $meni = $modelMeni->paginate($page, $sql);

        $this->render($response, 'meni.twig', compact('meni'));
    }

    public function getMeniDodavanje($request, $response)
    {
        $this->render($response, 'meni_dodavanje.twig');
    }

    public function postMeniDodavanje($request, $response)
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
            ]
        ];


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja menija.');
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('success', 'Nov meni je uspešno dodat.');
            $modelSale = new Sala();
            $modelSale->insert($data);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function postMeniBrisanje($request, $response)
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

    public function getMeniDetalj($request, $response)
    {
            $data = $request->getParams();
            $cName = $this->csrf->getTokenName();
            $cValue = $this->csrf->getTokenValue();
            $id = $data['id'];
            $modelSale = new Sala();
            $sala = $modelSale->find($id);
            $ar = ["cname" => $cName, "cvalue"=>$cValue, "sala"=>$sala];
            return $response->withJson($ar);
    }

    public function postMeniIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['id'];
        unset($data['id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 190,
                'alnum' => true,
                'unique' => 'groblja.naziv#id:' . $id,
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka groblja.');
            return $response->withRedirect($this->router->pathFor('groblja.izmena', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Podaci o groblju su uspešno izmenjeni.');
            $modelGroblja = new Meni();
            $modelGroblja->update($data, $id);
            return $response->withRedirect($this->router->pathFor('groblja'));
        }
    }
}

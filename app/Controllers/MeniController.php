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
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_meniji.naziv'
            ]
        ];


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja menija.');
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('success', 'Nov meni je uspešno dodat.');
            $modelMenija = new Meni();
            $modelMenija->insert($data);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function postMeniBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new Meni();
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Meni je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja menija.");
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function getMeniDetalj($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelMeni = new Meni();
        $meni = $modelMeni->find($id);
        $this->render($response, 'meni_pregled.twig', compact('meni'));
    }

    public function getMeniIzmena($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelMeni = new Meni();
        $meni = $modelMeni->find($id);
        $this->render($response, 'meni_izmena.twig', compact('meni'));
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
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_meniji.naziv#id:' . $id,
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka menija.');
            return $response->withRedirect($this->router->pathFor('meni.izmena', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Podaci menija su uspešno izmenjeni.');
            $model = new Meni();
            $model->update($data, $id);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }
}

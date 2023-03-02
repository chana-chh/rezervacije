<?php

namespace App\Controllers;

use App\Models\Sokoj;
use App\Classes\Logger;

class SokojController extends Controller
{
    public function getLista($request, $response)
    {
        $model = new Sokoj();
        $sokoj = $model->all('izvodjac');

        $this->render($response, 'sokoj.twig', compact('sokoj'));
    }

    public function postDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'izvodjac' => [
                'required' => true,
                'minlen' => 3,
                'maxlen' => 70,
                'alnum' => true
            ],
            'kompozicija' => [
                'required' => true,
                'minlen' => 3,
                'maxlen' => 150,
                'alnum' => true
            ]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja kompozicije.');
            return $response->withRedirect($this->router->pathFor('sokoj'));
        } else {
            $this->flash->addMessage('success', 'Nova kompozicija je uspešno dodata.');
            $modelSokoj = new Sokoj();
            $modelSokoj->insert($data);

            $id_sokoj = $modelSokoj->lastId();
            $sokoj = $modelSokoj->find($id_sokoj);
            $this->log(Logger::DODAVANJE, $sokoj, ['izvodjac', 'kompozicija']);
            return $response->withRedirect($this->router->pathFor('sokoj'));
        }
    }

    public function postBrisanje($request, $response)
    {
        $id_sokoj = (int)$request->getParam('idBrisanje');
        $modelSokoj = new Sokoj();
        $sokoj = $modelSokoj->find($id_sokoj);
        $success = $modelSokoj->deleteOne($id_sokoj);

        if ($success) {
            $this->flash->addMessage('success', "Kompozicija je uspešno obrisana.");
            $this->log(Logger::BRISANJE, $sokoj, ['izvodjac', 'kompozicija'], $sokoj);
            return $response->withRedirect($this->router->pathFor('sokoj'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja kompozicije.");
            return $response->withRedirect($this->router->pathFor('sokoj'));
        }
    }

    public function postDetalj($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();
        $id = $data['id'];
        $modelSokoj = new Sokoj();
        $sokoj = $modelSokoj->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "sokoj"=>$sokoj];

        return $response->withJson($ar);
    }

    public function postIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $datam = [
            "izvodjac" => $data['izvodjacModal'],
            "kompozicija" => $data['kompozicijaModal'],
        ];

        $validation_rules = [
            'izvodjac' => [
                'required' => true,
                'minlen' => 3,
                'maxlen' => 70,
                'alnum' => true
            ],
            'kompozicija' => [
                'required' => true,
                'minlen' => 3,
                'maxlen' => 150,
                'alnum' => true
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka kompozicije.');
            return $response->withRedirect($this->router->pathFor('sokoj'));
        } else {
            $this->flash->addMessage('success', 'Podaci o kompoziciji su uspešno izmenjeni.');
            $modelSokoj = new Sokoj();
            $stari = $modelSokoj->find($id);
            $modelSokoj->update($datam, $id);
            $sokoj = $modelSokoj->find($id);
            $this->log(Logger::IZMENA, $sokoj, ['izvodjac', 'kompozicija'], $stari);
            return $response->withRedirect($this->router->pathFor('sokoj'));
        }
    }

    public function getSlucajni($request, $response)
    {
        $model = new Sokoj();
        $sokoj =[];
        $bez = [];
        for ($i = 1; $i <= 10; $i++) {
            $sl = $model->slucajni($bez);
            while ($sl === []) {
                $sl = $model->slucajni($bez);
            }
            $zap = $sl[0];
            $bez[] = $zap->gg;
            $sokoj[] = $zap;
        }
        usort($sokoj, function($a, $b) {return strcmp($a->izvodjac, $b->izvodjac);});
        $this->render($response, 'nasumicno.twig', compact('sokoj'));
    }
}

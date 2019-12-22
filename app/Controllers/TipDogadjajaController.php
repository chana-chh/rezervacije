<?php

namespace App\Controllers;

use App\Models\TipDogadjaja;
Use App\Classes\Db;

class TipDogadjajaController extends Controller
{
    public function getTipove($request, $response)
    {
        $model = new TipDogadjaja();
        $tipovi = $model->all();
       
        $this->render($response, 'tip_dogadjaja.twig', compact('tipovi'));
    }

    public function postTipDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'tip' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_tip_dogadjaja.tip'
            ]
        ];


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja tipa događaja.');
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        } else {
            $this->flash->addMessage('success', 'Nov tip događaja je uspešno dodat.');
            $model = new TipDogadjaja();
            $model->insert($data);
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }

    public function postTipBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new TipDogadjaja();
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Tip događaja je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja tipa događaja.");
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }

    public function postTipDetalj($request, $response)
    {
            $data = $request->getParams();
            $cName = $this->csrf->getTokenName();
            $cValue = $this->csrf->getTokenValue();
            $id = $data['id'];
            $model = new TipDogadjaja();
            $tip = $model->find($id);
            $ar = ["cname" => $cName, "cvalue"=>$cValue, "tip"=>$tip];
            return $response->withJson($ar);
    }

    public function postTipIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $datam = ["tip"=>$data['tipModal']];

        $validation_rules = [
            'tip' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_tip_dogadjaja.tip#id:' . $id,
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka tipa događaja.');
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        } else {
            $this->flash->addMessage('success', 'Podaci o tipu događaja su uspešno izmenjeni.');
            $model = new TipDogadjaja();
            $model->update($datam, $id);
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }
}

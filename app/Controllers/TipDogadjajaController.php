<?php

namespace App\Controllers;

use App\Models\TipDogadjaja;
use App\Models\Log;
Use App\Classes\Db;
use App\Classes\Auth;

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

        $multi_ugovori = isset($data['multi_ugovori']) ? 1 : 0;
        $data['multi_ugovori'] = $multi_ugovori;

        $validation_rules = [
            'tip' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_tip_dogadjaja.tip'
            ],
            'multi_ugovori' => [
                'required' => true,
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

            $id_tipa = $model->lastId();
            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            $tip_dogadjaja = $model->find($id_tipa);
            $modelLog->insert(['opis' => $ime_korisnika." je dodao tip događaja ".$tip_dogadjaja->tip." sa id brojem ".$id_tipa,  'tip' => "dodavanje", 'korisnik_id' => $id_korisnika]);
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }

    public function postTipBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new TipDogadjaja();
        $tip_dogadjaja = $model->find($id);
        $naziv_tipa = $tip_dogadjaja->tip;
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Tip događaja je uspešno obrisan.");

            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            
            $modelLog->insert(['opis' => $ime_korisnika." je obrisao tip događaja ".$naziv_tipa." sa id brojem ".$id,  'tip' => "brisanje", 'korisnik_id' => $id_korisnika]);

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

        $multi_ugovori = isset($data['multi_ugovoriM']) ? 1 : 0;

        $datam = ["tip"=>$data['tipModal'], "multi_ugovori"=>$multi_ugovori];

        $validation_rules = [
            'tip' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_tip_dogadjaja.tip#id:' . $id,
            ],
            'multi_ugovori' => [
                'required' => true,
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

            $tip_dogadjaja = $model->find($id);
            $naziv_tipa = $tip_dogadjaja->tip;

            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            
            $modelLog->insert(['opis' => $ime_korisnika." je izmenio podatke o tipu događaja ".$naziv_tipa." sa id brojem ".$id,  'tip' => "izmena", 'korisnik_id' => $id_korisnika]);
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }
}

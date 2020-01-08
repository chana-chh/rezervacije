<?php

namespace App\Controllers;

use App\Models\Sala;
use App\Models\Log;
Use App\Classes\Db;
use App\Classes\Auth;

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
            'max_kapacitet_mesta' => [
                'required' => true
            ],
            'max_kapacitet_stolova' => [
                'required' => true
            ],
            'korisnik_id' => [
                'required' => true,
            ]
        ];

        $data['korisnik_id'] = $this->auth->user()->id;
        
        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja sale.');
            return $response->withRedirect($this->router->pathFor('sale'));
        } else {
            $this->flash->addMessage('success', 'Nova sala je uspešno dodata.');
            $modelSale = new Sala();
            $modelSale->insert($data);

            $id_sale = $modelSale->lastId();
            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            $sala = $modelSale->find($id_sale);
            $modelLog->insert(['opis' => $ime_korisnika." je dodao salu ".$sala->naziv." sa id brojem ".$id_sale,  'tip' => "dodavanje", 'korisnik_id' => $id_korisnika]);

            return $response->withRedirect($this->router->pathFor('sale'));
        }
    }

    public function postSalaBrisanje($request, $response)
    {
        $id_sale = (int)$request->getParam('idBrisanje');
        $modelSale = new Sala();
        $sala = $modelSale->find($id_sale);
        $naziv_sale = $sala->naziv;
        $success = $modelSale->deleteOne($id_sale);
        if ($success) {

            $this->flash->addMessage('success', "Sala je uspešno obrisana.");

            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            
            $modelLog->insert(['opis' => $ime_korisnika." je obrisao salu ".$naziv_sale." sa id brojem ".$id_sale,  'tip' => "brisanje", 'korisnik_id' => $id_korisnika]);

            return $response->withRedirect($this->router->pathFor('sale'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja sale.");
            return $response->withRedirect($this->router->pathFor('sale'));
        }
    }

    public function postSalaDetalj($request, $response)
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

    public function postSalaIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $datam = ["naziv"=>$data['nazivModal'], "max_kapacitet_mesta"=>$data['mk_mesta_Modal'], "max_kapacitet_stolova"=>$data['mk_stolova_Modal'],"napomena"=>$data['napomenaModal']];

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 70,
                'alnum' => true,
                'unique' => 'sale.naziv#id:' . $id,
            ],
            'max_kapacitet_mesta' => [
                'required' => true
            ],
            'max_kapacitet_stolova' => [
                'required' => true
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka sale.');
            return $response->withRedirect($this->router->pathFor('sale'));
        } else {
            $this->flash->addMessage('success', 'Podaci o sali su uspešno izmenjeni.');
            $modelSale = new Sala();
            $modelSale->update($datam, $id);

            $sala = $modelSale->find($id);
            $naziv_sale = $sala->naziv;

            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            
            $modelLog->insert(['opis' => $ime_korisnika." je izmenio podatke o sali ".$naziv_sale." sa id brojem ".$id,  'tip' => "izmena", 'korisnik_id' => $id_korisnika]);
            return $response->withRedirect($this->router->pathFor('sale'));
        }
    }
}

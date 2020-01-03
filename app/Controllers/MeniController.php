<?php

namespace App\Controllers;

use App\Models\Meni;
use App\Models\Log;
Use App\Classes\Db;
use App\Classes\Auth;

class MeniController extends Controller
{
    public function getMeni($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Meni();
        $meni = $model->paginate($page);

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
            ],
            'cena' => [
                'required' => true
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

            $id_menija = $modelMenija->lastId();
            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            $meni = $modelMenija->find($id_menija);
            $modelLog->insert(['opis' => $ime_korisnika." je dodao meni ".$meni->naziv." sa id brojem ".$id_menija,  'tip' => "dodavanje", 'korisnik_id' => $id_korisnika]);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function postMeniBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $modelMenija = new Meni();
        $meni = $modelMenija->find($id);
        $naziv_menija = $meni->naziv;
        $success = $modelMenija->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Meni je uspešno obrisan.");

            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            
            $modelLog->insert(['opis' => $ime_korisnika." je obrisao meni ".$naziv_menija." sa id brojem ".$id,  'tip' => "brisanje", 'korisnik_id' => $id_korisnika]);

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
            'cena' => [
                'required' => true
            ]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka menija.');
            return $response->withRedirect($this->router->pathFor('meni.izmena', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Podaci menija su uspešno izmenjeni.');
            $model = new Meni();
            $model->update($data, $id);

            $meni = $model->find($id);
            $naziv_menija = $meni->naziv;

            $modelLog= new Log();
            $k = new Auth();
            $id_korisnika = $k->user()->id;
            $ime_korisnika = $k->user()->ime;
            
            $modelLog->insert(['opis' => $ime_korisnika." je izmenio podatke o meniju ".$naziv_menija." sa id brojem ".$id,  'tip' => "izmena", 'korisnik_id' => $id_korisnika]);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }
}

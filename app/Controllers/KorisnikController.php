<?php

namespace App\Controllers;

use \App\Models\Korisnik;
Use App\Classes\Nivo;

class KorisnikController extends Controller
{
    public function getKorisnikLista($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Korisnik();
        $data = $model->paginate($page);

        $this->render($response, 'korisnik/lista.twig', compact('data'));
    }

        public function postKorisnikDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'ime' => [
                'required' => true,
                'minlen' => 5,
                'alnum' => true,
            ],
            'korisnicko_ime' => [
                'required' => true,
                'minlen' => 3,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 'korisnici.korisnicko_ime', // tabela.kolona
            ],
            'lozinka' => [
                'required' => true,
                'minlen' => 6,
            ],
            'lozinka_potvrda' => [
                'match_field' => 'lozinka',
            ],
            'nivo' => [
                'required' => true
            ],
            'korisnik_id' => [
                'required' => true,
            ]
        ];

        $data['korisnik_id'] = $this->auth->user()->id;


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja korisnika.');
            return $response->withRedirect($this->router->pathFor('admin.korisnik.lista'));
        } else {
            $this->flash->addMessage('success', 'Nov korisnik je uspešno dodat.');
            $modelKorisnik = new Korisnik();
            unset($data['lozinka_potvrda']);
            $data['lozinka'] = password_hash($data['lozinka'], PASSWORD_BCRYPT);
            $modelKorisnik->insert($data);
            return $response->withRedirect($this->router->pathFor('admin.korisnik.lista'));
        }
    }

    public function postKorisnikBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new Korisnik();
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Korisnik je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('admin.korisnik.lista'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja korisnika.");
            return $response->withRedirect($this->router->pathFor('admin.korisnik.lista'));
        }
    }

        public function postKorisnikDetalj($request, $response)
    {
	    	// 		value="0">Admin
	      	// 		value="100">Obrada
	      	// 		value="200">Pregled

    		$nivoA = new Nivo();
    		$nivoA->vrednost = 0;
    		$nivoA->naziv = "Admin";

    		$nivoO = new Nivo();
    		$nivoO->vrednost = 100;
    		$nivoO->naziv = "Obrada";

    		$nivoP = new Nivo();
    		$nivoP->vrednost = 200;
    		$nivoP->naziv = "Pregled";

    		$nivoi = [$nivoA, $nivoO, $nivoP];

            $data = $request->getParams();
            $cName = $this->csrf->getTokenName();
            $cValue = $this->csrf->getTokenValue();
            $id = $data['id'];
            $model = new Korisnik();
            $korisnik = $model->find($id);

            $ar = ["cname" => $cName, "cvalue"=>$cValue, "korisnik"=>$korisnik, "nivoi"=>$nivoi];
            return $response->withJson($ar);
    }

    public function postKorisnikIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $datam = [
            "ime"=>$data['imeM'], 
            "korisnicko_ime"=>$data['korisnicko_imeM'],
            "lozinka"=>$data['lozinkaM'],
            "lozinka_potvrda"=>$data['lozinka_potvrdaM'],
            "nivo"=>$data['nivoM']
        ];

        $validation_rules = [
            'ime' => [
                'required' => true,
                'minlen' => 5,
                'alnum' => true,
            ],
            'korisnicko_ime' => [
                'required' => true,
                'minlen' => 3,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 'korisnici.korisnicko_ime#id:' . $id,
            ],
            'nivo' => [
                'required' => true
            ],
        ];

        $validation_pass = [
            'lozinka' => [
                'required' => true,
                'minlen' => 6,
            ],
            'lozinka_potvrda' => [
                'match_field' => 'lozinka',
            ]
        ];

        if (!empty($datam['lozinka'])) {
            array_push($validation_rules, $validation_pass);
        }

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka korisnika.');
            return $response->withRedirect($this->router->pathFor('korisnici.izmena', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Podaci o korisniku su uspešno izmenjeni.');
            $modelKorisnik = new Korisnik();
            unset($datam['lozinka_potvrda']);
            if (!empty($datam['lozinka'])) {
                $datam['lozinka'] = password_hash($datam['lozinka'], PASSWORD_BCRYPT);
            } else{
                unset($datam['lozinka']);
            }
            $modelKorisnik->update($datam, $id);
            return $response->withRedirect($this->router->pathFor('admin.korisnik.lista'));
        }
    }
}

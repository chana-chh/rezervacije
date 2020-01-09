<?php

namespace App\Controllers;

use App\Models\Termin;
use App\Models\Sala;
use App\Models\TipDogadjaja;

class TerminController extends Controller
{
    public function getTerminPregled($request, $response, $args) // Kalendar
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $url_termin_dodavanje = $this->router->pathFor('termin.dodavanje.get');

        $model_termin = new Termin();
        $termini = $model_termin->all();

        $data = [];

        foreach ($termini as $termin) {
            $ikonica = "";
            if ($termin->zauzet == 1) {
                $ikonica = 'fas fa-calendar-check text-success';
            }

            if ($termin->zauzet == 0 && !empty($termin->ugovori())) {
                $ikonica = 'fas fa-calendar-plus text-danger';
            }

            if ($termin->zauzet == 0 && empty($termin->ugovori())) {
                $ikonica = 'fas fa-question-circle text-primary';
            }

            $data[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
            ];
        }

        $data = json_encode($data);

        $this->render($response, 'termin/pregled.twig', compact('data', 'url_termin_dodavanje'));
    }

    public function getTerminDetalj($request, $response, $args)
    {
        $id = $args['id'];
        if ($id) {
            $model_termin = new Termin();
            $termin = $model_termin->find($id);
            $this->render($response, 'termin/detalj.twig', compact('termin'));
        } else {
            return $response->withRedirect($this->router->pathFor('termin.pregled.get'));
        }
    }

    public function getTerminDodavanje($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $model_sala = new Sala();
        $sale = $model_sala->all();
        $model_tip = new TipDogadjaja();
        $tipovi = $model_tip->all();

        $this->render($response, 'termin/dodavanje.twig', compact('sale', 'tipovi', 'datum'));
    }

    public function postTerminDodavanje($request, $response)
    {
        $datum = isset($data['datum']) ? $data['datum'] : null;

        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'sala' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'pocetak' => [
                'required' => true
            ],
            'kraj' => [
                'required' => true
            ],
            'opis' => [
                'required' => true
            ],
        ];
        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja termina.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
        } else {
            // Preklapanje termina
            $model_termin = new Termin();
            $preklapanje = false;
            $pocetak = strtotime("{$data['datum']} {$data['pocetak']}");
            $kraj = strtotime("{$data['datum']} {$data['kraj']}");
            $sql = "SELECT datum, pocetak, kraj FROM termini WHERE datum = :dat AND sala_id = :sal";
            $params = [
            ':dat' => $data['datum'],
            ':sal' => $data['sala_id'],
            ];
            $postojeci_termini = $model_termin->fetch($sql, $params);
            // Uporedjivanje
            foreach ($postojeci_termini as $pt) {
                $pt_pocetak = strtotime("{$pt->datum} {$pt->pocetak}");
                $pt_kraj = strtotime("{$pt->datum} {$pt->kraj}");
                if ($pocetak >= $pt_pocetak && $pocetak < $pt_kraj) {
                    $preklapanje = true;
                }
                if ($kraj > $pt_pocetak && $kraj <= $pt_kraj) {
                    $preklapanje = true;
                }
                if ($pocetak <= $pt_pocetak && $kraj >= $pt_kraj) {
                    $preklapanje = true;
                }
            }
            if ($preklapanje) {
                $this->flash->addMessage('danger', 'Termin se preklapa sa nekim od postojećih termina.');
                return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
            }
            // Upisivanje u bazu
            $data['korisnik_id'] = $this->auth->user()->id;
            $data['created_at'] = date("Y-m-d H:i:s");
            $model_termin->insert($data);
            $this->flash->addMessage('success', 'Termin je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.pregled.get', ['datum' => $data['datum']]));
        }
    }

    public function postTerminBrisanje($request, $response)
    {
        $id = (int) $request->getParam('modal_termin_brisanje_id');
        $model = new Termin();
        $termin = $model->find($id);
        $datum = $termin->datum;

        if (count($termin->ugovori()) > 0) {
            $this->flash->addMessage('danger', "Postoje ugovori vezani za ovaj termin. Da bi se obrisao termin nephodno je prethodno obrisati sve ugovore vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $id]));
        }

        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Termin je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('termin.pregled.get', ['datum' => $datum]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja termina.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $id]));
        }
    }

    public function postTerminZakljucivanje($request, $response)
    {
        $data = $request->getParams();
        $termin_id = (int) $data['termin_id'];
        $this->addCsrfToken($data);
        // ovde zakljucavam/otkljucavam termin
        $model = new Termin();
        $termin = $model->find($termin_id);
        $zakljucen = $termin->zakljucen();
        $z = $zakljucen ? 0 : 1;
        $data['zakljucen'] = $z == 1 ? true : false;
        $model->update(['zauzet' => $z], $termin_id);
        return json_encode($data);
    }

    public function getTerminIzmena($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_sala = new Sala();
        $sale = $model_sala->all();
        $model_tip = new TipDogadjaja();
        $tipovi = $model_tip->all();
        $model_termin = new Termin();
        $termin = $model_termin->find($id);

        $this->render($response, 'termin/izmena.twig', compact('sale', 'tipovi', 'termin'));
    }

    public function postTerminIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = (int) $data['termin_id'];
        unset($data['termin_id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'sala' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'pocetak' => [
                'required' => true
            ],
            'kraj' => [
                'required' => true
            ],
            'opis' => [
                'required' => true
            ],
        ];
        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene termina.');
            return $response->withRedirect($this->router->pathFor('termin.izmena.get', ['id' => $id]));
        } else {
            // Preklapanje termina
            $model_termin = new Termin();
            $preklapanje = false;
            $pocetak = strtotime("{$data['datum']} {$data['pocetak']}");
            $kraj = strtotime("{$data['datum']} {$data['kraj']}");
            $sql = "SELECT datum, pocetak, kraj FROM termini WHERE datum = :dat AND sala_id = :sal AND id != :iid";
            $params = [
            ':dat' => $data['datum'],
            ':sal' => $data['sala_id'],
            ':iid' => $id,
            ];
            $postojeci_termini = $model_termin->fetch($sql, $params);
            // Uporedjivanje
            foreach ($postojeci_termini as $pt) {
                $pt_pocetak = strtotime("{$pt->datum} {$pt->pocetak}");
                $pt_kraj = strtotime("{$pt->datum} {$pt->kraj}");
                if ($pocetak >= $pt_pocetak && $pocetak < $pt_kraj) {
                    $preklapanje = true;
                }
                if ($kraj > $pt_pocetak && $kraj <= $pt_kraj) {
                    $preklapanje = true;
                }
                if ($pocetak <= $pt_pocetak && $kraj >= $pt_kraj) {
                    $preklapanje = true;
                }
            }
            if ($preklapanje) {
                $this->flash->addMessage('danger', 'Termin se preklapa sa nekim od postojećih termina.');
                return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
            }
            // Upisivanje u bazu
            $data['korisnik_id'] = $this->auth->user()->id;
            $data['zauzet'] = isset($data['zauzet']) ? 1 : 0;
            $model_termin->update($data, $id);
            $this->flash->addMessage('success', 'Termin je uspešno izmenjen.');
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $id]));
        }
    }
}

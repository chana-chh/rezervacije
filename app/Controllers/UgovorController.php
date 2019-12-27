<?php

namespace App\Controllers;

use App\Models\Ugovor;
use App\Models\Termin;
Use App\Classes\Db;

class UgovorController extends Controller
{
    public function getUgovor($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Ugovor();
        $ugovori = $model->paginate($page);

        $model_termini = new Termin();
        $termini = $model_termini->all();

        $this->render($response, 'ugovori.twig', compact('ugovori', 'termini'));
    }

    public function postUgovorPretraga($request, $response)
    {
        $_SESSION['DATA_UGOVORI_PRETRAGA'] = $request->getParams();
        return $response->withRedirect($this->router->pathFor('ugovori.pretraga'));
    }

    public function getUgovorPretraga($request, $response)
    {
        $data = $_SESSION['DATA_UGOVORI_PRETRAGA'];
        array_shift($data);
        array_shift($data);
        if (empty($data['prezime']) && 
            empty($data['ime']) && 
            empty($data['telefon']) && 
            empty($data['email']) && 
            empty($data['napomena']) && 
            empty($data['broj_ugovora']) && 
            empty($data['datum']) && 
            empty($data['termin_id']) && 
            empty($data['korisnik_id'])) {
            $this->getLog($request, $response);
        }

        $data['prezime'] = str_replace('%', '', $data['prezime']);
        $data['ime'] = str_replace('%', '', $data['ime']);
        $data['telefon'] = str_replace('%', '', $data['telefon']);
        $data['email'] = str_replace('%', '', $data['email']);
        $data['napomena'] = str_replace('%', '', $data['napomena']);
        $data['broj_ugovora'] = str_replace('%', '', $data['broj_ugovora']);

        $prezime = '%' . filter_var($data['prezime'], FILTER_SANITIZE_STRING) . '%';
        $ime = '%' . filter_var($data['ime'], FILTER_SANITIZE_STRING) . '%';
        $telefon = '%' . filter_var($data['telefon'], FILTER_SANITIZE_STRING) . '%';
        $email = '%' . filter_var($data['email'], FILTER_SANITIZE_STRING) . '%';
        $napomena = '%' . filter_var($data['napomena'], FILTER_SANITIZE_STRING) . '%';
        $broj_ugovora = '%' . filter_var($data['broj_ugovora'], FILTER_SANITIZE_STRING) . '%';

        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $where = " WHERE ";
        $params = [];
        if (!empty($data['prezime'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "prezime LIKE :prezime";
            $params[':prezime'] = $prezime;
        }
        if (!empty($data['ime'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "ime LIKE :ime";
            $params[':ime'] = $ime;
        }
        if (!empty($data['telefon'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "telefon LIKE :telefon";
            $params[':telefon'] = $telefon;
        }
        if (!empty($data['email'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "email LIKE :email";
            $params[':email'] = $email;
        }
        if (!empty($data['napomena'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "napomena LIKE :napomena";
            $params[':napomena'] = $napomena;
        }
        if (!empty($data['broj_ugovora'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "broj_ugovora LIKE :broj_ugovora";
            $params[':broj_ugovora'] = $broj_ugovora;
        }
        if (!empty($data['datum'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "DATE(datum) = :datum";
            $params[':datum'] = $data['datum'];
        }
        if (!empty($data['korisnik_id'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "korisnik_id = :korisnik_id";
            $params[':korisnik_id'] = $data['korisnik_id'];
        }
        if (!empty($data['termin_id'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "termin_id = :termin_id";
            $params[':termin_id'] = $data['termin_id'];
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Ugovor();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY datum DESC;";
        $ugovori = $model->paginate($page, 'page', $sql, $params);

        $this->render($response, 'ugovori.twig', compact('ugovori', 'data'));
    }

    public function getUgovorDodavanje($request, $response)
    {
        $this->render($response, 'ugovor_dodavanje.twig');
    }

    public function postUgovorDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'termin_id' => [
                'required' => true,
                'min' => 1,
            ],
            'broj_ugovora' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 'ugovori.broj_ugovora'
            ],
            'prezime' => [
                'required' => true,
            ],
            'ime' => [
                'required' => true,
            ]
        ];


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja ugovora.');
            return $response->withRedirect($this->router->pathFor('ugovori'));
        } else {
            $this->flash->addMessage('success', 'Nov ugovor je uspešno dodat.');
            $modelUgovora = new Ugovor();
            $modelUgovora->insert($data);
            return $response->withRedirect($this->router->pathFor('ugovori'));
        }
    }

    public function postUgovorBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new Ugovor();
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Ugovor je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('ugovori'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja ugovora.");
            return $response->withRedirect($this->router->pathFor('ugovori'));
        }
    }

    public function getUgovorDetalj($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelMeni = new Ugovor();
        $meni = $modelMeni->find($id);
        $this->render($response, 'meni_pregled.twig', compact('ugovori'));
    }

    public function getUgovorIzmena($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelMeni = new Ugovor();
        $meni = $modelMeni->find($id);
        $this->render($response, 'meni_izmena.twig', compact('ugovori'));
    }

    public function postUgovorIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['id'];
        unset($data['id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
                'termin_id' => [
                'required' => true,
                'min' => 1,
            ],
            'broj_ugovora' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 'ugovori.broj_ugovora#id:' . $id
            ],
            'prezime' => [
                'required' => true,
            ],
            'ime' => [
                'required' => true,
            ]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka ugovora.');
            return $response->withRedirect($this->router->pathFor('ugovor.izmena', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Podaci ugovora su uspešno izmenjeni.');
            $model = new Ugovor();
            $model->update($data, $id);
            return $response->withRedirect($this->router->pathFor('ugovori'));
        }
    }
}

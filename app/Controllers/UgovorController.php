<?php

namespace App\Controllers;

use App\Models\Ugovor;
use App\Models\Termin;
use App\Models\Meni;
use App\Models\Korisnik;
use App\Models\Uplata;
use App\Classes\Db;
use App\Classes\Auth;

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
        $model = new Termin();
        $termin = $model->find(10);

        $ugovori = $termin->ugovori();

        $modelMeni = new Meni();
        $meniji = $modelMeni->all();

        $this->render($response, 'ugovor_dodavanje.twig', compact('termin', 'meniji', 'ugovori'));
    }

    public function getUgovorDodavanjeTermin($request, $response, $args)
    {
        $termin_id = (int) $args['termin_id'];
        $model_termin = new Termin();
        $termin = $model_termin->find($termin_id);

        $model_meni = new Meni();
        $meniji = $model_meni->all();

        // provera da li postoji ugovor i da li je dozvoljeno vise ugovora za termin
        $broj_ugovora_za_termin = count($termin->ugovori());
        if (!$termin->multiUgovori() && $broj_ugovora_za_termin > 0) {
            $this->flash->addMessage('warning', "Nije dozvoljeno dodavanje više od jednog ugovora.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }
        $this->render($response, 'ugovor/dodavanje.twig', compact('termin', 'meniji'));
    }

    public function postUgovorDodavanje($request, $response)
    {
        $data = $request->getParams();

        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $muzika_chk = isset($data['muzika_chk']) ? 1 : 0;
        $data['muzika_chk'] = $muzika_chk;
        $fotograf_chk = isset($data['fotograf_chk']) ? 1 : 0;
        $data['fotograf_chk'] = $fotograf_chk;
        $torta_chk = isset($data['torta_chk']) ? 1 : 0;
        $data['torta_chk'] = $torta_chk;
        $dekoracija_chk = isset($data['dekoracija_chk']) ? 1 : 0;
        $data['dekoracija_chk'] = $dekoracija_chk;
        $kokteli_chk = isset($data['kokteli_chk']) ? 1 : 0;
        $data['kokteli_chk'] = $kokteli_chk;
        $slatki_sto_chk = isset($data['slatki_sto_chk']) ? 1 : 0;
        $data['slatki_sto_chk'] = $slatki_sto_chk;
        $vocni_sto_chk = isset($data['vocni_sto_chk']) ? 1 : 0;
        $data['vocni_sto_chk'] = $vocni_sto_chk;

        // $data['termin_id'] = 1; Za sada !!!
        $data['korisnik_id'] = $this->auth->user()->id;

        $validation_rules = [
            'termin_id' => ['required' => true,],
            'broj_ugovora' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 'ugovori.broj_ugovora'],
            'datum' => ['required' => true,],
            'meni_id' => ['required' => true,],
            'prezime' => ['required' => true,],
            'ime' => ['required' => true,],
            'broj_mesta' => ['required' => true,],
            'broj_stolova' => ['required' => true,],
            'broj_mesta_po_stolu' => ['required' => true,],
            'iznos' => ['required' => true,],
            'prosek_godina' => ['required' => true,],
            'muzika_chk' => ['required' => true,],
            'fotograf_chk' => ['required' => true,],
            'torta_chk' => ['required' => true,],
            'dekoracija_chk' => ['required' => true,],
            'kokteli_chk' => ['required' => true,],
            'slatki_sto_chk' => ['required' => true,],
            'vocni_sto_chk' => ['required' => true,],
            'korisnik_id' => ['required' => true,]
        ];


        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja ugovora.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.ugovor', ['termin_id'=>(int)$data['termin_id']]));
        } else {
            $model_ugovor = new Ugovor();
            $model_ugovor->insert($data);
            $this->flash->addMessage('success', 'Novi ugovor je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.ugovor', ['termin_id'=>(int)$data['termin_id']]));
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
        $modelUgovor = new Ugovor();
        $ugovor = $modelUgovor->find($id);
        $this->render($response, 'ugovor_pregled.twig', compact('ugovor'));
    }

    public function getUgovorIzmena($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelUgovor = new Ugovor();
        $ugovor = $modelUgovor->find($id);

        $modelMeni = new Meni();
        $meniji = $modelMeni->all();

        $this->render($response, 'ugovor_izmena.twig', compact('ugovor', 'meniji'));
    }

    public function postUgovorIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['id'];
        unset($data['id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $k = new Auth();
        $id_korisnika = $k->user()->id;

        $muzika_chk = isset($data['muzika_chk']) ? 1 : 0;
        $data['muzika_chk'] = $muzika_chk;
        $fotograf_chk = isset($data['fotograf_chk']) ? 1 : 0;
        $data['fotograf_chk'] = $fotograf_chk;
        $torta_chk = isset($data['torta_chk']) ? 1 : 0;
        $data['torta_chk'] = $torta_chk;
        $dekoracija_chk = isset($data['dekoracija_chk']) ? 1 : 0;
        $data['dekoracija_chk'] = $dekoracija_chk;
        $kokteli_chk = isset($data['kokteli_chk']) ? 1 : 0;
        $data['kokteli_chk'] = $kokteli_chk;
        $slatki_sto_chk = isset($data['slatki_sto_chk']) ? 1 : 0;
        $data['slatki_sto_chk'] = $slatki_sto_chk;
        $vocni_sto_chk = isset($data['vocni_sto_chk']) ? 1 : 0;
        $data['vocni_sto_chk'] = $vocni_sto_chk;

        $data['korisnik_id'] = $id_korisnika;

        $validation_rules = [
            'termin_id' => ['required' => true,],
            'broj_ugovora' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 'ugovori.broj_ugovora#id:' . $id],
            'datum' => ['required' => true,],
            'meni_id' => ['required' => true,],
            'prezime' => ['required' => true,],
            'ime' => ['required' => true,],
            'broj_mesta' => ['required' => true,],
            'broj_stolova' => ['required' => true,],
            'broj_mesta_po_stolu' => ['required' => true,],
            'iznos' => ['required' => true,],
            'prosek_godina' => ['required' => true,],
            'muzika_chk' => ['required' => true,],
            'fotograf_chk' => ['required' => true,],
            'torta_chk' => ['required' => true,],
            'dekoracija_chk' => ['required' => true,],
            'kokteli_chk' => ['required' => true,],
            'slatki_sto_chk' => ['required' => true,],
            'vocni_sto_chk' => ['required' => true,],
            'korisnik_id' => ['required' => true,]
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

    public function postUgovorUplata($request, $response)
    {
        $id = (int)$request->getParam('idUgovora');
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);
        unset($data['idUgovora']);

        $k = new Auth();
        $id_korisnika = $k->user()->id;

        $data['ugovor_id'] = $id;
        $data['korisnik_id'] = $id_korisnika;

        $validation_rules = [
            'ugovor_id' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'iznos' => [
                'required' => true
            ],
            'nacin_placanja' => [
                'required' => true
            ],
            'korisnik_id' => [
                'required' => true
            ]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom evidentiranja uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.detalj', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', "Uplata je uspešno evidentirana.");
            $modelUplate = new Uplata();
            $modelUplate->insert($data);
            return $response->withRedirect($this->router->pathFor('ugovor.detalj', ['id' => $id]));
        }
    }
}

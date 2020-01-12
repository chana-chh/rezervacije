<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Models\Ugovor;
use App\Models\Termin;
use App\Models\Meni;
use App\Models\Uplata;
Use App\Classes\Nacin;

class TerminUgovorController extends Controller
{
    public function getUgovorDodavanjeTermin($request, $response, $args)
    {
        $termin_id = (int) $args['termin_id'];
        $model_termin = new Termin();
        $termin = $model_termin->find($termin_id);

        $model_meni = new Meni();
        $meniji = $model_meni->all();

        // provera multi ugovor
        if (!$termin->multiUgovori() && !empty($termin->ugovori())) {
            $this->flash->addMessage('warning', "Nije dozvoljeno dodavanje više od jednog ugovora.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }
        $this->render($response, 'ugovor/dodavanje.twig', compact('termin', 'meniji'));
    }

    public function postUgovorDodavanjeTermin($request, $response)
    {
        $data = $request->getParams();
        $kapara = (float) $data['kapara'];
        $nacin_placanja = $data['nacin_placanja'];

        unset($data['csrf_name']);
        unset($data['csrf_value']);
        unset($data['cekiraj_sve']);
        unset($data['kapara']);
        unset($data['nacin_placanja']);

        $data['muzika_chk'] = isset($data['muzika_chk']) ? 1 : 0;
        $data['fotograf_chk'] = isset($data['fotograf_chk']) ? 1 : 0;
        $data['torta_chk'] = isset($data['torta_chk']) ? 1 : 0;
        $data['dekoracija_chk'] = isset($data['dekoracija_chk']) ? 1 : 0;
        $data['kokteli_chk'] = isset($data['kokteli_chk']) ? 1 : 0;
        $data['slatki_sto_chk'] = isset($data['slatki_sto_chk']) ? 1 : 0;
        $data['vocni_sto_chk'] = isset($data['vocni_sto_chk']) ? 1 : 0;

        $validation_rules = [
            'termin_id' => ['required' => true,],
            // ako nije obavezno ne moze da bude unique jer ce biti vise praznih
            // i u bazi pravi problem unique key
            // jedino da ako nije prazno proverimo da li postoji
            // 'broj_ugovora' => [
            //     'required' => true,
            //     'maxlen' => 50,
            //     'unique' => 'ugovori.broj_ugovora'],
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
            'muzika_iznos' => ['required' => true,],
            'fotograf_iznos' => ['required' => true,],
            'torta_iznos' => ['required' => true,],
            'dekoracija_iznos' => ['required' => true,],
            'kokteli_iznos' => ['required' => true,],
            'slatki_sto_iznos' => ['required' => true,],
            'vocni_sto_iznos' => ['required' => true,],
            'posebni_zahtevi_iznos' => ['required' => true,]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja ugovora.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.ugovor', ['termin_id' => (int) $data['termin_id']]));
        } else {
            $model_ugovor = new Ugovor();
            $data['korisnik_id'] = $this->auth->user()->id;
            $model_ugovor->insert($data);
            // dodavanje uplate za kaparu
            $ugovor = $model_ugovor->find($model_ugovor->lastId());
            $this->log(Logger::DODAVANJE, $ugovor, 'broj_ugovora');
            if ($kapara > 0) {
                $kapara_data = [
                'ugovor_id' => $ugovor->id,
                'datum' => date("Y-m-d H:i:s"),
                'iznos' => $kapara,
                'nacin_placanja' => $nacin_placanja,
                'opis' => 'kapara',
                'korisnik_id' => $this->auth->user()->id,
                ];
                $model_uplata = new Uplata();
                $model_uplata->insert($kapara_data);
                $uplata = $model_uplata->find($model_uplata->lastId());
                $this->log(Logger::DODAVANJE, $uplata, 'opis');
            }

            $this->flash->addMessage('success', 'Novi ugovor je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => (int) $data['termin_id']]));
        }
    }

    public function postUgovorBrisanjeTermin($request, $response)
    {
        $id = (int) $request->getParam('idBrisanje');
        $model = new Ugovor();
        $ugovor = $model->find($id);
        $termin_id = (int) $ugovor->termin_id;
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Ugovor je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin_id]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja ugovora.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin_id]));
        }
    }

    public function getUgovorIzmenaTermin($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $model_termin = new Termin();
        $termin = $model_termin->find($ugovor->termin_id);

        $model_meni = new Meni();
        $meniji = $model_meni->all();

        $this->render($response, 'ugovor/izmena.twig', compact('ugovor', 'meniji', 'termin'));
    }

    public function postUgovorIzmenaTermin($request, $response)
    {
        $data = $request->getParams();
        $id = (int) $data['id'];
        unset($data['id']);
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
            'muzika_iznos' => ['required' => true,],
            'fotograf_iznos' => ['required' => true,],
            'torta_iznos' => ['required' => true,],
            'dekoracija_iznos' => ['required' => true,],
            'kokteli_iznos' => ['required' => true,],
            'slatki_sto_iznos' => ['required' => true,],
            'vocni_sto_iznos' => ['required' => true,],
            'posebni_zahtevi_iznos' => ['required' => true,]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene ugovora.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.izmena.get', ['id' => $id]));
        } else {
            $model = new Ugovor();
            $model->update($data, $id);
            $this->flash->addMessage('success', 'Ugovor je uspešno izmenjen.');
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => (int) $data['termin_id']]));
        }
    }

    public function getUgovorDetaljTermin($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $this->render($response, 'ugovor/detalj.twig', compact('ugovor'));
    }

    public function postUgovorUplataTermin($request, $response)
    {
        $data = $request->getParams();
        $ugovor_id = (int) $data['ugovor_id'];
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $data['korisnik_id'] = $this->auth->user()->id;

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
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom evidentiranja uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        } else {
            $model_uplate = new Uplata();
            $model_uplate->insert($data);
            $this->flash->addMessage('success', "Uplata je uspešno evidentirana.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        }
    }

    public function getUgovorUplateLista($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $this->render($response, 'ugovor/uplate.twig', compact('ugovor'));
    }

    public function postUplataDetalj($request, $response)
    {
            $nivoG = new Nacin();
            $nivoG->vrednost = "gotovina";
            $nivoG->naziv = "Gotovina";

            $nivoK = new Nacin();
            $nivoK->vrednost = "kartica";
            $nivoK->naziv = "Kartica";

            $nivoC = new Nacin();
            $nivoC->vrednost = "ček";
            $nivoC->naziv = "Ček";

            $nivoF = new Nacin();
            $nivoF->vrednost = "faktura";
            $nivoF->naziv = "Faktura";

            $nacini = [$nivoG, $nivoK, $nivoC, $nivoF];

            $data = $request->getParams();
            $cName = $this->csrf->getTokenName();
            $cValue = $this->csrf->getTokenValue();
            $id = $data['id'];
            $model_uplate = new Uplata();
            $uplata = $model_uplate->find($id);
            $ar = ["cname" => $cName, "cvalue"=>$cValue, "uplata"=>$uplata, "nacini"=>$nacini];
            return $response->withJson($ar);
    }

    public function postIzmenaUplata($request, $response)
    {

        $data = $request->getParams();
        $id = $data['idIzmena'];
        $model = new Uplata();
        $uplata = $model->find($id);
        $ugovor_id = $uplata->ugovor_id;
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $datam = ["opis"=>$data['opisModal'], "datum"=>$data['datumModal'], "iznos"=>$data['iznosModal'], "nacin_placanja"=>$data['nacin_placanjaModal']];

        $validation_rules = [
            'datum' => [
                'required' => true
            ],
            'iznos' => [
                'required' => true
            ],
            'nacin_placanja' => [
                'required' => true
            ],
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom izmene uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        } else {
            $model_uplate = new Uplata();
            $model_uplate->update($datam, $id);
            $this->flash->addMessage('success', "Podaci o uplati su uspešno izmenjeni.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        }
    }

    public function postUplataBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new Uplata();
        $uplata = $model->find($id);
        $ugovor_id = $uplata->ugovor_id;
        $success = $model->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Uplata je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        }
    }
}

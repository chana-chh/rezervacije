<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Models\Termin;

class IzvestajiController extends Controller
{
    public function getPoSalama($request, $response)
    {
        $lista = false;
        $godina = date('Y');
        $od_datum = "{$godina}-01-01";
        $do_datum = date('Y-m-d');
        $this->render($response, 'izvestaji/sale.twig', compact('lista', 'od_datum', 'do_datum'));
    }

    public function postPoSalama($request, $response)
    {
        $_SESSION['DATA_IZVESTAJI_SALE_LISTA'] = $request->getParams();
        return $response->withRedirect($this->router->pathFor('izvestaji.sale.lista'));
    }

    public function getPoSalamaLista($request, $response)
    {
        $lista = true;

        $data = $_SESSION['DATA_IZVESTAJI_SALE_LISTA'];
        // unset($_SESSION['DATA_IZVESTAJI_SALE_LISTA']);

        $validation_rules = [
            'od' => [
                'required' => true
            ],
            'do' => [
                'required' => true
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izrade izveštaja.');
            return $response->withRedirect($this->router->pathFor('izvestaji.sale'));
        } else {
            $od = $data['od'];
            $do = $data['do'];
            $params = [
            ':od' => $od,
            ':do' => $do,
            ];

            $sql = "SELECT ter.sala_id, sale.naziv, ter.datum, SUM(ugo.ug_broj_mesta) AS broj_mesta, SUM((ugo.ug_iznos_meni + ugo.ug_iznos_usluge)) AS iznos, SUM(ugo.ug_uplate_iznos) AS uplate_iznos,
                            (SUM((ugo.ug_iznos_meni + ugo.ug_iznos_usluge)) - SUM(ugo.ug_uplate_iznos)) AS dug
                    FROM termini AS ter
                    JOIN (SELECT ug.id, ug.termin_id, SUM(ug.broj_mesta) AS ug_broj_mesta, SUM(ug.broj_stolova) AS ug_broj_stolova, SUM(ug.iznos) AS ug_iznos_meni,
                                    SUM((ug.muzika_iznos +
                                    ug.fotograf_iznos +
                                    ug.torta_iznos +
                                    ug.dekoracija_iznos +
                                    ug.kokteli_iznos +
                                    ug.slatki_sto_iznos +
                                    ug.vocni_sto_iznos +
                                    ug.posebni_zahtevi_iznos)) AS ug_iznos_usluge,
                                    SUM(up.uplate_iznos) AS ug_uplate_iznos
                            FROM ugovori AS ug
                            LEFT JOIN
                                (SELECT uplate.ugovor_id, SUM(uplate.iznos) AS uplate_iznos
                                    FROM uplate
                                    GROUP BY uplate.ugovor_id) AS up
                            ON up.ugovor_id = ug.id
                            GROUP BY ug.termin_id) AS ugo
                    ON ugo.termin_id = ter.id
                    JOIN sale ON sale.id = ter.sala_id
                    GROUP BY ter.sala_id WITH ROLLUP
                    HAVING ter.datum BETWEEN :od AND :do;";
            $model = new Termin();
            $izvestaj = $model->fetch($sql, $params);
            $this->render($response, 'izvestaji/sale.twig', compact('lista', 'izvestaj', 'od', 'do'));
        }
    }
}

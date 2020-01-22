<?php

namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\Logger;
use App\Models\Termin;
use App\Models\Ugovor;
use App\Models\Meni;

class VlasnikController extends Controller
{
   
    public function getKalendarVlasnik($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $model_termin = new Termin();
        $sql = "SELECT * FROM {$model_termin->getTable()} WHERE datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH);";
        $termini = $model_termin->fetch($sql);
        $data = [];

        foreach ($termini as $termin) {
            
            $ikonica = $termin->statusIkonica();
            $data[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
                "advancedTitle" => 'Ovaj događaj je ' . $termin->tip()->tip . ' i odžatiće se u ' . $termin->sala()->naziv . '. Trenutni broj zvanica je ' . $termin->popunjenaMesta() . ', a cena termina je: ' . number_format($termin->cenaTermina(), 2, ',', '.') . ' dinara',
                "advancedDetalj" => $termin->tip()->tip . '.<br> Broj zvanica: ' . $termin->popunjenaMesta() . '<br> Cena: ' . number_format($termin->cenaTermina(), 2, ',', '.'),
            ];
        }

        $data = json_encode($data);

        $this->render($response, 'kalendar_vlasnik.twig', compact('data'));
    }

    public function getTerminVlasnik($request, $response, $args)
    {
        $id = $args['id'];
        if ($id) {
            $model_termin = new Termin();
            $termin = $model_termin->find($id);

            $this->render($response, 'termin/detalj_vlasnik.twig', compact('termin'));
        } else {
            return $response->withRedirect($this->router->pathFor('vlasnik.kalendar'));
        }
    }

    public function getUgovorVlasnik($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $this->render($response, 'ugovor/detalj_vlasnik.twig', compact('ugovor'));
    }

    public function getUplateVlasnik($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $this->render($response, 'ugovor/uplate_vlasnik.twig', compact('ugovor'));
    }
}

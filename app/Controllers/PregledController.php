<?php

namespace App\Controllers;

use App\Models\Sala;
use App\Models\Termin;


	class PregledController extends Controller
	{

    public function getKalendar($request, $response)
    {

        $model_sala = new Sala();
        $sale = $model_sala->all();

        $datum = isset($args['datum']) ? $args['datum'] : null;

        $url = $this->router->pathFor('termin.dodavanje.get');

        $modelTermin = new Termin();
        $termini = $modelTermin->all();

        $naslovi = array();
        $idijevi = array();
        $datumi = array();
        $poceci = array();
        $krajevi = array();
        $detalji = array();

        foreach ($termini as $termin) {
            $naslovi[] = [($termin->pocetak ? '<strong style="text-align: center; font-size: 1.4em !important"><center>'. date('H:i', strtotime($termin->pocetak)). '</center></strong><center>' : '').$termin->sala()->naziv.'</center>'];
            $idijevi[] = $termin->id;
            $datumi[] = $termin->datum;
            $poceci[] = $termin->pocetak;
            $krajevi[] = $termin->kraj;
            $detalji[] = '<strong style="font-size: 1.4em !important">'.$termin->opis.'</strong><br><span><img src=" /rezervacije/pub/img/res.jpg" class="img-fluid" alt="Responsive image"></span>';
        }

        $naslovie = json_encode($naslovi);
        $idijevie = json_encode($idijevi);
        $datumie = json_encode($datumi);
        $pocecie = json_encode($poceci);
        $krajevie = json_encode($krajevi);
        $detaljie = json_encode($detalji);

        $this->render($response, 'kalendar.twig', compact('sale', 'url', 'datum', 'idijevie', 'naslovie', 'datumie', 'pocecie', 'krajevie', 'detaljie'));
    }
}

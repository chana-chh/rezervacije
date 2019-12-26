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
            
            if ($termin->zauzet == 0) {
                $ikonica = '<i class="fas fa-calendar-minus fa-2x" style="color: #0275d8"></i>';
            }
            else if($termin->zauzet == 1){
                $ikonica = '<i class="fas fa-calendar-plus fa-2x" style="color: #5cb85c"></i>';
            }else{
                $ikonica = '<i class="fas fa-calendar-check fa-2x" style="color: #d9534f"></i>';
            }

            $naslovi[] = [($termin->pocetak ? '<strong style="text-align: center; font-size: 1.4em !important"><center>'. date('H:i', strtotime($termin->pocetak)). '</center></strong><center>' : '').$termin->sala()->naziv.'</center>'];
            $idijevi[] = $termin->id;
            $datumi[] = $termin->datum;
            $poceci[] = $termin->pocetak;
            $krajevi[] = $termin->kraj;
            // Dodaj polje u bazi boja i daj vrednosti 0,1,2 kao id sala i u zavisnosti od toga se menja back dogadjaja i skini komentare u eventRender
            //$detalji[] = $termin->boja; 
            // $detalji[] = '<strong style="font-size: 1.4em !important">'.$termin->opis.'</strong><br><span><img src=" /rezervacije/pub/img/res.jpg" class="img-fluid" alt="Responsive image"></span>';
            //Varijanta sa FontAwesome i ZAUZETO
            $detalji[] = $ikonica;

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

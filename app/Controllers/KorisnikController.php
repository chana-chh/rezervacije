<?php

namespace App\Controllers;

use \App\Models\Korisnik;

class HomeController extends Controller
{
    public function getKorisnikLista($request, $response)
    {
        $model = new Korisnik();
        $data = $model->all();

        $this->render($response, 'korisnik/lista.twig', compact('data'));
    }
}

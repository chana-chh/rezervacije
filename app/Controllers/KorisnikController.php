<?php

namespace App\Controllers;

use \App\Models\Korisnik;

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
}

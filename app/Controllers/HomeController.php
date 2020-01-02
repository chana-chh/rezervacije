<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function getHome($request, $response)
    {
        // $password = "pregled";
        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // $password = "zakazivanje";
        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // dd($hash);
        $this->render($response, 'home.twig', compact('password', 'hash'));
    }

    //Za sada ovde
    public function getKalendar($request, $response)
    {
        $this->render($response, 'kalendar.twig');
    }
}

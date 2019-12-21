<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function getHome($request, $response)
    {
        $password = "zakazivanje";
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // dd($hash);
        // $this->flash->addMessage('success', 'Dobrodošli u Ordinaciju');
        $this->render($response, 'home.twig', compact('password', 'hash'));
    }

    //Za sada ovde
    public function getKalendar($request, $response)
    {
        $this->render($response, 'kalendar.twig');
    }
}

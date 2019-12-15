<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function getHome($request, $response)
    {
        // $password="vlasnik";
        // $hash = password_hash($password, PASSWORD_DEFAULT);

        // $this->flash->addMessage('success', 'Dobrodošli u Ordinaciju');
        $this->render($response, 'home.twig', compact('password', 'hash'));
    }
}

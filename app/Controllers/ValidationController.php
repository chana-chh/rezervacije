<?php

namespace App\Controllers;

// use App\Models\Termin;
// use App\Models\Sala;

class ValidationController extends Controller
{
        public function getValidation($request, $response)
    {
        $this->render($response, 'validation.twig');
    }

    public function postValidation($request, $response)
    {
        $data = $request->getParams();

        dd($data);
        
        return $response->withRedirect($this->router->pathFor('pocetna', ['datum' => $datum]));
    }
}

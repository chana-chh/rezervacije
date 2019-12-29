<?php

namespace App\Controllers;

class ValidationController extends Controller
{
    public function getValidation($request, $response)
    {
        $this->render($response, 'validation.twig');
    }

    public function postValidation($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'fname' => [
                'minlen' => 4
            ],
        ];
        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške.');
            return $response->withRedirect($this->router->pathFor('valid'));
        } else {
            $this->flash->addMessage('success', 'Validacija prosla.');
            return $response->withRedirect($this->router->pathFor('pocetna'));
        }
    }
}

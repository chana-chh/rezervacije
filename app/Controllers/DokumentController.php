<?php

namespace App\Controllers;

use App\Models\Dokument;

class DokumentController extends Controller
{
    public function postDokumentDodavanje($request, $response)
    {
        $data = $request->getParams();
        $dokument = $request->getUploadedFiles()['dokument'];

        unset($data['csrf_name']);
        unset($data['csrf_value']);

        if ($dokument->getError() === UPLOAD_ERR_NO_FILE) {
            $this->flash->addMessage('danger', 'Morate odabrati dokument.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $data['ugovor_id']]));
        }
        if ($dokument->getError() !== UPLOAD_ERR_OK) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom prebacivanja dokumenta.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $data['ugovor_id']]));
        }

        $validation_rules = [
            'opis' => [
                'required' => true,
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja dokumenta.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $data['ugovor_id']]));
        } else {
            $unique = bin2hex(random_bytes(8));
            $extension = pathinfo($dokument->getClientFilename(), PATHINFO_EXTENSION);
            $opis = str_replace(" ", "_", $data['opis']);
            $name = "{$data['ugovor_id']}_{$opis}_{$unique}";
            $filename = "{$name}.{$extension}";
            $veza = URL . "/doc/{$filename}";
            $data['link'] = $veza;
            $data['korisnik_id'] = $this->auth->user()->id;
            $dokument->moveTo('doc/' . $filename);
            $modelDokument = new Dokument();
            $modelDokument->insert($data);
            $this->flash->addMessage('success', 'Dokument je uspešno sačuvan.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $data['ugovor_id']]));
        }
    }
}

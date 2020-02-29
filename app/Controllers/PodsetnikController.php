<?php

namespace App\Controllers;

use App\Models\Podsetnik;

class PodsetnikController extends Controller
{
    public function postPodsetnikDodavanje($request, $response)
    {
        $data = $this->data();
        $data['ugovor_id'] = $data['ugovor_id_podsetnik'];
        unset($data['ugovor_id_podsetnik']);
        
        $validation_rules = [
                'datum' => ['required' => true,],
                'tekst' => ['required' => true,],
            ];

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja podsetnika.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $data['ugovor_id']]));
        } else {
            $model = new Podsetnik();
            $model->insert($data);
            $podsetnik = $model->find($model->lastId());
            $this->log($this::DODAVANJE, $podsetnik, ['datum']);
            $this->flash->addMessage('success', 'Podsetnik je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $data['ugovor_id']]));
        }
    }
    /*
    public function postPodsetnikiBrisanje($request, $response)
    {
        $id = (int)$request->getParam('modal_dokument_id');
        $ugovor_id = (int)$request->getParam('modal_dokument_ugovor_id');
        $modelDokument = new Dokument();
        $dok = $modelDokument->find($id);
        $tmp = explode('/', $dok->link);
        $file = DIR . 'pub' . DS . 'doc' . DS . end($tmp);
        $success = $modelDokument->deleteOne($id);
        if ($success) {
            unlink($file);
            $this->flash->addMessage('success', "Dokument je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $ugovor_id]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja dokumenta.");
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $ugovor_id]));
        }
    }

    public function postPodsetnikDetalj($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();
        $id = $data['id'];
        $modelDokument = new Dokument();
        $dokument = $modelDokument->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "dokument"=>$dokument];
        return $response->withJson($ar);
    }

    public function postPodsetnikIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmenaDokumenta'];
        $ugovor_id = $data['idUgovorDokumenta'];
        unset($data['idUgovorDokumenta']);
        unset($data['idIzmenaDokumenta']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $datam = ["opis"=>$data['opisModal']];

        $validation_rules = [
            'opis' => [
                'required' => true
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka dokumenta.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $ugovor_id]));
        } else {
            $this->flash->addMessage('success', 'Podaci o dokumentu su uspešno izmenjeni.');
            $modelDokument = new Dokument();
            $modelDokument->update($datam, $id);
            $dokument = $modelDokument->find($id);
            $this->log(Logger::IZMENA, $dokument, 'opis');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.detalj.get', ['id' => $ugovor_id]));
        }
    }
    */
}

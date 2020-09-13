<?php

namespace App\Controllers;

use App\Models\Podsetnik;
use App\Models\Korisnik;
use App\Classes\Logger;

class PodsetnikController extends Controller
{
    public function getPodsetnikLista($request, $response, $args)
    {
        $id = (int) $args['korisnik_id'];
        $model_korisnik = new Korisnik();
        $korisnik = $model_korisnik->find($id);

        $this->render($response, 'podsetnik/lista.twig', compact('korisnik'));
    }

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
            return $response->withRedirect($this->referer());
        } else {
            $model = new Podsetnik();
            $model->insert($data);
            $podsetnik = $model->find($model->lastId());
            $this->log($this::DODAVANJE, $podsetnik, ['datum']);
            $this->flash->addMessage('success', 'Podsetnik je uspešno dodat.');
            return $response->withRedirect($this->referer());
        }
    }

    public function postPodsetnikReseno($request, $response)
    {
        $data = $this->data();
        $id = (int) $data['id'];
        $this->addCsrfToken($data);
        $model = new Podsetnik();
        $podsetnik = $model->find($id);
        $reseno = 0;
        if ($podsetnik->reseno == 0) {
            $reseno = 1;
        }
        $model->update(['reseno' => $reseno], $id);
        $podsetnik1 = $model->find($id);
        $this->log($this::IZMENA, $podsetnik1, 'datum', $podsetnik);

        return json_encode($data);
    }

    public function postPodsetnikiBrisanje($request, $response)
    {
        $id = (int)$request->getParam('podsetnik_id');
        $ugovor_id = (int)$request->getParam('ugovor_id');
        $modelPodsetnik = new Podsetnik();
        $pod = $modelPodsetnik->find($id);
        $success = $modelPodsetnik->deleteOne($id);
        if ($success) {
            $this->flash->addMessage('success', "Podsetnik je uspešno obrisan.");
            return $response->withRedirect($this->referer());
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja podsetnika.");
            return $response->withRedirect($this->referer());
        }
    }

    public function postPodsetnikDetalj($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();
        $id = $data['id'];
        $modelPodsetnik = new Podsetnik();
        $podsetnik = $modelPodsetnik->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "podsetnik"=>$podsetnik];
        return $response->withJson($ar);
    }

    public function postPodsetnikIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['podsetnik_id'];
        $ugovor_id = $data['ugovor_id'];
        unset($data['ugovor_id']);
        unset($data['podsetnik_id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
                    'datum' => ['required' => true],
                    'tekst' => ['required' => true],
                ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka podsetnika.');
            return $response->withRedirect($this->referer());
        } else {
            $this->flash->addMessage('success', 'Podaci o podsetniku su uspešno izmenjeni.');
            $modelPodsetnik = new Podsetnik();
            $modelPodsetnik->update($data, $id);
            $podsetnik = $modelPodsetnik->find($id);
            $this->log(Logger::IZMENA, $podsetnik, 'datum');
            return $response->withRedirect($this->referer());
        }
    }
}

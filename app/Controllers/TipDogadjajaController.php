<?php

namespace App\Controllers;

use App\Models\TipDogadjaja;
use App\Classes\Logger;

class TipDogadjajaController extends Controller
{
    public function getTipove($request, $response)
    {
        $model = new TipDogadjaja();
        $tipovi = $model->all();

        $this->render($response, 'tip_dogadjaja.twig', compact('tipovi'));
    }

    public function postTipDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $multi_ugovori = isset($data['multi_ugovori']) ? 1 : 0;
        $data['multi_ugovori'] = $multi_ugovori;

        $validation_rules = [
            'tip' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'unique' => 's_tip_dogadjaja.tip'
            ],
            'multi_ugovori' => [
                'required' => true,
            ],
            'korisnik_id' => [
                'required' => true,
            ]
        ];

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja tipa događaja.');
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        } else {
            $this->flash->addMessage('success', 'Nov tip događaja je uspešno dodat.');
            $model = new TipDogadjaja();
            $model->insert($data);
            $id_tipa = $model->lastId();
            $tip_dogadjaja = $model->find($id_tipa);
            $this->log(Logger::DODAVANJE, $tip_dogadjaja, 'tip');
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }

    public function postTipBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new TipDogadjaja();
        $tip_dogadjaja = $model->find($id);
        $success = $model->deleteOne($id);

        if ($success) {
            $this->flash->addMessage('success', "Tip događaja je uspešno obrisan.");
            $this->log(Logger::BRISANJE, $tip_dogadjaja, 'tip', $tip_dogadjaja);
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja tipa događaja.");
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }

    public function postTipDetalj($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();
        $id = $data['id'];
        $model = new TipDogadjaja();
        $tip = $model->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "tip"=>$tip];

        return $response->withJson($ar);
    }

    public function postTipIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $multi_ugovori = isset($data['multi_ugovoriM']) ? 1 : 0;

        $datam = [
            "tip" => $data['tipModal'],
            "multi_ugovori" => $multi_ugovori,
        ];

        $validation_rules = [
            'tip' => [
                'required' => true,
                'minlen' => 5,
                'maxlen' => 50,
                'alnum' => true,
                'unique' => 's_tip_dogadjaja.tip#id:' . $id,
            ],
            'multi_ugovori' => [
                'required' => true,
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka tipa događaja.');
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        } else {
            $this->flash->addMessage('success', 'Podaci o tipu događaja su uspešno izmenjeni.');
            $model = new TipDogadjaja();
            $stari = $model->find($id);
            $model->update($datam, $id);
            $tip_dogadjaja = $model->find($id);
            $this->log(Logger::IZMENA, $tip_dogadjaja, 'tip', $stari);
            return $response->withRedirect($this->router->pathFor('tip_dogadjaja'));
        }
    }
}

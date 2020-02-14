<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Models\Ugovor;
use App\Models\Termin;
use App\Models\Meni;
use App\Models\Uplata;

class UplataController extends Controller
{
    public function postUplataDodavanje($request, $response)
    {
        $data = $request->getParams();
        $ugovor_id = (int) $data['ugovor_id'];
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $data['korisnik_id'] = $this->auth->user()->id;

        $kapara = isset($data['kapara']) ? 1 : 0;
        $data['kapara'] = $kapara;

        $validation_rules = [
            'ugovor_id' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'iznos' => [
                'required' => true,
                'min' => 1
            ],
            'nacin_placanja' => [
                'required' => true
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom evidentiranja uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        } else {
            $model_uplate = new Uplata();
            $model_uplate->insert($data);
            $uplata = $model_uplate->find($model_uplate->lastId());
            $model_ugovor = new Ugovor();
            $ugovor = $model_ugovor->find($ugovor_id);
            $model_termin = new Termin();
            $termin = $model_termin->find($ugovor->termin_id);
            if ($termin->zakljucavanje()) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            } else {
                $model_termin->update(['zauzet' => 0], $termin->id);
            }
            $this->log(Logger::DODAVANJE, $uplata, 'datum');
            $this->flash->addMessage('success', "Uplata je uspešno evidentirana.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        }
    }

    public function postUplataIzmenaAjax($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();
        $id = $data['id'];
        $model_uplate = new Uplata();
        $nacini = $model_uplate->enumOrSetList('nacin_placanja');
        $uplata = $model_uplate->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "uplata"=>$uplata, "nacini"=>$nacini];

        return $response->withJson($ar);
    }

    public function postUplataIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['idIzmena'];
        $model = new Uplata();
        $uplata = $model->find($id);
        $ugovor_id = $uplata->ugovor_id;
        unset($data['idIzmena']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $kapara = isset($data['kaparaModal']) ? 1 : 0;

        $datam = [
            "opis"=>$data['opisModal'],
            "datum"=>$data['datumModal'],
            "iznos"=>$data['iznosModal'],
            "nacin_placanja"=>$data['nacin_placanjaModal'],
            "kapara"=>$kapara
        ];

        $validation_rules = [
            'datum' => [
                'required' => true
            ],
            'iznos' => [
                'required' => true
            ],
            'nacin_placanja' => [
                'required' => true
            ],
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom izmene uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        } else {
            $model_uplate = new Uplata();
            $uplata = $model_uplate->find($id);
            $model_uplate->update($datam, $id);
            $model_ugovor = new Ugovor();
            $ugovor = $model_ugovor->find($ugovor_id);
            $model_termin = new Termin();
            $termin = $model_termin->find($ugovor->termin_id);

            if ($termin->zakljucavanje()) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            } else {
                $model_termin->update(['zauzet' => 0], $termin->id);
            }

            $this->log(Logger::IZMENA, $uplata, 'datum', $uplata);
            $this->flash->addMessage('success', "Podaci o uplati su uspešno izmenjeni.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor_id]));
        }
    }

    public function postUplataBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $model = new Uplata();
        $uplata = $model->find($id);
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($uplata->ugovor_id);
        $model_termin = new Termin();
        $termin = $model_termin->find($ugovor->termin_id);

        if ($termin->zakljucavanje()) {
            $model_termin->update(['zauzet' => 1], $termin->id);
        } else {
            $model_termin->update(['zauzet' => 0], $termin->id);
        }

        $success = $model->deleteOne($id);

        if ($success) {
            $this->log(Logger::BRISANJE, $uplata, 'datum', $uplata);
            $this->flash->addMessage('success', "Uplata je uspešno obrisana.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor->id]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja uplate.");
            return $response->withRedirect($this->router->pathFor('ugovor.uplate.lista', ['id' => $ugovor->id]));
        }
    }
}

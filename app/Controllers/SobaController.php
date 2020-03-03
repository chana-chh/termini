<?php

namespace App\Controllers;

use App\Models\Soba;

class SobaController extends Controller
{
    
    public function getSobe($request, $response)
    {
        $model = new Soba();
        $sobe = $model->paginate($this->page(), 'page', "SELECT * FROM sobe ORDER BY id;");

        $this->render($response, 'sobe/lista.twig', compact('sobe'));
    }

    public function postSobePretraga($request, $response)
    {
        $_SESSION['DATA_SOBE_PRETRAGA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('sobe.pretraga'));
    }

    public function getSobePretraga($request, $response)
    {
        $data = $_SESSION['DATA_SOBE_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        if (empty($data['nazivf'])) {
            return $response->withRedirect($this->router->pathFor('sobe'));
        }

        $data['nazivf'] = str_replace('%', '', $data['nazivf']);

        $naziv = '%' . filter_var($data['nazivf'], FILTER_SANITIZE_STRING) . '%';

        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $where = " WHERE ";
        $params = [];

        if (!empty($data['nazivf'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "naziv LIKE :naziv";
            $params[':naziv'] = $naziv;
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Soba();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY naziv;";
        $sobe = $model->paginate($page, 'page', $sql, $params);

        $this->render($response, 'sobe/lista.twig', compact('sobe', 'data'));
    }

    public function postSobeDodavanje($request, $response)
    {

        $data = $this->data();

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 255,
                'unique' => 'sobe.naziv'
            ],
            'cena' => [
                'required' => true
            ]
        ];

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja sobe.');
            return $response->withRedirect($this->router->pathFor('sobe'));
        } else {
            $this->flash->addMessage('success', 'Novi soba je uspešno dodata.');
            $model = new Soba();
            $model->insert($data);

            $id_sobe = $model->lastId();
            $soba = $model->find($id_sobe);
            $this->log($this::DODAVANJE, $soba, ['naziv', 'cena']);
            return $response->withRedirect($this->router->pathFor('sobe'));
        }
    }

    public function postSobeBrisanje($request, $response)
    {
        $id_sobe = (int)$request->getParam('idBrisanje');
        $model = new Soba();
        $soba = $model->find($id_sobe);
        $success = $model->deleteOne($id_sobe);

        if ($success) {
            $this->flash->addMessage('success', "Soba je uspešno obrisana.");
            $this->log($this::BRISANJE, $soba, 'naziv', $soba);
            return $response->withRedirect($this->router->pathFor('sobe'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja sobe.");
            return $response->withRedirect($this->router->pathFor('sobe'));
        }
    }

    public function postSobeDetalj($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();

        $id = $data['id'];
        $model = new Soba();
        $soba = $model->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "soba"=>$soba];

        return $response->withJson($ar);
    }

    public function postSobeIzmena($request, $response)
    {
        $data = $this->data();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);

        $datam = [
            "naziv" => $data['nazivModal'],
            "cena" => $data['cenaModal'],
            "opis" => $data['opisModal']
        ];

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 255,
                'unique' => 'sobe.naziv#id:' . $id,
            ],
            'cena' => [
                'required' => true
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka sobe.');
            return $response->withRedirect($this->router->pathFor('sobe'));
        } else {
            $this->flash->addMessage('success', 'Podaci sobe su uspešno izmenjeni.');
            $model = new Soba();
            $stari = $model->find($id);
            $model->update($datam, $id);
            $soba = $model->find($id);
            $this->log($this::IZMENA, $soba, 'naziv', $stari);
            return $response->withRedirect($this->router->pathFor('sobe'));
        }
    }
}

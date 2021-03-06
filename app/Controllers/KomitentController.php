<?php

namespace App\Controllers;

use App\Models\Komitent;
use App\Models\Kategorija;

class KomitentController extends Controller
{
    
    public function getKomitenti($request, $response)
    {
        $model = new Komitent();
        $komitenti = $model->paginate($this->page(), 'page', "SELECT * FROM komintenti;");
        $kategorije_model = new Kategorija;
        $kategorije = $kategorije_model->all();
        $this->render($response, 'komitenti/lista.twig', compact('komitenti', 'kategorije'));
    }

    public function postKomitentiPretraga($request, $response)
    {
        $_SESSION['DATA_KOMITENTI_PRETRAGA'] = $request->getParams();
        return $response->withRedirect($this->router->pathFor('komitenti.pretraga'));
    }

    public function getKomitentiPretraga($request, $response)
    {
        $data = $_SESSION['DATA_KOMITENTI_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        if (empty($data['nazivf']) &&
            empty($data['kategorijaf'])) {
            return $response->withRedirect($this->router->pathFor('komitenti'));
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

        if (!empty($data['kategorijaf'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "kategorija_id = :kategorija";
            $params[':kategorija'] = $data['kategorijaf'];
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Komitent();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY naziv;";
        $komitenti = $model->paginate($page, 'page', $sql, $params);

        $this->render($response, 'komitenti/lista.twig', compact('komitenti', 'data'));
    }

    public function postKomitentiDodavanje($request, $response)
    {
        $data = $this->data();
        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 255,
                'unique' => 'komintenti.naziv'
            ],
            'kategorija_id' => [
                'required' => true
            ]
        ];

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja komitenta.');
            return $response->withRedirect($this->router->pathFor('komitenti'));
        } else {
            $this->flash->addMessage('success', 'Novi komitent je uspešno dodat.');
            $modelKomitent = new Komitent();
            $modelKomitent->insert($data);

            $id_komitenta = $modelKomitent->lastId();
            $komitent = $modelKomitent->find($id_komitenta);
            $this->log($this::DODAVANJE, $komitent, ['naziv', 'kategorija']);
            return $response->withRedirect($this->router->pathFor('komitenti'));
        }
    }

    public function postKomitentiBrisanje($request, $response)
    {
        $id_komitenta = (int)$request->getParam('idBrisanje');
        $modelKomitent = new Komitent();
        $komitent = $modelKomitent->find($id_komitenta);
        $success = $modelKomitent->deleteOne($id_komitenta);

        if ($success) {
            $this->flash->addMessage('success', "Komitent je uspešno obrisan.");
            $this->log($this::BRISANJE, $komitent, 'naziv', $komitent);
            return $response->withRedirect($this->router->pathFor('komitenti'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja komitenta.");
            return $response->withRedirect($this->router->pathFor('komitenti'));
        }
    }

    public function postKomitentiDetalj($request, $response)
    {
        $data = $request->getParams();
        $cName = $this->csrf->getTokenName();
        $cValue = $this->csrf->getTokenValue();

        $id = $data['id'];
        $modelKomitent = new Komitent();
        $kategorije_model = new Kategorija;
        $kategorije = $kategorije_model->all();
        $komitent = $modelKomitent->find($id);
        $ar = ["cname" => $cName, "cvalue"=>$cValue, "komitent"=>$komitent, "kategorije"=>$kategorije];

        return $response->withJson($ar);
    }

    public function postKomitentiIzmena($request, $response)
    {
        $data = $this->data();
        $id = $data['idIzmena'];
        unset($data['idIzmena']);

        $datam = [
            "naziv" => $data['nazivModal'],
            "kategorija_id" => $data['kategorijaModal'],
            "napomena" => $data['napomenaModal']
        ];

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 255,
                'unique' => 'komintenti.naziv#id:' . $id,
            ],
            'kategorija_id' => [
                'required' => true
            ]
        ];

        $this->validator->validate($datam, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka komitenta.');
            return $response->withRedirect($this->router->pathFor('komitenti'));
        } else {
            $this->flash->addMessage('success', 'Podaci o komitentu su uspešno izmenjeni.');
            $modelKomitent = new Komitent();
            $stari = $modelKomitent->find($id);
            $modelKomitent->update($datam, $id);
            $komitent = $modelKomitent->find($id);
            $this->log($this::IZMENA, $komitent, 'naziv', $stari);
            return $response->withRedirect($this->router->pathFor('komitenti'));
        }
    }
}

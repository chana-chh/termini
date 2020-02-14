<?php

namespace App\Controllers;

use App\Models\Meni;
use App\Classes\Logger;

class MeniController extends Controller
{
    public function getMeni($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Meni();
        $meni = $model->paginate($page);

        $this->render($response, 'meni.twig', compact('meni'));
    }

    public function postMeniPretraga($request, $response)
    {
        $_SESSION['DATA_MENI_PRETRAGA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('meni.pretraga'));
    }

    public function getMeniPretraga($request, $response)
    {
        $data = $_SESSION['DATA_MENI_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        if (empty($data['upit'])) {
            $this->getMeni($request, $response);
        }

        $data['upit'] = str_replace('%', '', $data['upit']);

        $upit = '%' . filter_var($data['upit'], FILTER_SANITIZE_STRING) . '%';

        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $where = " WHERE ";
        $params = [];

        if (!empty($data['upit'])) {
            $where .= "naziv LIKE :naziv";
            $params[':naziv'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "hladno_predjelo LIKE :hladno_predjelo";
            $params[':hladno_predjelo'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "sirevi LIKE :sirevi";
            $params[':sirevi'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "corba LIKE :corba";
            $params[':corba'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "glavno_jelo LIKE :glavno_jelo";
            $params[':glavno_jelo'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "meso LIKE :meso";
            $params[':meso'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "hleb LIKE :hleb";
            $params[':hleb'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "karta_pica LIKE :karta_pica";
            $params[':karta_pica'] = $upit;
        }

        if (!empty($data['upit'])) {
            if ($where !== " WHERE ") {
                $where .= " OR ";
            }
            $where .= "napomena LIKE :napomena";
            $params[':napomena'] = $upit;
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Meni();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY id;";
        $meni = $model->paginate($page, 'page', $sql, $params);

        $this->render($response, 'meni.twig', compact('meni', 'data'));
    }

    public function getMeniDodavanje($request, $response)
    {
        $this->render($response, 'meni_dodavanje.twig');
    }

    public function postMeniDodavanje($request, $response)
    {
        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 's_meniji.naziv'
            ],
            'cena' => [
                'required' => true
            ],
            'korisnik_id' => [
                'required' => true,
            ]
        ];

        $data['hladno_predjelo'] = rtrim($data['hladno_predjelo']);
        $data['sirevi'] = rtrim($data['sirevi']);
        $data['corba'] = rtrim($data['corba']);
        $data['glavno_jelo'] = rtrim($data['glavno_jelo']);
        $data['meso'] = rtrim($data['meso']);
        $data['hleb'] = rtrim($data['hleb']);
        $data['karta_pica'] = rtrim($data['karta_pica']);

        $data['hladno_predjelo'] = rtrim($data['hladno_predjelo'], ';');
        $data['sirevi'] = rtrim($data['sirevi'], ';');
        $data['corba'] = rtrim($data['corba'], ';');
        $data['glavno_jelo'] = rtrim($data['glavno_jelo'], ';');
        $data['meso'] = rtrim($data['meso'], ';');
        $data['hleb'] = rtrim($data['hleb'], ';');
        $data['karta_pica'] = rtrim($data['karta_pica'], ';');

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja menija.');
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('success', 'Nov meni je uspešno dodat.');
            $modelMenija = new Meni();
            $modelMenija->insert($data);
            $id_menija = $modelMenija->lastId();
            $meni = $modelMenija->find($id_menija);
            $this->log(Logger::DODAVANJE, $meni, 'naziv');
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function postMeniBrisanje($request, $response)
    {
        $id = (int)$request->getParam('idBrisanje');
        $modelMenija = new Meni();
        $meni = $modelMenija->find($id);
        $success = $modelMenija->deleteOne($id);

        if ($success) {
            $this->flash->addMessage('success', "Meni je uspešno obrisan.");
            $this->log(Logger::BRISANJE, $meni, 'naziv', $meni);
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja menija.");
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function getMeniDetalj($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelMeni = new Meni();
        $meni = $modelMeni->find($id);

        $this->render($response, 'meni_pregled.twig', compact('meni'));
    }

    public function getMeniIzmena($request, $response, $args)
    {
        $id = (int)$args['id'];
        $modelMeni = new Meni();
        $meni = $modelMeni->find($id);

        $this->render($response, 'meni_izmena.twig', compact('meni'));
    }

    public function postMeniIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = $data['id'];
        unset($data['id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 's_meniji.naziv#id:' . $id,
            ],
            'cena' => [
                'required' => true
            ]
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene podataka menija.');
            return $response->withRedirect($this->router->pathFor('meni.izmena', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Podaci menija su uspešno izmenjeni.');
            $model = new Meni();
            $stari = $model->find($id);
            $model->update($data, $id);
            $meni = $model->find($id);
            $this->log(Logger::IZMENA, $meni, 'naziv', $stari);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function ajaxMeni($request, $response)
    {
        $data = $request->getParams();
        $naziv = $data['nazivMenija'];
        $cena = $data['cenaMenija'];
        $napomena = $data['napomenaMenija'];
        unset($data['csrf_name']);
        unset($data['csrf_value']);
        unset($data['nazivMenija']);
        unset($data['cenaMenija']);
        unset($data['napomenaMenija']);

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 's_meniji.naziv'
            ],
            'cena' => [
                'required' => true
            ],
        ];

        $data['naziv'] = $naziv;
        $data['cena'] = $cena;
        $data['napomena'] = $napomena;
        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        $ar=[];
        $this->addCsrfToken($ar);
        $ar['greska'] = false;

        if ($this->validator->hasErrors()) {
            $ar['greska'] = true;
            return $response->withJson($ar);
        } else {
            $this->flash->addMessage('success', 'Nov meni je uspešno dodat.');
            $modelMenija = new Meni();
            $modelMenija->insert($data);
            $id_menija = $modelMenija->lastId();
            $meni = $modelMenija->find($id_menija);
            $this->log(Logger::DODAVANJE, $meni, 'naziv');
            $meniji = $modelMenija->all();
            $ar['meniji'] = $meniji;
            $ar['id_menija'] = $id_menija;

            return $response->withJson($ar);
        }
    }
}

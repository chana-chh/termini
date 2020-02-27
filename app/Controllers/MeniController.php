<?php

namespace App\Controllers;

use App\Models\Meni;
use App\Models\StavkaMenija;

class MeniController extends Controller
{
    public function getMeni($request, $response)
    {
        $model = new Meni();
        $meniji = $model->paginate($this->page());

        $this->render($response, 'meni/lista.twig', compact('meniji'));
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

        $cena = (int) $data['cena'];

        if (empty($data['naziv']) &&
            empty($data['napomena']) &&
            $cena === 0) {
            return $response->withRedirect($this->router->pathFor('meni'));
        }

        $data['naziv'] = str_replace('%', '', $data['naziv']);
        $data['napomena'] = str_replace('%', '', $data['napomena']);

        $naziv = '%' . filter_var($data['naziv'], FILTER_SANITIZE_STRING) . '%';
        $napomena = '%' . filter_var($data['napomena'], FILTER_SANITIZE_STRING) . '%';

        $where = " WHERE ";
        $params = [];

        if (!empty($data['naziv'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "naziv LIKE :naziv";
            $params[':naziv'] = $naziv;
        }

        if (!empty($data['napomena'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "napomena LIKE :napomena";
            $params[':napomena'] = $napomena;
        }

        if ($cena !== 0) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "cena = :cena";
            $params[':cena'] = $cena;
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Meni();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY id;";
        $meniji = $model->paginate($this->page(), 'page', $sql, $params);

        $this->render($response, 'meni/lista.twig', compact('meniji', 'data'));
    }

    public function getMeniDodavanje($request, $response)
    {
        $modelMeni = new Meni();
        $stavke = new StavkaMenija();
        $standard = explode(',', $modelMeni->find(1)->stavke);
        $kategorije = $stavke->sveKategorije();

        $this->render($response, 'meni/dodavanje.twig', compact('kategorije', 'stavke', 'standard'));
    }

    public function postMeniDodavanje($request, $response)
    {
        $data = $this->data();

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 'meniji.naziv'
            ],
            'cena' => [
                'required' => true,
                'min' => 0,
            ],
            'odabrane_stavke' => [
                'required' => true,
            ],
        ];

        $data['stavke'] = implode(',', $data['odabrane_stavke']);
        unset($data['odabrane_stavke']);

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja menija.');
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('success', 'Nov meni je uspešno dodat.');
            $model = new Meni();
            $model->insert($data);
            $meni = $model->find($model->lastId());
            $this->log($this::DODAVANJE, $meni, 'naziv');
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function postMeniBrisanje($request, $response)
    {
        $id = $this->dataId('idBrisanje');
        $modelMenija = new Meni();
        $meni = $modelMenija->find($id);
        $success = $modelMenija->deleteOne($id);

        if ($success) {
            $this->flash->addMessage('success', "Meni je uspešno obrisan.");
            $this->log($this::BRISANJE, $meni, 'naziv', $meni);
            return $response->withRedirect($this->router->pathFor('meni'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja menija.");
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function getMeniDetalj($request, $response, $args)
    {
        $id = (int) $args['id'];
        $modelMeni = new Meni();
        $meni = $modelMeni->find($id);
        $stavke = new StavkaMenija();
        $kategorije = $stavke->sveKategorije();

        $this->render($response, 'meni/detalj.twig', compact('meni', 'kategorije'));
    }

    public function getMeniIzmena($request, $response, $args)
    {
        $id = (int) $args['id'];
        $modelMeni = new Meni();
        $meni = $modelMeni->find($id);
        $stavke = new StavkaMenija();
        $odabrano = explode(',', $meni->stavke);
        $kategorije = $stavke->sveKategorije();

        $this->render($response, 'meni/izmena.twig', compact('meni', 'kategorije', 'stavke', 'odabrano'));
    }

    public function postMeniIzmena($request, $response)
    {
        $data = $this->data('id');
        $id = $this->dataId();

        $validation_rules = [
            'naziv' => [
                'required' => true,
                'maxlen' => 50,
                'unique' => 'meniji.naziv#id:'.$id,
            ],
            'cena' => [
                'required' => true,
                'min' => 0,
            ],
            'odabrane_stavke' => [
                'required' => true,
            ],
        ];

        $data['stavke'] = implode(',', $data['odabrane_stavke']);
        unset($data['odabrane_stavke']);

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene menija.');
            return $response->withRedirect($this->router->pathFor('meni.izmena.get', ['id' => $id]));
        } else {
            $this->flash->addMessage('success', 'Meni je uspešno izmenjen.');
            $model = new Meni();
            $stari = $model->find($id);
            $model->update($data, $id);
            $meni = $model->find($id);
            $this->log($this::IZMENA, $meni, 'naziv', $stari);
            return $response->withRedirect($this->router->pathFor('meni'));
        }
    }

    public function ajaxMeni($request, $response)
    {
        $data = $this->data();
        $naziv = $data['nazivMenija'];
        $cena = $data['cenaMenija'];
        $napomena = $data['napomenaMenija'];
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
                'required' => true,
                'min' => 0,
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

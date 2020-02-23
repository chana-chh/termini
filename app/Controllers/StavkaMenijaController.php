<?php

namespace App\Controllers;

use App\Models\StavkaMenija;

class StavkaMenijaController extends Controller
{
    public function getStavkaMenija($request, $response)
    {
        $model = new StavkaMenija();
        $stavke = $model->paginate($this->page(), 'page', "SELECT * FROM stavke_menija ORDER BY kategorija;");

        $this->render($response, 'stavka_menija/lista.twig', compact('stavke'));
    }

    public function postStavkaMenijaPretraga($request, $response)
    {
        $_SESSION['DATA_STAVKA_MENIJA_PRETRAGA'] = $request->getParams();
        return $response->withRedirect($this->router->pathFor('stavke_menija.pretraga'));
    }

    public function getStavkaMenijaPretraga($request, $response)
    {
        $data = $_SESSION['DATA_STAVKA_MENIJA_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        $cena = (int) $data['cenaf'];

        if (empty($data['nazivf']) &&
            $cena === 0 &&
            empty($data['kategorija'])) {
            return $response->withRedirect($this->router->pathFor('stavke_menija'));
        }

        $data['nazivf'] = str_replace('%', '', $data['nazivf']);

        $naziv = '%' . filter_var($data['nazivf'], FILTER_SANITIZE_STRING) . '%';

        $where = " WHERE ";
        $params = [];

        if (!empty($data['nazivf'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "naziv LIKE :naziv";
            $params[':naziv'] = $naziv;
        }

        if ($cena !== 0) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "cena = :cena";
            $params[':cena'] = $cena;
        }

        if (!empty($data['kategorijaf'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "kategorija = :kategorija";
            $params[':kategorija'] = $data['kategorijaf'];
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new StavkaMenija();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY naziv;";
        $stavke = $model->paginate($this->page(), 'page', $sql, $params);

        $this->render($response, 'stavka_menija/lista.twig', compact('stavke', 'data'));
    }

    public function postStavkaMenijaDodavanje($request, $response)
    {
        $data = $this->data();

        $validation_rules = [
                'naziv' => [
                    'required' => true,
                    'maxlen' => 150,
                    'unique' => 'stavke_menija.naziv'
                ],
                'cena' => [
                    'required' => true,
                    'min' => 0,
                ],
                'kategorija' => [
                    'required' => true
                ]
            ];

        $data['korisnik_id'] = $this->auth->user()->id;

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja stavke menija.');
            return $response->withRedirect($this->router->pathFor('stavke_menija'));
        } else {
            $this->flash->addMessage('success', 'Nova stavka menija je uspešno dodata.');
            $model = new StavkaMenija();
            $model->insert($data);
            $stavka = $model->find($model->lastId());
            $this->log($this::DODAVANJE, $stavka, ['naziv', 'kategorija']);
            return $response->withRedirect($this->router->pathFor('stavke_menija'));
        }
    }


        public function postStavkaMenijaBrisanje($request, $response)
        {
            $id = $this->dataId('idBrisanje');
            $model = new StavkaMenija();
            $stavka = $model->find($id);
            $success = $model->deleteOne($id);

            if ($success) {
                $this->flash->addMessage('success', "Stavka menija je uspešno obrisana.");
                $this->log($this::BRISANJE, $stavka, 'naziv');
                return $response->withRedirect($this->router->pathFor('stavke_menija'));
            } else {
                $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja stavke menija.");
                return $response->withRedirect($this->router->pathFor('stavke_menija'));
            }
        }
/*
        public function postKomitentiDetalj($request, $response)
        {
            $data = $request->getParams();
            $cName = $this->csrf->getTokenName();
            $cValue = $this->csrf->getTokenValue();

            //Zli enum
            $kategorijaA = (object)[];
            $kategorijaA->vrednost = "Muzika";
            $kategorijaA->naziv = "Muzika";

            $kategorijaB = (object)[];
            $kategorijaB->vrednost = "Fotograf";
            $kategorijaB->naziv = "Fotograf";

            $kategorijaC = (object)[];
            $kategorijaC->vrednost = "Torta";
            $kategorijaC->naziv = "Torta";

            $kategorijaD = (object)[];
            $kategorijaD->vrednost = "Dekoracija";
            $kategorijaD->naziv = "Dekoracija";

            $kategorijaE = (object)[];
            $kategorijaE->vrednost = "Kokteli";
            $kategorijaE->naziv = "Kokteli";

            $kategorijaF = (object)[];
            $kategorijaF->vrednost = "Slatki sto";
            $kategorijaF->naziv = "Slatki sto";

            $kategorijaG = (object)[];
            $kategorijaG->vrednost = "Voćni sto";
            $kategorijaG->naziv = "Voćni sto";

            $kategorijaH = (object)[];
            $kategorijaH->vrednost = "Trubači";
            $kategorijaH->naziv = "Trubači";

            $kategorijaI = (object)[];
            $kategorijaI->vrednost = "Animator";
            $kategorijaI->naziv = "Animator";

            $kategorije = [$kategorijaA, $kategorijaB, $kategorijaC, $kategorijaD, $kategorijaE, $kategorijaF, $kategorijaG, $kategorijaH, $kategorijaI];

            $id = $data['id'];
            $modelKomitent = new Komitent();
            // $kategorije = $modelKomitent->enumOrSetList('kategorija');
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
                "kategorija" => $data['kategorijaModal']
            ];

            $validation_rules = [
                'naziv' => [
                    'required' => true,
                    'maxlen' => 255,
                    'unique' => 'komintenti.naziv#id:' . $id,
                ],
                'kategorija' => [
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
        */
}

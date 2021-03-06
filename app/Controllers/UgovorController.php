<?php

namespace App\Controllers;

use App\Models\Ugovor;
use App\Models\Termin;
use App\Models\Meni;
use App\Models\Uplata;
use App\Models\Soba;
use App\Models\SobaUgovor;
use App\Models\MeniUgovor;
use App\Models\Komitent;
use App\Models\Kategorija;
use App\Models\DodatnaUsluga;

class UgovorController extends Controller
{
    public function getUgovor($request, $response)
    {
        $model = new Ugovor();
        $ugovori = $model->paginate($this->page(), 'page', "SELECT * FROM ugovori ORDER BY datum DESC;");

        $this->render($response, 'ugovor/lista.twig', compact('ugovori'));
    }

    public function postUgovorPretraga($request, $response)
    {
        $_SESSION['DATA_UGOVORI_PRETRAGA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('ugovori.pretraga'));
    }

    public function getUgovorPretraga($request, $response)
    {
        $data = $_SESSION['DATA_UGOVORI_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        if (empty($data['prezime']) &&
            empty($data['ime']) &&
            empty($data['naziv_firme']) &&
            empty($data['telefon']) &&
            empty($data['email']) &&
            empty($data['napomena']) &&
            empty($data['broj_ugovora']) &&
            empty($data['datum_1']) &&
            empty($data['datum_2']) &&
            empty($data['korisnik_id'])) {
            $this->getUgovor($request, $response);
        }

        $data['naziv_firme'] = str_replace('%', '', $data['naziv_firme']);
        $data['prezime'] = str_replace('%', '', $data['prezime']);
        $data['ime'] = str_replace('%', '', $data['ime']);
        $data['telefon'] = str_replace('%', '', $data['telefon']);
        $data['email'] = str_replace('%', '', $data['email']);
        $data['napomena'] = str_replace('%', '', $data['napomena']);
        $data['broj_ugovora'] = str_replace('%', '', $data['broj_ugovora']);

        $naziv_firme = '%' . filter_var($data['naziv_firme'], FILTER_SANITIZE_STRING) . '%';
        $prezime = '%' . filter_var($data['prezime'], FILTER_SANITIZE_STRING) . '%';
        $ime = '%' . filter_var($data['ime'], FILTER_SANITIZE_STRING) . '%';
        $telefon = '%' . filter_var($data['telefon'], FILTER_SANITIZE_STRING) . '%';
        $email = '%' . filter_var($data['email'], FILTER_SANITIZE_STRING) . '%';
        $napomena = '%' . filter_var($data['napomena'], FILTER_SANITIZE_STRING) . '%';
        $broj_ugovora = '%' . filter_var($data['broj_ugovora'], FILTER_SANITIZE_STRING) . '%';

        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $where = " WHERE ";
        $params = [];

        if (!empty($data['prezime'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "prezime LIKE :prezime";
            $params[':prezime'] = $prezime;
        }

        if (!empty($data['naziv_firme'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "naziv_firme LIKE :naziv_firme";
            $params[':naziv_firme'] = $naziv_firme;
        }

        if (!empty($data['ime'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "ime LIKE :ime";
            $params[':ime'] = $ime;
        }

        if (!empty($data['telefon'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "telefon LIKE :telefon";
            $params[':telefon'] = $telefon;
        }

        if (!empty($data['email'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "email LIKE :email";
            $params[':email'] = $email;
        }

        if (!empty($data['napomena'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "napomena LIKE :napomena";
            $params[':napomena'] = $napomena;
        }

        if (!empty($data['broj_ugovora'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "broj_ugovora LIKE :broj_ugovora";
            $params[':broj_ugovora'] = $broj_ugovora;
        }

        if (!empty($data['datum_1']) && empty($data['datum_2'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "DATE(datum) = :datum_1";
            $params[':datum_1'] = $data['datum_1'];
        }

        if (!empty($data['datum_1']) && !empty($data['datum_2'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "DATE(datum) >= :datum_1 AND DATE(datum) <= :datum_2 ";
            $params[':datum_1'] = $data['datum_1'];
            $params[':datum_2'] = $data['datum_2'];
        }

        if (!empty($data['korisnik_id'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "korisnik_id = :korisnik_id";
            $params[':korisnik_id'] = $data['korisnik_id'];
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Ugovor();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY datum DESC;";
        $ugovori = $model->paginate($page, 'page', $sql, $params);

        $this->render($response, 'ugovor/lista.twig', compact('ugovori', 'data'));
    }

    public function getUgovorDodavanje($request, $response, $args)
    {
        $termin_id = (int) $args['termin_id'];
        $model_termin = new Termin();
        $termin = $model_termin->find($termin_id);

        $komitenti = new Komitent();

        if (!$termin->multiUgovori() && !empty($termin->ugovori())) {
            $this->flash->addMessage('warning', "Nije dozvoljeno dodavanje više od jednog ugovora.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        $this->render($response, 'ugovor/dodavanje.twig', compact('termin', 'komitenti'));
    }

    public function postUgovorDodavanje($request, $response)
    {
        $data = $this->data();

        $validation_rules = [
            'termin_id' => ['required' => true,],
            'prezime' => ['required' => true,],
            'ime' => ['required' => true,],
            'broj_mesta' => ['required' => true,],
            'broj_stolova' => ['required' => true,],
            'broj_mesta_po_stolu' => ['required' => true,],
            'fizicko_pravno' => ['required' => true,]
        ];

        $model_ugovor = new Ugovor();
        if (trim($data['broj_ugovora']) != "") {
            $sql = "SELECT COUNT(*) AS broj FROM ugovori WHERE broj_ugovora = :br;";
            $params = [':br' => trim($data['broj_ugovora'])];
            $br = (int) $model_ugovor->fetch($sql, $params)[0]->broj;
            if ($br > 0) {
                $this->validator->addError('broj_ugovora', 'U bazi već postoji [Broj ugovora] sa istom vrednošću');
            }
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja ugovora.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.ugovor', ['termin_id' => (int) $data['termin_id']]));
        } else {
            $data['korisnik_id'] = $this->auth->user()->id;
            $model_ugovor->insert($data);
            $ugovor = $model_ugovor->find($model_ugovor->lastId());
            $this->log($this::DODAVANJE, $ugovor, 'broj_ugovora');

            $model_termin = new Termin();
            $termin = $model_termin->find($ugovor->termin_id);
            if ($termin->zakljucavanje()) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            } else {
                $model_termin->update(['zauzet' => 0], $termin->id);
            }

            $this->flash->addMessage('success', 'Novi ugovor je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $ugovor->id]));
        }
    }

    public function getUgovorIzmena($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $model_termin = new Termin();
        $termin = $model_termin->find($ugovor->termin_id);
        $komitenti = new Komitent();

        $this->render($response, 'ugovor/izmena.twig', compact('ugovor', 'termin', 'komitenti'));
    }

    public function postUgovorIzmena($request, $response)
    {
        $data = $this->data('id');
        $id = $this->dataId();

        $validation_rules = [
            'termin_id' => ['required' => true,],
            'prezime' => ['required' => true,],
            'ime' => ['required' => true,],
            'broj_mesta' => ['required' => true,],
            'broj_stolova' => ['required' => true,],
            'broj_mesta_po_stolu' => ['required' => true,],
            'iznos_meni' => ['required' => true,],
            'iznos_sobe' => ['required' => true,],
            'fizicko_pravno' => ['required' => true,]
        ];

        // provera broja ugovora unique
        $model_ugovor = new Ugovor();
        if (trim($data['broj_ugovora']) != "") {
            $sql = "SELECT COUNT(*) AS broj FROM ugovori WHERE broj_ugovora = :br AND id != :id;";
            $params = [':br' => trim($data['broj_ugovora']), ':id' => $id];
            $br = (int) $model_ugovor->fetch($sql, $params)[0]->broj;
            if ($br > 0) {
                $this->validator->addError('broj_ugovora', 'U bazi već postoji [Broj ugovora] sa istom vrednošću');
            }
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene ugovora.');
            return $response->withRedirect($this->router->pathFor('termin.ugovor.izmena.get', ['id' => $id]));
        } else {
            $model = new Ugovor();
            $ugovor = $model->find($id);
            $model->update($data, $id);
            $ugovor1 = $model->find($id);
            $model_termin = new Termin();
            $termin = $model_termin->find($ugovor->termin_id);
            if ($termin->zakljucavanje()) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            } else {
                $model_termin->update(['zauzet' => 0], $termin->id);
            }
            $this->log($this::IZMENA, $ugovor1, 'broj_ugovora', $ugovor);
            $this->flash->addMessage('success', 'Ugovor je uspešno izmenjen.');
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => (int) $data['termin_id']]));
        }
    }

    public function postUgovorBrisanje($request, $response)
    {
        $id = (int) $request->getParam('idBrisanje');
        $model = new Ugovor();
        $ugovor = $model->find($id);
        $model_termin = new Termin();
        $termin = $model_termin->find($ugovor->termin_id);

        if (count($ugovor->uplate()) > 0) {
            $this->flash->addMessage('danger', "Postoje uplate vezane za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve uplate vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if (count($ugovor->dokumenti()) > 0) {
            $this->flash->addMessage('danger', "Postoje dokumenti vezani za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve dokumente vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if (count($ugovor->sobaUgovor()) > 0) {
            $this->flash->addMessage('danger', "Postoje sobe vezani za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve sobe vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if (count($ugovor->meniUgovor()) > 0) {
            $this->flash->addMessage('danger', "Postoje meniji vezani za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve menije vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if (count($ugovor->dodatneUsluge()) > 0) {
            $this->flash->addMessage('danger', "Postoje dodatne usluge vezane za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve dodatne usluge vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if (count($ugovor->podsetnici()) > 0) {
            $this->flash->addMessage('danger', "Postoje podsetnici vezani za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve podsetnike vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if ($termin->zakljucavanje()) {
            $model_termin->update(['zauzet' => 1], $termin->id);
        } else {
            $model_termin->update(['zauzet' => 0], $termin->id);
        }

        $success = $model->deleteOne($id);

        if ($success) {
            $this->log($this::BRISANJE, $ugovor, 'broj_ugovora', $ugovor);
            $this->flash->addMessage('success', "Ugovor je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja ugovora.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }
    }

    public function getUgovorDetalj($request, $response, $args) // detalj ugovora sa dokumentima
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);

        $this->render($response, 'ugovor/detalj.twig', compact('ugovor'));
    }

    public function getUgovorUplateDetalj($request, $response, $args) // detalj ugovora sa uplatama
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $model_uplata = new Uplata();
        $nacin = $model_uplata->enumOrSetList('nacin_placanja');
        $svrha = $model_uplata->enumOrSetList('svrha_placanja');

        $this->render($response, 'ugovor/uplate.twig', compact('ugovor', 'nacin', 'svrha'));
    }


    // Sobe !!!
    // public function getUgovorSobe($request, $response, $args)
    // {
    //     $id = (int) $args['id'];
    //     $model_ugovor = new Ugovor();
    //     $ugovor = $model_ugovor->find($id);

    //     $model_sobe = new Soba();
    //     $sobe = $model_sobe->all();

    //     $this->render($response, 'ugovor/sobe.twig', compact('ugovor', 'sobe'));
    // }

    // public function postSobaUgovorDodavanje($request, $response)
    // {
    //     $data = $this->data();

    //     $id_ugovora = $data['ugovor_id'];

    //     $validation_rules = [
    //         'ugovor_id' => [
    //             'required' => true,
    //         ],
    //         'soba_id' => [
    //             'required' => true
    //         ],
    //         'komada' => [
    //             'required' => true
    //         ],
    //         'popust' => [
    //             'required' => true,
    //         ]
    //     ];

    //     $this->validator->validate($data, $validation_rules);

    //     if ($this->validator->hasErrors()) {
    //         $this->flash->addMessage('danger', 'Došlo je do greške prilikom vezivanja sobe za ugovor.');
    //         return $response->withRedirect($this->router->pathFor('sale'));
    //     } else {
    //         $this->flash->addMessage('success', 'Soba je uspešno vezana za ugovor.');
    //         $model = new SobaUgovor();
    //         $model->insert($data);

    //         $id_sale = $model->lastId();
    //         $sala = $model->find($id_sale);
    //         $this->log($this::DODAVANJE, $sala, 'opis');
    //         return $response->withRedirect($this->router->pathFor('ugovor.sobe.lista', ['id' => $$id_ugovora]));
    //     }
    // }

    // Dodatne usluge NOVO!!!
    public function postDodatneUgovorDodavanje($request, $response)
    {
        $data = $this->data();
        $status = isset($data['status']) ? 1 : 0;
        $data['status'] = $status;
        $data['ugovor_id'] = $data['ugovor_id_dodatna'];
        $id_ugovora = $data['ugovor_id'];
        unset($data['ugovor_id_dodatna']);
        unset($data['kategorija_id']);
        $data['korisnik_id'] = $this->auth->user()->id;

        $validation_rules = [
            'ugovor_id' => [
                'required' => true,
            ],
            'komitent_id' => [
                'required' => true
            ],
            'korisnik_id' => [
                'required' => true
            ],
            'status' => [
                'required' => true
            ],
            'cena' => [
                'required' => true,
            ]
        ];
        $model = new DodatnaUsluga();
        $sql = "SELECT COUNT(*) AS broj FROM dodatne_usluge WHERE ugovor_id = :u_id AND komitent_id = :k_id;";
        $params = [':u_id' => $id_ugovora, ':k_id' => $data['komitent_id']];
        $br = (int) $model->fetch($sql, $params)[0]->broj;
        if ($br > 0) {
            $this->validator->addError('komitent_id', 'U ugovoru već postoji odabrani komitent.');
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom vezivanja dodatne usluge za ugovor.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $data['ugovor_id']]));
        } else {
            $this->flash->addMessage('success', 'Dodatna usluga je uspešno vezana za ugovor.');
            $model->insert($data);

            $id_sale = $model->lastId();
            $dodatna = $model->find($id_sale);
            $this->log($this::DODAVANJE, $dodatna, 'opis');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $data['ugovor_id']]));
        }
    }

    public function postDopunaDodatneBrisanje($request, $response)
    {
        $id_dodatne = (int)$request->getParam('idBrisanjeDodatne');
        $id_ugovor = (int)$request->getParam('ugovor_iddodatne');
        $model = new DodatnaUsluga();
        $dodatna_ugovor = $model->find($id_dodatne);
        $success = $model->deleteOne($id_dodatne);

        if ($success) {
            $this->flash->addMessage('success', "Dodatna usluga je uspešno obrisana.");
            $this->log($this::BRISANJE, $dodatna_ugovor, 'opis', $dodatna_ugovor);
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id_ugovor]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja menija.");
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id_ugovor]));
        }
    }

    public function getUgovorDopuna($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $model_meni = new Meni();
        $model_soba = new Soba();
        $model_kategorija = new Kategorija();
        $model_komitent = new Komitent();
        $meniji = $model_meni->all();
        $sobe = $model_soba->all();
        $kategorije = $model_kategorija->all();
        $komitenti = $model_komitent->all();

        $this->render($response, 'ugovor/dopuna.twig', compact('ugovor', 'meniji', 'sobe', 'kategorije', 'komitenti'));
    }

    public function postUgovorDopunaMeni($request, $response)
    {
        $data = $this->data();
        $data['ugovor_id'] = $data['ugovor_id_meni'];
        $data['cena_sa_popustom'] = (float) $data['cena'] - (float) $data['popust'];
        $data['iznos'] = $data['cena_sa_popustom'] * (int) $data['komada'];
        unset($data['ugovor_id_meni']);
        unset($data['cena']);

        $validation_rules = [
            'ugovor_id' => ['required' => true,],
            'meni_id' => ['required' => true,],
            'komada' => ['required' => true,],
            'popust' => ['required' => true,],
            'cena_sa_popustom' => ['required' => true,],
            'iznos' => ['required' => true,],
        ];

        $model = new MeniUgovor();

        $sql = "SELECT COUNT(*) AS broj FROM ugovor_meni WHERE ugovor_id = :u_id AND meni_id = :m_id;";
        $params = [':u_id' => $data['ugovor_id'], ':m_id' => $data['meni_id']];
        $br = (int) $model->fetch($sql, $params)[0]->broj;
        if ($br > 0) {
            $this->validator->addError('meni_id', 'U ugovoru već postoji odabrani meni.');
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja menija na ugovor.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $data['ugovor_id']]));
        } else {
            $model->insert($data);
            $ugovor_meni = $model->find($model->lastId());
            $this->log($this::DODAVANJE, $ugovor_meni, ['ugovor_id','meni_id']);

            $model_ugovor = new Ugovor();
            $ugovor = $model_ugovor->find((int) $data['ugovor_id']);
            $data_iznos = ['iznos_meni' => $ugovor->ukupanIznosMenija()];
            $ugovor->update($data_iznos, $ugovor->id);
            if ($ugovor->ukupanBrojMenija() > $ugovor->broj_mesta) {
                $this->flash->addMessage('warning', 'Meni je uspešno dodat na ugovor, ali je broj menija veći od broja gostiju.');
            } else {
                $this->flash->addMessage('success', 'Meni je uspešno dodat na ugovor.');
            }


            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $data['ugovor_id']]));
        }
    }

    public function postDopunaMeniBrisanje($request, $response)
    {
        $id_meniugovor = (int)$request->getParam('idBrisanjeMenija');
        $id_ugovor = (int)$request->getParam('ugovor_idmeni');
        $model = new MeniUgovor();
        $meni_ugovor = $model->find($id_meniugovor);
        $success = $model->deleteOne($id_meniugovor);

        if ($success) {
            $this->flash->addMessage('success', "Meni je uspešno obrisan.");
            $this->log($this::BRISANJE, $meni_ugovor, 'komada', $meni_ugovor);
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id_ugovor]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja menija.");
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id_ugovor]));
        }
    }

    public function postUgovorDopunaSoba($request, $response)
    {
        $data = $this->data();
        $data['ugovor_id'] = $data['ugovor_id_soba'];
        $data['cena_sa_popustom'] = (float) $data['cena'] - (float) $data['popust'];
        $data['iznos'] = $data['cena_sa_popustom'] * (int) $data['komada'];
        unset($data['ugovor_id_soba']);
        unset($data['cena']);

        $validation_rules = [
            'ugovor_id' => ['required' => true,],
            'soba_id' => ['required' => true,],
            'komada' => ['required' => true,],
            'popust' => ['required' => true,],
            'cena_sa_popustom' => ['required' => true,],
            'iznos' => ['required' => true,],
        ];

        $model = new SobaUgovor();

        $sql = "SELECT COUNT(*) AS broj FROM ugovor_soba WHERE ugovor_id = :u_id AND soba_id = :s_id;";
        $params = [':u_id' => $data['ugovor_id'], ':s_id' => $data['soba_id']];
        $br = (int) $model->fetch($sql, $params)[0]->broj;
        if ($br > 0) {
            $this->validator->addError('soba_id', 'U ugovoru već postoji odabrana vrsta sobe.');
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja sobe na ugovor.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $data['ugovor_id']]));
        } else {
            $model->insert($data);
            $ugovor_soba = $model->find($model->lastId());
            $this->log($this::DODAVANJE, $ugovor_soba, ['ugovor_id','soba_id']);

            $model_ugovor = new Ugovor();
            $ugovor = $model_ugovor->find((int) $data['ugovor_id']);
            $data_iznos = ['iznos_sobe' => $ugovor->ukupanIznosSoba()];
            $ugovor->update($data_iznos, $ugovor->id);

            $this->flash->addMessage('success', 'Soba je uspešno dodata na ugovor.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => (int) $data['ugovor_id']]));
        }
    }

    public function postDopunaSobaBrisanje($request, $response)
    {
        $id_sobaugovor = (int)$request->getParam('idBrisanjeSobe');
        $id_ugovor = (int)$request->getParam('ugovor_idmeni');
        $model = new SobaUgovor();
        $soba_ugovor = $model->find($id_sobaugovor);
        $success = $model->deleteOne($id_sobaugovor);

        if ($success) {
            $this->flash->addMessage('success', "Soba je uspešno obrisana.");
            $this->log($this::BRISANJE, $soba_ugovor, 'komada', $soba_ugovor);
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id_ugovor]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja sobe.");
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id_ugovor]));
        }
    }

    public function postUgovorDopunaAneks($request, $response)
    {
        $data = $this->data('ugovor_id_aneks');
        $id = $this->dataId('ugovor_id_aneks');

        $validation_rules = [
            'aneks_broj_mesta' => ['required' => true,],
            'aneks_iznos_meni' => ['required' => true,],
            'aneks_iznos_dodatno' => ['required' => true,],
            'aneks_iznos_sobe' => ['required' => true,],
        ];

        $model = new Ugovor();

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja aneksa ugovora.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id]));
        } else {
            $stari = $model->find($id);
            $model->update($data, $id);
            $novi = $model->find($id);
            $this->log($this::IZMENA, $novi, ['broj_ugovora'], $stari);

            $this->flash->addMessage('success', 'Aneks je uspešno dodat na ugovor.');
            return $response->withRedirect($this->router->pathFor('ugovor.dopuna.get', ['id' => $id]));
        }
    }
}

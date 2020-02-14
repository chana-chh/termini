<?php

namespace App\Controllers;

use App\Models\Ugovor;
use App\Models\Termin;
use App\Models\Meni;
use App\Models\Uplata;
use App\Classes\Logger;

class UgovorController extends Controller
{
    public function getUgovor($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Ugovor();
        $ugovori = $model->paginate($page, 'page', "SELECT * FROM ugovori ORDER BY datum DESC;");

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

        $model_meni = new Meni();
        $meniji = $model_meni->all();

        // provera multi ugovor
        if (!$termin->multiUgovori() && !empty($termin->ugovori())) {
            $this->flash->addMessage('warning', "Nije dozvoljeno dodavanje više od jednog ugovora.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        $this->render($response, 'ugovor/dodavanje.twig', compact('termin', 'meniji'));
    }

    public function postUgovorDodavanje($request, $response)
    {
        $data = $request->getParams();

        unset($data['csrf_name']);
        unset($data['csrf_value']);
        unset($data['cekiraj_sve']);

        $data['fizicko_pravno'] = isset($data['fizicko_pravno']) ? 1 : 0;
        $data['muzika_chk'] = isset($data['muzika_chk']) ? 1 : 0;
        $data['fotograf_chk'] = isset($data['fotograf_chk']) ? 1 : 0;
        $data['torta_chk'] = isset($data['torta_chk']) ? 1 : 0;
        $data['dekoracija_chk'] = isset($data['dekoracija_chk']) ? 1 : 0;
        $data['kokteli_chk'] = isset($data['kokteli_chk']) ? 1 : 0;
        $data['slatki_sto_chk'] = isset($data['slatki_sto_chk']) ? 1 : 0;
        $data['vocni_sto_chk'] = isset($data['vocni_sto_chk']) ? 1 : 0;

        $validation_rules = [
            'termin_id' => ['required' => true,],
            'meni_id' => ['required' => true,],
            'prezime' => ['required' => true,],
            'ime' => ['required' => true,],
            'broj_mesta' => ['required' => true,],
            'broj_stolova' => ['required' => true,],
            'broj_mesta_po_stolu' => ['required' => true,],
            'iznos' => ['required' => true,],
            'prosek_godina' => ['required' => true,],
            'muzika_chk' => ['required' => true,],
            'fizicko_pravno' => ['required' => true,],
            'fotograf_chk' => ['required' => true,],
            'torta_chk' => ['required' => true,],
            'dekoracija_chk' => ['required' => true,],
            'kokteli_chk' => ['required' => true,],
            'slatki_sto_chk' => ['required' => true,],
            'vocni_sto_chk' => ['required' => true,],
            'muzika_iznos' => ['required' => true,],
            'fotograf_iznos' => ['required' => true,],
            'torta_iznos' => ['required' => true,],
            'dekoracija_iznos' => ['required' => true,],
            'kokteli_iznos' => ['required' => true,],
            'slatki_sto_iznos' => ['required' => true,],
            'vocni_sto_iznos' => ['required' => true,],
            'posebni_zahtevi_iznos' => ['required' => true,]
        ];

        // provera broja ugovora
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
            $this->log(Logger::DODAVANJE, $ugovor, 'broj_ugovora');

            $model_termin = new Termin();
            $termin = $model_termin->find($ugovor->termin_id);
            if ($termin->zakljucavanje()) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            } else {
                $model_termin->update(['zauzet' => 0], $termin->id);
            }

            $this->flash->addMessage('success', 'Novi ugovor je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => (int) $data['termin_id']]));
        }
    }

    public function getUgovorIzmena($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);
        $model_termin = new Termin();
        $termin = $model_termin->find($ugovor->termin_id);

        $model_meni = new Meni();
        $meniji = $model_meni->all();

        $this->render($response, 'ugovor/izmena.twig', compact('ugovor', 'meniji', 'termin'));
    }

    public function postUgovorIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = (int) $data['id'];
        unset($data['id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $fizicko_pravno = isset($data['fizicko_pravno']) ? 1 : 0;
        $data['fizicko_pravno'] = $fizicko_pravno;
        $muzika_chk = isset($data['muzika_chk']) ? 1 : 0;
        $data['muzika_chk'] = $muzika_chk;
        $fotograf_chk = isset($data['fotograf_chk']) ? 1 : 0;
        $data['fotograf_chk'] = $fotograf_chk;
        $torta_chk = isset($data['torta_chk']) ? 1 : 0;
        $data['torta_chk'] = $torta_chk;
        $dekoracija_chk = isset($data['dekoracija_chk']) ? 1 : 0;
        $data['dekoracija_chk'] = $dekoracija_chk;
        $kokteli_chk = isset($data['kokteli_chk']) ? 1 : 0;
        $data['kokteli_chk'] = $kokteli_chk;
        $slatki_sto_chk = isset($data['slatki_sto_chk']) ? 1 : 0;
        $data['slatki_sto_chk'] = $slatki_sto_chk;
        $vocni_sto_chk = isset($data['vocni_sto_chk']) ? 1 : 0;
        $data['vocni_sto_chk'] = $vocni_sto_chk;

        $validation_rules = [
            'termin_id' => ['required' => true,],
            'meni_id' => ['required' => true,],
            'prezime' => ['required' => true,],
            'ime' => ['required' => true,],
            'broj_mesta' => ['required' => true,],
            'broj_stolova' => ['required' => true,],
            'broj_mesta_po_stolu' => ['required' => true,],
            'iznos' => ['required' => true,],
            'prosek_godina' => ['required' => true,],
            'fizicko_pravno' => ['required' => true,],
            'muzika_chk' => ['required' => true,],
            'fotograf_chk' => ['required' => true,],
            'torta_chk' => ['required' => true,],
            'dekoracija_chk' => ['required' => true,],
            'kokteli_chk' => ['required' => true,],
            'slatki_sto_chk' => ['required' => true,],
            'vocni_sto_chk' => ['required' => true,],
            'muzika_iznos' => ['required' => true,],
            'fotograf_iznos' => ['required' => true,],
            'torta_iznos' => ['required' => true,],
            'dekoracija_iznos' => ['required' => true,],
            'kokteli_iznos' => ['required' => true,],
            'slatki_sto_iznos' => ['required' => true,],
            'vocni_sto_iznos' => ['required' => true,],
            'posebni_zahtevi_iznos' => ['required' => true,]
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
            $this->log(Logger::IZMENA, $ugovor1, 'broj_ugovora', $ugovor);
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

        // provera uplata i dokumenata
        if (count($ugovor->uplate()) > 0) {
            $this->flash->addMessage('danger', "Postoje uplate vezane za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve uplate vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if (count($ugovor->dokumenti()) > 0) {
            $this->flash->addMessage('danger', "Postoje dokumenti vezani za ovaj ugovor. Da bi se obrisao ugovor nephodno je prethodno obrisati sve dokumente vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $termin->id]));
        }

        if ($termin->zakljucavanje()) {
            $model_termin->update(['zauzet' => 1], $termin->id);
        } else {
            $model_termin->update(['zauzet' => 0], $termin->id);
        }

        $success = $model->deleteOne($id);

        if ($success) {
            $this->log(Logger::BRISANJE, $ugovor, 'broj_ugovora', $ugovor);
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

        $this->render($response, 'ugovor/uplate.twig', compact('ugovor'));
    }
}

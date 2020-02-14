<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Models\Termin;
use App\Models\Ugovor;
use App\Models\Meni;

class VlasnikController extends Controller
{
    public function getKalendarVlasnik($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $model_termin = new Termin();
        $sql = "SELECT * FROM {$model_termin->getTable()} WHERE datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH);";
        $termini = $model_termin->fetch($sql);
        $data = [];

        foreach ($termini as $termin) {
            $ikonica = $termin->statusIkonica();
            $data[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
                "advancedTitle" => 'Ovaj događaj je ' . $termin->tip()->tip . ' i održaće se u ' . $termin->sala()->naziv . '. Trenutni broj zvanica je ' . $termin->popunjenaMesta() . ', a cena termina je: ' . number_format($termin->cenaTermina(), 2, ',', '.') . ' dinara',
                "advancedDetalj" => $termin->tip()->tip . '.<br> Broj zvanica: ' . $termin->popunjenaMesta() . '<br> Cena: ' . number_format($termin->cenaTermina(), 2, ',', '.'),
            ];
        }

        $data = json_encode($data);

        $this->render($response, 'kalendar_vlasnik.twig', compact('data'));
    }

    public function getTerminVlasnik($request, $response, $args)
    {
        $id = $args['id'];

        if ($id) {
            $model_termin = new Termin();
            $termin = $model_termin->find($id);

            $this->render($response, 'termin/detalj_vlasnik.twig', compact('termin'));
        } else {
            return $response->withRedirect($this->router->pathFor('vlasnik.kalendar'));
        }
    }

    public function getUgovorVlasnik($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);

        $this->render($response, 'ugovor/detalj_vlasnik.twig', compact('ugovor'));
    }

    public function getUplateVlasnik($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_ugovor = new Ugovor();
        $ugovor = $model_ugovor->find($id);

        $this->render($response, 'ugovor/uplate_vlasnik.twig', compact('ugovor'));
    }

    public function getUgovorListaVlasnik($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Ugovor();
        $ugovori = $model->paginate($page, 'page', "SELECT * FROM ugovori ORDER BY datum DESC;");

        $this->render($response, 'ugovor/lista_vlasnik.twig', compact('ugovori'));
    }

    public function postUgovorPretragaVlasnik($request, $response)
    {
        $_SESSION['DATA_UGOVORI_PRETRAGA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('vlasnik.ugovori.pretraga'));
    }

    public function getUgovorPretragaVlasnik($request, $response)
    {
        $data = $_SESSION['DATA_UGOVORI_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        if (empty($data['prezime']) &&
            empty($data['ime']) &&
            empty($data['telefon']) &&
            empty($data['email']) &&
            empty($data['napomena']) &&
            empty($data['broj_ugovora']) &&
            empty($data['datum_1']) &&
            empty($data['datum_2']) &&
            empty($data['korisnik_id'])) {
            $this->getUgovor($request, $response);
        }

        $data['prezime'] = str_replace('%', '', $data['prezime']);
        $data['ime'] = str_replace('%', '', $data['ime']);
        $data['telefon'] = str_replace('%', '', $data['telefon']);
        $data['email'] = str_replace('%', '', $data['email']);
        $data['napomena'] = str_replace('%', '', $data['napomena']);
        $data['broj_ugovora'] = str_replace('%', '', $data['broj_ugovora']);

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

        $this->render($response, 'ugovor/lista_vlasnik.twig', compact('ugovori', 'data'));
    }
}

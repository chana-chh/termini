<?php

namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\Logger;
use App\Models\Termin;

class HomeController extends Controller
{
    public function getHome($request, $response)
    {
        $model_termin = new Termin();
        $sql = "SELECT * FROM {$model_termin->getTable()} WHERE datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND vaznost <= DATE_ADD(CURDATE(), INTERVAL 5 DAY);";
        $termini = $model_termin->fetch($sql);

        $this->render($response, 'home.twig', compact('termini'));
    }

    public function getKalendar($request, $response)
    {
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

        $this->render($response, 'kalendar.twig', compact('data'));
    }

    public function postKalendarPretraga($request, $response)
    {
        $_SESSION['DATA_KALENDAR_PRETRAGA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('kalendar.filter'));
    }

    public function getKalendarPretraga($request, $response)
    {
        $data = $_SESSION['DATA_KALENDAR_PRETRAGA'];
        array_shift($data);
        array_shift($data);

        if (empty($data['upit'])) {
            $this->getKalendar($request, $response);
        }


        $where = " WHERE ";
        $params = [];

        if (!empty($data['sala_id'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "sala_id = :sala_id";
            $params[':sala_id'] = $data['sala_id'];
        }

        if (!empty($data['tip_dogadjaja_id'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "tip_dogadjaja_id = :tip_dogadjaja_id";
            $params[':tip_dogadjaja_id'] = $upit;
        }


        $where = $where === " WHERE " ? "" : $where;
        $model_termin = new Termin();
        $sql = "SELECT * FROM {$model_termin->getTable()}{$where} AND datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH);";
        $termini = $model_termin->fetch($sql);

        $this->render($response, 'kalendar.twig', compact('termini', 'data'));
    }
}

<?php

namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\Logger;
use App\Models\Termin;
use App\Models\Podsetnik;
use App\Models\Sala;
use App\Models\TipDogadjaja;

class HomeController extends Controller
{
    public function getHome($request, $response)
    {
        $model_termin = new Termin();
        $sql = "SELECT * FROM {$model_termin->getTable()} WHERE datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND vaznost <= DATE_ADD(CURDATE(), INTERVAL 5 DAY);";
        $termini = $model_termin->fetch($sql);

        $model_podsetnik = new Podsetnik();
        $sql = "SELECT * FROM {$model_podsetnik->getTable()} WHERE datum > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND reseno = 0;";
        $podsetnici = $model_podsetnik->fetch($sql);

        $this->render($response, 'home.twig', compact('termini', 'podsetnici'));
    }

    public function postTerminObavestenje($request, $response)
    {
        $id_termina = (int)$request->getParam('idTermina');
        $model = new Termin();
        $termin = $model->find($id_termina);

        $ugovori = $termin->ugovori();
        $broj_ugovora = count($ugovori);
        if ($broj_ugovora > 0) {
            $d = date('d.m.Y', strtotime($termin->datum));
            $p = date('H:i', strtotime($termin->pocetak));
            $k = date('H:i', strtotime($termin->kraj));

            $telo = $this->renderPartial('mail/vaznost_termina.twig', compact('termin'));
            $broj_poslatih = 0;
            $nisu_prosli = [];
            foreach ($ugovori as $ugovor) {
                if ($ugovor->email != '') {
                    $adresa = trim($ugovor->email);
                    $adresa = filter_var($adresa, FILTER_SANITIZE_EMAIL);
                    $ime = $ugovor->punoIme();
                    if (filter_var($adresa, FILTER_VALIDATE_EMAIL)) {
                    //     $poslato = $this->mailer->sendMail(
                    //     [['email' => $adresa, 'name' => $ime]],
                    //     "Istek rezervacije za termin u sali {$termin->sala()->naziv} za {$d}. godine",
                    //     $telo
                    // );
                    }
                }
                if ($poslato) {
                    $broj_poslatih++;
                } else {
                    array_push($nisu_prosli, $ime);
                }
            }
            $broj_ugovora = 1;
            $broj_poslatih = 1;
            if ($broj_poslatih == $broj_ugovora) {
                $model->update(['obavestenje' => "Obaveštenje je uspešno poslato na dan - ".date('d-m-Y')], $id_termina);
                $this->flash->addMessage('success', "Obaveštenje je uspešno poslato.");
                return $response->withRedirect($this->router->pathFor('pocetna'));
            } elseif ($broj_poslatih == 0) {
                $this->flash->addMessage('danger', "Došlo je do greške prilikom slanja obaveštenja.");
                return $response->withRedirect($this->router->pathFor('pocetna'));
            } else {
                $comma_separated = implode(",", $nisu_prosli);
                $model->update(['obavestenje' => "Samo je deo obaveštenja uspešno prosleđen. Problem se javio kod: ".$comma_separated], $id_termina);
                $this->flash->addMessage('warning', "Samo je deo obaveštenja uspešno prosleđen. Problem se javio kod: ".$comma_separated);
                return $response->withRedirect($this->router->pathFor('pocetna'));
            }
        } else {
            $this->flash->addMessage('danger', "E-mail adrese na koje treba poslati obaveštenje nisu dodate ili nije definisan ugovor za termin.");
            return $response->withRedirect($this->router->pathFor('pocetna'));
        }
    }

    public function postUkloniVaznost($request, $response)
    {
        $id_termina = (int)$request->getParam('idTerminaVaznost');
        $model = new Termin();
        $izmenjeno = $model->update(['vaznost' => null], $id_termina);

        if ($izmenjeno) {
            $this->flash->addMessage('success', "Važnost rezervacije termina je uspešno uklonjena.");
            return $response->withRedirect($this->router->pathFor('pocetna'));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom uklanjanja važnosti rezervacije.");
            return $response->withRedirect($this->router->pathFor('pocetna'));
        }
    }

    public function getKalendar($request, $response)
    {
        $model_termin = new Termin();
        $sql = "SELECT * FROM {$model_termin->getTable()} WHERE datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH);";
        $termini = $model_termin->fetch($sql);
        $data_kal = [];

        $model_sala = new Sala();
        $sale = $model_sala->all();
        $model_tip = new TipDogadjaja();
        $tipovi = $model_tip->all();

        foreach ($termini as $termin) {
            $ikonica = $termin->statusIkonica();
            $bojica = $termin->statusBoja();
            $data_kal[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
                "advancedBoja" => $bojica,
                "advancedTitle" => 'Ovaj događaj je ' . $termin->tip()->tip . ' i održaće se u ' . $termin->sala()->naziv . '. Trenutni broj zvanica je ' . $termin->popunjenaMesta() . ', a cena termina je: ' . number_format($termin->cenaTermina(), 2, ',', '.') . ' dinara',
                "advancedDetalj" => $termin->tip()->tip . '.<br> Broj zvanica: ' . $termin->popunjenaMesta() . '<br> Cena: ' . number_format($termin->cenaTermina(), 2, ',', '.'),
            ];
        }

        $data_kal = json_encode($data_kal);

        $this->render($response, 'kalendar.twig', compact('data_kal', 'tipovi', 'sale'));
    }

    public function postKalendarPretraga($request, $response)
    {
        $_SESSION['DATA_KALENDAR_PRETRAGA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('kalendar.pretraga'));
    }

    public function getKalendarPretraga($request, $response)
    {
        $data = $_SESSION['DATA_KALENDAR_PRETRAGA'];
        array_shift($data);
        array_shift($data);


        if (empty($data['sala_id']) && empty($data['tip_dogadjaja_id']) && strlen($data['status']) == 0) {
            return $response->withRedirect($this->router->pathFor('kalendar'));
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
            $params[':tip_dogadjaja_id'] = $data['tip_dogadjaja_id'];
        }

        if (strlen($data['status']) > 0) {
            if ($data['status'] == 1) {
                $where = $where === " WHERE " ? " WHERE " : $where.' AND ';
                $model_termin = new Termin();
                $sql = "SELECT * FROM {$model_termin->getTable()}{$where}termini.datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND zauzet = 1;";
                $termini = $model_termin->fetch($sql, $params);
            } elseif ($data['status'] == 2) {
                $where = $where === " WHERE " ? " WHERE " : $where.' AND ';
                $model_termin = new Termin();
                $sql = "SELECT {$model_termin->getTable()}.*, ugovori.termin_id FROM {$model_termin->getTable()} LEFT JOIN ugovori ON termini.id = ugovori.termin_id{$where}termini.datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND zauzet = 0 AND ugovori.termin_id IS NULL;";
                $termini = $model_termin->fetch($sql, $params);
            } else {
                $where = $where === " WHERE " ? " WHERE " : $where.' AND ';
                $model_termin = new Termin();
                $sql = "SELECT {$model_termin->getTable()}.*, ugovori.termin_id FROM {$model_termin->getTable()} LEFT JOIN ugovori ON termini.id = ugovori.termin_id{$where}termini.datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND zauzet = 0 AND ugovori.termin_id IS NOT NULL;";
                $termini = $model_termin->fetch($sql, $params);
            }
        } else {
            $where = $where === " WHERE " ? "" : $where;
            $model_termin = new Termin();
            $sql = "SELECT * FROM {$model_termin->getTable()}{$where} AND datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH);";
            $termini = $model_termin->fetch($sql, $params);
        }

        $data_kal = [];

        foreach ($termini as $termin) {
            $ikonica = $termin->statusIkonica();
            $bojica = $termin->statusBoja();
            $data_kal[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica,
                "advancedBoja" => $bojica,
                "advancedTitle" => 'Ovaj događaj je ' . $termin->tip()->tip . ' i održaće se u ' . $termin->sala()->naziv . '. Trenutni broj zvanica je ' . $termin->popunjenaMesta() . ', a cena termina je: ' . number_format($termin->cenaTermina(), 2, ',', '.') . ' dinara',
                "advancedDetalj" => $termin->tip()->tip . '.<br> Broj zvanica: ' . $termin->popunjenaMesta() . '<br> Cena: ' . number_format($termin->cenaTermina(), 2, ',', '.'),
            ];
        }

        $data_kal = json_encode($data_kal);

        $this->render($response, 'kalendar.twig', compact('data_kal', 'data'));
    }
}

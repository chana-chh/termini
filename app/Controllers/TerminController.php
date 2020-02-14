<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Classes\Mailer;
use App\Models\Termin;
use App\Models\Sala;
use App\Models\TipDogadjaja;

class TerminController extends Controller
{
    public function getTerminPregled($request, $response, $args) // Kalendar
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $url_termin_dodavanje = $this->router->pathFor('termin.dodavanje.get');

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
                "advancedTitle" => 'Napomena: ' . preg_replace('/\s+/', ' ', trim($termin->napomena))
            ];
        }

        $data = json_encode($data);

        $this->render($response, 'termin/pregled.twig', compact('data', 'url_termin_dodavanje'));
    }

    public function getTerminDetalj($request, $response, $args)
    {
        $id = $args['id'];
        if ($id) {
            $model_termin = new Termin();
            $termin = $model_termin->find($id);
            $this->render($response, 'termin/detalj.twig', compact('termin'));
        } else {
            return $response->withRedirect($this->router->pathFor('termin.pregled.get'));
        }
    }

    public function getTerminDodavanje($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $model_sala = new Sala();
        $sale = $model_sala->all();
        $model_tip = new TipDogadjaja();
        $tipovi = $model_tip->all();

        $this->render($response, 'termin/dodavanje.twig', compact('sale', 'tipovi', 'datum'));
    }

    public function postTerminDodavanje($request, $response)
    {
        $datum = isset($data['datum']) ? $data['datum'] : null;

        $data = $request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $pocetak = strtotime("{$data['datum']} {$data['pocetak']}");
        $kraj = strtotime("{$data['datum']} {$data['kraj']}");

        $validation_rules = [
            'sala' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'pocetak' => [
                'required' => true
            ],
            'kraj' => [
                'required' => true
            ],
            'opis' => [
                'required' => true
            ],
        ];

        if ($kraj <= $pocetak) {
            $this->validator->addError('kraj', 'Vreme završetka termina mora biti veće od vremena početka termina.');
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom dodavanja termina.');
            return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
        } else {
            // Preklapanje termina
            $model_termin = new Termin();
            $preklapanje = false;
            $sql = "SELECT datum, pocetak, kraj FROM termini WHERE datum = :dat AND sala_id = :sal";
            $params = [
            ':dat' => $data['datum'],
            ':sal' => $data['sala_id'],
            ];
            $postojeci_termini = $model_termin->fetch($sql, $params);
            // Uporedjivanje
            foreach ($postojeci_termini as $pt) {
                $pt_pocetak = strtotime("{$pt->datum} {$pt->pocetak}");
                $pt_kraj = strtotime("{$pt->datum} {$pt->kraj}");
                if ($pocetak >= $pt_pocetak && $pocetak < $pt_kraj) {
                    $preklapanje = true;
                }
                if ($kraj > $pt_pocetak && $kraj <= $pt_kraj) {
                    $preklapanje = true;
                }
                if ($pocetak <= $pt_pocetak && $kraj >= $pt_kraj) {
                    $preklapanje = true;
                }
            }

            if ($preklapanje) {
                $this->flash->addMessage('danger', 'Termin se preklapa sa nekim od postojećih termina.');
                return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
            }

            // Upisivanje u bazu
            $data['korisnik_id'] = $this->auth->user()->id;
            $data['zauzet'] = isset($data['zauzet']) ? 1 : 0;
            $model_termin->insert($data);
            $termin = $model_termin->find($model_termin->lastId());
            $link = $this->router->fullUrlFor($this->request->getUri(),'termin.detalj.get', ["id"=>$termin->id]);
			$d = date('d.m.Y', strtotime($termin->datum));
			$p = date('H:i', strtotime($termin->pocetak));
            $k = date('H:i', strtotime($termin->kraj));
            $telo = "<h1>U sali {$termin->sala()->naziv} je zakazan događaj: {$termin->tip()->tip}</h1>
                    <h2>Dana {$d}. godine od {$p} do {$k}</h2>
                    <h3>{$termin->opis}</h3>
                    <p><a href=\"{$link}\">Link do termina</a></p>
                    <p style=\"color: red\">* Milimo Vas da ne odgovarate na ovu poruku</p>
                    <p><strong>Prijatan dan</strong></p>";
            $this->log(Logger::DODAVANJE, $termin, 'opis');

            Mailer::sendMail(
                [['email' => 'stashakg@gmail.com', 'name' => 'Stanislav']],
                "Zakazan je novi termin u sali {$termin->sala()->naziv} za {$d}. godine",
                $telo
            );

            $this->flash->addMessage('success', 'Termin je uspešno dodat.');
            return $response->withRedirect($this->router->pathFor('termin.pregled.get', ['datum' => $data['datum']]));
        }
    }

    public function postTerminBrisanje($request, $response)
    {
        $id = (int) $request->getParam('modal_termin_brisanje_id');
        $model = new Termin();
        $termin = $model->find($id);
        $datum = $termin->datum;

        if (count($termin->ugovori()) > 0) {
            $this->flash->addMessage('danger', "Postoje ugovori vezani za ovaj termin. Da bi se obrisao termin nephodno je prethodno obrisati sve ugovore vezane za njega.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $id]));
        }

        $success = $model->deleteOne($id);
        if ($success) {
            $this->log(Logger::BRISANJE, $termin, 'opis', $termin);
            $this->flash->addMessage('success', "Termin je uspešno obrisan.");
            return $response->withRedirect($this->router->pathFor('termin.pregled.get', ['datum' => $datum]));
        } else {
            $this->flash->addMessage('danger', "Došlo je do greške prilikom brisanja termina.");
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $id]));
        }
    }

    public function postTerminZakljucivanje($request, $response)
    {
        $data = $request->getParams();
        $termin_id = (int) $data['termin_id'];
        $this->addCsrfToken($data);
        $model = new Termin();
        $termin = $model->find($termin_id);
        $zakljucen = $termin->zakljucen();
        $z = $zakljucen ? 0 : 1;
        $data['zakljucen'] = $z == 1 ? true : false;
        $model->update(['zauzet' => $z], $termin_id);
        $termin1 = $model->find($termin_id);
        $data['ikonica'] = $termin1->statusIkonica();
        $data['status'] = $termin1->status();
        $this->log(Logger::IZMENA, $termin1, 'opis', $termin);

        return json_encode($data);
    }

    public function getTerminIzmena($request, $response, $args)
    {
        $id = (int) $args['id'];
        $model_sala = new Sala();
        $sale = $model_sala->all();
        $model_tip = new TipDogadjaja();
        $tipovi = $model_tip->all();
        $model_termin = new Termin();
        $termin = $model_termin->find($id);

        $this->render($response, 'termin/izmena.twig', compact('sale', 'tipovi', 'termin'));
    }

    public function postTerminIzmena($request, $response)
    {
        $data = $request->getParams();
        $id = (int) $data['termin_id'];
        unset($data['termin_id']);
        unset($data['csrf_name']);
        unset($data['csrf_value']);

        $pocetak = strtotime("{$data['datum']} {$data['pocetak']}");
        $kraj = strtotime("{$data['datum']} {$data['kraj']}");

        $validation_rules = [
            'sala' => [
                'required' => true
            ],
            'datum' => [
                'required' => true
            ],
            'pocetak' => [
                'required' => true
            ],
            'kraj' => [
                'required' => true
            ],
            'opis' => [
                'required' => true
            ],
        ];

        if ($kraj <= $pocetak) {
            $this->validator->addError('kraj', 'Vreme završetka termina mora biti veće od vremena početka termina.');
        }

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izmene termina.');
            return $response->withRedirect($this->router->pathFor('termin.izmena.get', ['id' => $id]));
        } else {
            // Preklapanje termina
            $model_termin = new Termin();
            $stari = $model_termin->find($id);
            $preklapanje = false;
            $sql = "SELECT datum, pocetak, kraj FROM termini WHERE datum = :dat AND sala_id = :sal AND id != :iid";
            $params = [
            ':dat' => $data['datum'],
            ':sal' => $data['sala_id'],
            ':iid' => $id,
            ];
            $postojeci_termini = $model_termin->fetch($sql, $params);
            // Uporedjivanje
            foreach ($postojeci_termini as $pt) {
                $pt_pocetak = strtotime("{$pt->datum} {$pt->pocetak}");
                $pt_kraj = strtotime("{$pt->datum} {$pt->kraj}");
                if ($pocetak >= $pt_pocetak && $pocetak < $pt_kraj) {
                    $preklapanje = true;
                }
                if ($kraj > $pt_pocetak && $kraj <= $pt_kraj) {
                    $preklapanje = true;
                }
                if ($pocetak <= $pt_pocetak && $kraj >= $pt_kraj) {
                    $preklapanje = true;
                }
            }
            if ($preklapanje) {
                $this->flash->addMessage('danger', 'Termin se preklapa sa nekim od postojećih termina.');
                return $response->withRedirect($this->router->pathFor('termin.dodavanje.get'));
            }
            // Upisivanje u bazu
            $data['korisnik_id'] = $this->auth->user()->id;
            $model_termin->update($data, $id);
            $termin = $model_termin->find($id);
            if (isset($data['zauzet'])) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            } elseif ($termin->zakljucavanje()) {
                $model_termin->update(['zauzet' => 1], $termin->id);
            }

            $this->log(Logger::IZMENA, $termin, 'opis', $stari);
            $this->flash->addMessage('success', 'Termin je uspešno izmenjen.');
            return $response->withRedirect($this->router->pathFor('termin.detalj.get', ['id' => $id]));
        }
    }
}

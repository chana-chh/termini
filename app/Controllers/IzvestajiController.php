<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Models\Tabela;

class IzvestajiController extends Controller
{
    public function getPoSalama($request, $response)
    {
        $lista = false;
        $godina = date('Y');
        $od_datum = "{$godina}-01-01";
        $do_datum = date('Y-m-d');

        $this->render($response, 'izvestaji/sale.twig', compact('lista', 'od_datum', 'do_datum'));
    }

    public function postPoSalama($request, $response)
    {
        $_SESSION['DATA_IZVESTAJI_SALE_LISTA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('izvestaji.sale.lista'));
    }

    public function getPoSalamaLista($request, $response)
    {
        $lista = true;

        $data = $_SESSION['DATA_IZVESTAJI_SALE_LISTA'];

        $validation_rules = [
            'od' => [
                'required' => true
            ],
            'do' => [
                'required' => true
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izrade izveštaja.');
            return $response->withRedirect($this->router->pathFor('izvestaji.sale'));
        } else {
            $od = $data['od'];
            $do = $data['do'];
            $params = [
                ':od' => $od,
                ':do' => $do,
            ];

            $sql = "SELECT ter.sala_id, sale.naziv, ter.datum,
                            SUM(ugo.ug_broj_mesta) AS broj_mesta,
                            SUM(ugo.ug_iznos) AS iznos,
                            SUM(ugo.ug_uplate_iznos) AS uplate_iznos,
                            (SUM(ugo.ug_iznos) - SUM(ugo.ug_uplate_iznos)) AS dug
                    FROM termini AS ter
                    JOIN (SELECT ug.id, ug.termin_id, ug.broj_mesta AS ug_broj_mesta, ug.broj_stolova AS ug_broj_stolova, ug.iznos AS ug_iznos,
                                IFNULL(up.uplate_iznos,0) AS ug_uplate_iznos
                        FROM ugovori AS ug
                        LEFT JOIN
                            (SELECT uplate.ugovor_id, uplate.iznos AS uplate_iznos
                                FROM uplate) AS up
                        ON up.ugovor_id = ug.id) AS ugo
                    ON ugo.termin_id = ter.id
                    JOIN sale ON sale.id = ter.sala_id
                    WHERE ter.datum BETWEEN :od AND :do
                    GROUP BY ter.sala_id;";
            $model = new Tabela();
            $izvestaj = $model->fetch($sql, $params);
            $zbir_mesta = 0;
            $zbir_iznosa = 0;
            $zbir_uplata = 0;
            $zbir_dugova = 0;
            foreach ($izvestaj as $iz) {
                $zbir_mesta += $iz->broj_mesta;
                $zbir_iznosa += $iz->iznos;
                $zbir_uplata += $iz->uplate_iznos;
                $zbir_dugova += $iz->dug;
            }

            $this->render($response, 'izvestaji/sale.twig', compact('lista', 'izvestaj', 'od', 'do', 'zbir_mesta', 'zbir_iznosa', 'zbir_uplata', 'zbir_dugova'));
        }
    }

    public function getPoTipovima($request, $response)
    {
        $lista = false;
        $godina = date('Y');
        $od_datum = "{$godina}-01-01";
        $do_datum = date('Y-m-d');

        $this->render($response, 'izvestaji/tipovi.twig', compact('lista', 'od_datum', 'do_datum'));
    }

    public function postPoTipovima($request, $response)
    {
        $_SESSION['DATA_IZVESTAJI_TIPOVI_LISTA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('izvestaji.tipovi.lista'));
    }

    public function getPoTipovimaLista($request, $response)
    {
        $lista = true;

        $data = $_SESSION['DATA_IZVESTAJI_TIPOVI_LISTA'];

        $validation_rules = [
            'od' => [
                'required' => true
            ],
            'do' => [
                'required' => true
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izrade izveštaja.');
            return $response->withRedirect($this->router->pathFor('izvestaji.tipovi'));
        } else {
            $od = $data['od'];
            $do = $data['do'];
            $params = [
                ':od' => $od,
                ':do' => $do,
            ];

            $sql = "SELECT ter.tip_dogadjaja_id, s_tip_dogadjaja.tip, ter.datum, SUM(ugo.ug_broj_mesta) AS broj_mesta,
                        SUM(ugo.ug_iznos) AS iznos, SUM(ugo.ug_uplate_iznos) AS uplate_iznos,
                        (SUM(ugo.ug_iznos) - SUM(ugo.ug_uplate_iznos)) AS dug
                    FROM termini AS ter
                    JOIN (SELECT ug.id, ug.termin_id, ug.broj_mesta AS ug_broj_mesta, ug.broj_stolova AS ug_broj_stolova, ug.iznos AS ug_iznos,
                                IFNULL(up.uplate_iznos,0) AS ug_uplate_iznos
                        FROM ugovori AS ug
                        LEFT JOIN
                            (SELECT uplate.ugovor_id, uplate.iznos AS uplate_iznos
                                FROM uplate) AS up
                        ON up.ugovor_id = ug.id) AS ugo
                    ON ugo.termin_id = ter.id
                    JOIN s_tip_dogadjaja ON s_tip_dogadjaja.id = ter.tip_dogadjaja_id
                    WHERE ter.datum BETWEEN :od AND :do
                    GROUP BY ter.tip_dogadjaja_id;";
            $model = new Tabela();
            $izvestaj = $model->fetch($sql, $params);
            $zbir_mesta = 0;
            $zbir_iznosa = 0;
            $zbir_uplata = 0;
            $zbir_dugova = 0;
            foreach ($izvestaj as $iz) {
                $zbir_mesta += $iz->broj_mesta;
                $zbir_iznosa += $iz->iznos;
                $zbir_uplata += $iz->uplate_iznos;
                $zbir_dugova += $iz->dug;
            }

            $this->render($response, 'izvestaji/tipovi.twig', compact('lista', 'izvestaj', 'od', 'do', 'zbir_mesta', 'zbir_iznosa', 'zbir_uplata', 'zbir_dugova'));
        }
    }

    public function getPoVrstiPlacanja($request, $response)
    {
        $lista = false;
        $godina = date('Y');
        $od_datum = "{$godina}-01-01";
        $do_datum = date('Y-m-d');

        $this->render($response, 'izvestaji/vrste.twig', compact('lista', 'od_datum', 'do_datum'));
    }

    public function postPoVrstiPlacanja($request, $response)
    {
        $_SESSION['DATA_IZVESTAJI_VRSTE_LISTA'] = $request->getParams();

        return $response->withRedirect($this->router->pathFor('izvestaji.vrste.lista'));
    }

    public function getPoVrstiPlacanjaLista($request, $response)
    {
        $lista = true;

        $data = $_SESSION['DATA_IZVESTAJI_VRSTE_LISTA'];

        $validation_rules = [
            'od' => [
                'required' => true
            ],
            'do' => [
                'required' => true
            ],
        ];

        $this->validator->validate($data, $validation_rules);

        if ($this->validator->hasErrors()) {
            $this->flash->addMessage('danger', 'Došlo je do greške prilikom izrade izveštaja.');
            return $response->withRedirect($this->router->pathFor('izvestaji.vrste'));
        } else {
            $od = $data['od'];
            $do = $data['do'];
            $params = [
                ':od' => $od,
                ':do' => $do,
            ];

            $sql = "SELECT ugo.nacin_placanja, ter.datum, SUM(ugo.uplate_iznos) AS uplate_iznos
                    FROM termini AS ter
                    JOIN (SELECT ug.id, ug.termin_id, up.nacin_placanja AS nacin_placanja, up.uplate_iznos
                        FROM ugovori AS ug
                        LEFT JOIN
                            (SELECT uplate.ugovor_id, IFNULL(uplate.iznos,0) AS uplate_iznos, uplate.nacin_placanja
                                FROM uplate) AS up
                        ON up.ugovor_id = ug.id) AS ugo
                    ON ugo.termin_id = ter.id
                    WHERE ter.datum BETWEEN :od AND :do
                    GROUP BY nacin_placanja;";
            $model = new Tabela();
            $izvestaj = $model->fetch($sql, $params);
            $zbir = 0;
            foreach ($izvestaj as $iz) {
                $zbir += $iz->uplate_iznos;
            }

            $this->render($response, 'izvestaji/vrste.twig', compact('lista', 'izvestaj', 'od', 'do', 'zbir'));
        }
    }
}

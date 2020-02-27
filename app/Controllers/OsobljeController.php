<?php

namespace App\Controllers;

use App\Classes\Logger;
use App\Models\Termin;
use App\Models\Ugovor;
use App\Models\Meni;
use App\Models\StavkaMenija;

class OsobljeController extends Controller
{
    public function getKalendarOsoblje($request, $response, $args)
    {
        $datum = isset($args['datum']) ? $args['datum'] : null;
        $model_termin = new Termin();
        $sql = "SELECT t.*, u.broj_ugovora FROM {$model_termin->getTable()} AS t
                LEFT JOIN ugovori u ON u.termin_id = t.id
                WHERE t.datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                AND u.termin_id IS NOT NULL;";
        $termini = $model_termin->fetch($sql);
        $data = [];

        foreach ($termini as $termin) {
            $ikonica = $termin->statusIkonica();
            $data[] = (object) [
                "id" => $termin->id,
                "title" => [$termin->sala()->naziv],
                "start" => $termin->datum . ' ' . $termin->pocetak,
                "end" => $termin->datum . ' ' . $termin->kraj,
                "description" => $ikonica
            ];
        }

        $data = json_encode($data);

        $this->render($response, 'kalendar_osoblje.twig', compact('data'));
    }

    public function getTerminOsoblje($request, $response, $args)
    {
        $id = (int) $args['id'];

        if ($id) {
            $model_termin = new Termin();
            $termin = $model_termin->find($id);

            $ugovori_model = new Ugovor();
            $sql = "SELECT * FROM {$ugovori_model->getTable()} WHERE termin_id = {$id};";
            $ugovori = $ugovori_model->fetch($sql);

            $meniji_model = new Meni();
            $sql = "SELECT mu.komada AS komada, m.naziv FROM ugovori AS u
                    LEFT JOIN ugovor_meni mu ON mu.ugovor_id = u.id
                    LEFT JOIN meniji m ON m.id = mu.meni_id
                    WHERE u.termin_id = {$id}";
            $meniji = $meniji_model->fetch($sql);

            $models = new StavkaMenija();
            $kategorije = $models->enumOrSetList('kategorija');

            $this->render($response, 'termin/detalj_osoblje.twig', compact('termin', 'ugovori', 'meniji', 'kategorije'));
        } else {
            return $response->withRedirect($this->router->pathFor('osoblje.kalendar'));
        }
    }
}

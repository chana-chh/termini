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
        $sql = "SELECT * FROM {$model_termin->getTable()}
                WHERE datum > DATE_SUB(CURDATE(), INTERVAL 6 MONTH);";
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

            $models = new StavkaMenija();
            $kategorije = $models->enumOrSetList('kategorija');

            $this->render($response, 'termin/detalj_osoblje.twig', compact('termin', 'ugovori', 'kategorije'));
        } else {
            return $response->withRedirect($this->router->pathFor('osoblje.kalendar'));
        }
    }
}

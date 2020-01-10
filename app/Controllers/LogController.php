<?php

namespace App\Controllers;

use App\Models\Log;
use App\Models\Korisnik;
use App\Classes\Db;

class LogController extends Controller
{
    public function getLog($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Log();
        $logovi = $model->paginate($page, 'page' ,"SELECT * FROM logovi ORDER BY datum DESC;");

        $model_korisnici = new Korisnik();
        $korisnici = $model_korisnici->all();

        $this->render($response, 'logovi.twig', compact('logovi', 'korisnici'));
    }

    public function postLogPretraga($request, $response)
    {
        $_SESSION['DATA_LOGOVI_PRETRAGA'] = $request->getParams();
        return $response->withRedirect($this->router->pathFor('logovi.pretraga'));
    }

    public function getLogPretraga($request, $response)
    {
        $data = $_SESSION['DATA_LOGOVI_PRETRAGA'];
        array_shift($data);
        array_shift($data);
        if (empty($data['opis']) && empty($data['tip']) && empty($data['datum']) && empty($data['korisnik_id'])) {
            $this->getLog($request, $response);
        }

        $data['opis'] = str_replace('%', '', $data['opis']);
        $data['tip'] = str_replace('%', '', $data['tip']);

        $opis = '%' . filter_var($data['opis'], FILTER_SANITIZE_STRING) . '%';
        $tip = '%' . filter_var($data['tip'], FILTER_SANITIZE_STRING) . '%';

        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $where = " WHERE ";
        $params = [];
        if (!empty($data['opis'])) {
            $where .= "opis LIKE :opis";
            $params[':opis'] = $opis;
        }
        if (!empty($data['tip'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "tip LIKE :tip";
            $params[':tip'] = $tip;
        }
        if (!empty($data['datum'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "DATE(datum) = :datum";
            $params[':datum'] = $data['datum'];
        }
        if (!empty($data['korisnik_id'])) {
            if ($where !== " WHERE ") {
                $where .= " AND ";
            }
            $where .= "korisnik_id = :korisnik_id";
            $params[':korisnik_id'] = $data['korisnik_id'];
        }

        $where = $where === " WHERE " ? "" : $where;
        $model = new Log();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY datum DESC;";
        $logovi = $model->paginate($page, 'page', $sql, $params);

        $this->render($response, 'logovi.twig', compact('logovi', 'data'));
    }
}

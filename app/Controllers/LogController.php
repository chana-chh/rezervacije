<?php

namespace App\Controllers;

use App\Models\Log;
Use App\Classes\Db;

class LogController extends Controller
{
    public function getLog($request, $response)
    {
        $query = [];
        parse_str($request->getUri()->getQuery(), $query);
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $model = new Log();
        $logovi = $model->paginate($page);

        $this->render($response, 'logovi.twig', compact('logovi'));
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
        if (empty($data['opis']) && empty($data['tip'])) {
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

        $where = $where === " WHERE " ? "" : $where;
        $model = new Log();
        $sql = "SELECT * FROM {$model->getTable()}{$where} ORDER BY datum DESC;";
        $logovi = $model->paginate($page, $page, $sql, $params);

        $this->render($response, 'logovi.twig', compact('logovi', 'data'));
    }
}
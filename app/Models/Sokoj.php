<?php

namespace App\Models;

use App\Classes\Model;

class Sokoj extends Model
{
    protected $table = 'sokoj';

    public function slucajni($bez)
    {
        if (count($bez) > 0) {
        $niz = implode(",", $bez);
           $sql = "SELECT r1.id AS gg, izvodjac, `kompozicija`
                FROM {$this->table()} AS r1 JOIN
                (SELECT CEIL(RAND() *
                     ((SELECT MAX(id)
                        FROM {$this->table()})-1)+1) AS id) AS r2
                WHERE r1.id >= r2.id AND r1.id NOT IN($niz)
                ORDER BY r1.id ASC
                LIMIT 1;";
        }else{
            $sql = "SELECT r1.id AS gg, izvodjac, `kompozicija`
                FROM {$this->table()} AS r1 JOIN
                (SELECT CEIL(RAND() *
                     ((SELECT MAX(id)
                        FROM {$this->table()})-1)+1) AS id) AS r2
                WHERE r1.id >= r2.id
                ORDER BY r1.id ASC
                LIMIT 1;";
        }

            return $this->fetch($sql);
    }

    public function __toString()
    {
        return 'Podaci iz modela: izvodjac:' . $this->izvodjac . ', kompozicija:' . $this->kompozicija;
    }
}

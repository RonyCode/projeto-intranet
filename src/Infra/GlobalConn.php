<?php

namespace Api\Infra;

use PDO;


class GlobalConn
{

    public static function conn(): PDO
    {
        $con = new PDO(DBDRIVE . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }
}

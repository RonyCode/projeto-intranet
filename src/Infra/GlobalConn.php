<?php

namespace Api\Infra;

use Api\Helper\ResponseError;
use PDO;


class GlobalConn
{
    use ResponseError;

    public static function conn(): PDO
    {
        try {
            $con = new PDO(DBDRIVE . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;

        } catch (\Exception) {
           return responseCatchError("Não foi possivel conectar ao banco de dados");
        }

    }
}

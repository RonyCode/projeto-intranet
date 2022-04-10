<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//Load Composer's autoloader

use Api\Infra\GlobalConn;
use Api\Model\Usuario;

require '../vendor/autoload.php';
//
date_default_timezone_set('America/Araguaina');


$conn = GlobalConn::conn();



$user = new Usuario(
    7, null, "17/02/1986",
    null, null, null, "17/02/1986",
    null, null, "17/02/1846", null,
    null, null, null,
    "11/11/1364", null, null,
    null, "17/02/1500", null,null,null
);

$id = $user->getIdUser();
var_dump((new \Api\Repository\RepoUsers())->selectUser($user));


<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//Load Composer's autoloader

require '../vendor/autoload.php';
//
date_default_timezone_set('America/Araguaina');


$teste = '12345678';

$func = new \Api\Model\Funcionario(
    1,
    $teste,
    null,
    null,
    "25.960-45",
    "SSPDF",
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    "A",
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
    null,
);

var_dump($func->getTs());
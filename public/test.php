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
    "28/03/2022",
    null,
    null,
    null,
    null,
    'João',
    null,
    "A",
    "POS.",
    null,
    null,
    null,
    null,
    "28/03/2022",
    "01680562169",
    null,
    null,
    null,
    "28/03/2022",
    null,
    null,
    null,
    "28/03/2022",
);
$testsa = null;


var_dump($testsa ?? $func);
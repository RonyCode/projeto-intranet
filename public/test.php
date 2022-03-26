<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

//Load Composer's autoloader

use Api\Infra\GlobalConn;
use Api\Model\User;

require '../vendor/autoload.php';
//
date_default_timezone_set('America/Araguaina');

$user = new User(
    1,
    "23/03/2022",
    "rony",
    11213957 - 1,
    "016.805.621-69",
    '123456789',
    '17/02/1986',
    "sim",
    'M',
    "A",
    '11/05/2000',
    'ronypc@outlook.com',
    "22/03/2022",
    1,
    null,


);

var_dump($user->getBirthday());

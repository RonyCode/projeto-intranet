<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

//Load Composer's autoloader
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

//var_dump((new \Api\Helper\ValidateParams())->dateFormatDbToBr("2014-10-30"));
var_dump((new \Api\Helper\ValidateParams())->dateTimeFormatDbToBr("2014-10-30 21:08:34"));
var_dump((new \Api\Helper\ValidateParams())->dateFormatDbToBr("1986-02-17"));
var_dump((new \Api\Helper\ValidateParams())->dateTimeFormatDbToBr("2022-03-21 13:15:42"));
var_dump( date('Y-m-d H:i:s',(strtotime('+ 24 hour'))));
deleteUser();
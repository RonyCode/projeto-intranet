<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//Load Composer's autoloader

use Api\Infra\GlobalConn;
use Api\Model\User;

require '../vendor/autoload.php';
//
date_default_timezone_set('America/Araguaina');


$conn = GlobalConn::conn();



$user = new User(
    null, null, "17/02/1986",
    null, null, null, "17/02/1986",
    null, null, "17/02/1846", null,
    null, null, null,
    "11/11/1364", null, null,
    null, "17/02/1500", null,null
);


var_dump( (new \Api\Helper\ValidateParams())->dateTimeFormatDbToBr("1988-01-01 22:00:00"));
var_dump( (new \Api\Helper\ValidateParams())->dateFormatDbToBr("1988-01-01"));
var_dump( (new \Api\Helper\ValidateParams())->dateFormatDbToBr("1988-01-01"));
var_dump( (new \Api\Helper\ValidateParams())->dateFormatDbToBr("1988-01-01"));
var_dump( (new \Api\Helper\ValidateParams())->dateFormatDbToBr("1988-01-01"));

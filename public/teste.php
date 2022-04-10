<?php

use Api\Infra\GlobalConn;
use Api\Model\Usuario;

require __DIR__ . "/../src/Model/Student.php";
require __DIR__ . "/../vendor/autoload.php";

$use = new Usuario(null, 'RONY ANDERSON', null, null, null);

$idStudent = $_POST["id_student"];
$student = $_POST["name"];
$dayStudent = explode(",", $_POST["day_student"]);



$pdo = new GlobalConn();
$stmt = $pdo::conn();

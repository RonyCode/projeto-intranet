<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

//Load Composer's autoloader
use Api\Model\Student;

require '../vendor/autoload.php';
//

$validate = new Student(
    1,
    "Rony",
    63981225577,
    "ronypc@outlook.com",
    "qdasdsadas",
    '17/02/1986',
    '17/02/1986',
    1234567,
    '17/02/1800',
    "3superior",
    '11/05/2000',
    'adimplente'
);

var_dump("Nome:" . $validate->getName());
//var_dump("idade:" . $validate->getAge());
//var_dump("Data nasc:" . $validate->getBirthday());
//var_dump("Email:" . $validate->getEmail());
//var_dump("Endereço:" . $validate->getAddress());
//var_dump("Escolaridade:" . $validate->getGrade());
//var_dump("Numero de Contrato:" . $validate->getContractNumber());
//var_dump("Venc Contrato:" . $validate->getDayStudent());
//var_dump("Data Pgto:" . $validate->getDatePayment());
//var_dump("Situação:" . $validate->getSituation());

$test = password_hash("1234567a", PASSWORD_ARGON2I);
$test2 = password_verify("1234567a", $test);
var_dump($test, $test2);
echo $test2;
echo $test;
echo password_hash('password', PASSWORD_ARGON2I);

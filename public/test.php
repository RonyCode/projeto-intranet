<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//Load Composer's autoloader

require '../vendor/autoload.php';
//
date_default_timezone_set('America/Araguaina');


$pdo = new PDO("mysql:host=localhost;dbname=intranet", "root", "170286P@ra");
$pdoAPi = new PDO("mysql:host=localhost;dbname=intranet_api", "root", "170286P@ra");

//$stmtAPi = $pdoAPi->prepare("SELECT * FROM privilegio");
//$stmtAPi->execute();
//$rows = $stmtAPi->fetchAll();
//
//if ($stmtAPi->rowCount() > 0) {
//    foreach ($rows as $item) {
//        $stmt = $pdoAPi->prepare("INSERT INTO privilégios1 (modulo, servico, sub_servico, status)
//                    VALUES(:modulo,:servico,:sub_servico,:status)");
//
//        $stmt->bindValue(":modulo", $item["nodulo"]);
//        $stmt->bindValue(":servico", $item["servico"]);
//        $stmt->bindValue(":sub_servico", $item["sub_servico"]);
//        $stmt->bindValue(":status", $item["status"]);
//        $stmt->execute();
//    }
//}
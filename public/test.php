<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//Load Composer's autoloader

require '../vendor/autoload.php';
//
date_default_timezone_set('America/Araguaina');


$pdo = new PDO("mysql:host=localhost;dbname=intranet", "root", "170286P@ra");
$pdoAPi = new PDO("mysql:host=localhost;dbname=intranet_api", "root", "170286P@ra");

//$stmt = $pdo->prepare("SELECT * FROM RH_UNIDADE");
//$stmt->execute();
//$rows = $stmt->fetchAll();
//if ($stmt->rowCount() > 0) {
//    foreach ($rows as $item) {
//        $pdoAPi = new PDO("mysql:host=localhost;dbname=intranet_api", "root", "170286P@ra");
//        $stmtA = $pdoAPi->prepare(
//            "UPDATE  unidade  SET cmt_und = null, sub_cmt_und = null WHERE id_und = id_und
//
//         ");
//
//        $stmtA->execute();
//    }
}
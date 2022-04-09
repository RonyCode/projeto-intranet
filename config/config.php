<?php

//CONFIG OF DATABASE LOCAL
const DBDRIVE = 'mysql';
const DBHOST = 'localhost';
const DBNAME = 'intranet_api';
const DBUSER = 'root';
const DBPASS = '170286P@ra';

//CONFIG OF DATABASE INTRANET
const DBDRIVE_INTRANET = 'mysql';
const DBHOST_INTRANET = 'localhost';
const DBNAME_INTRANET = 'intranet_api';
const DBUSER_INTRANET = 'root';
const DBPASS_INTRANET = '170286P@ra';

//CONFIG OF DATABASE INTRANET-HOMOLOGAÇÃO
const DBDRIVE_HOMOLOGACAO = 'mysql';
const DBHOST_HOMOLOGACAO = 'localhost';
const DBNAME_HOMOLOGACAO = 'intranet_api';
const DBUSER_HOMOLOGACAO = 'root';
const DBPASS_HOMOLOGACAO = '170286P@ra';

//########################################################################################
//CONFIG OF EVENT TO EXPIRES HASH AFTER 24H
//########################################################################################
//CREATE EVENT
//    EXCLUIR_HASH
//ON SCHEDULE EVERY
//    30 SECOND
//ON COMPLETION PRESERVE
//DO
//UPDATE intranet_api.senha_respawn SET hash_temp = NULL WHERE date_expires < NOW();

//########################################################################################
//CONFIG FIELDS UNIQUE REGISTER
//########################################################################################
//CPF
//EMAIL
//########################################################################################


//########################################################################################
//CONFIG OF EVENT TO DELETE REPEATED DATA
//########################################################################################
//CREATE EVENT
//   EXCLUI_DADOS_REPETIDOS
//ON SCHEDULE EVERY
//    5 second
//ON COMPLETION PRESERVE
//DO
//    DELETE id_old FROM intranet_api.senha_respawn AS id_old,
//                 intranet_api.senha_respawn AS id_new WHERE id_old.id < id_new.id;
//########################################################################################



///CONFIG LOGIN JWT
const JWTKEY = 'Ronyc0d3';

/// CONFIG PHPMAILER
/// !!!!ATENTION!!!! FOR USERS S.O LINUX APACHE USER THIS COMMANDS FOR GRANT PERMISSION ON SERVER
/// sudo setsebool -P httpd_can_sendmail 1
//  sudo setsebool -P httpd_can_network_connect 1

const    HOST_MAIL = 'smtp.gmail.com';
const    PORT_MAIL = '587';
const    USER_MAIL = 'espaco.educar.palmas@gmail.com';
const    PASS_MAIL = 'lcquwdhhdgxjxzld';
const    FROM_NAME_MAIL = 'INFO-CBMTO';
const    FROM_EMAIL_MAIL = 'espaco.educar.palmas@gmail.com';
const    SUBJET_MAIL = 'Email de solicitação para recuperação de senha.';
const    ALT_BODY = 'Email solicitação recuperação de senha.Caso o remetente não use HTML';

//DIRETORY IMAGES

define("DIR_IMG", $_SERVER['DOCUMENT_ROOT'] . '/api-intranet-proj/uploads/');

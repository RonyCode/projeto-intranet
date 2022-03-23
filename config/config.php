<?php

//CONFIG OF DATABASE LOCAL
const DBDRIVE = 'mysql';
const DBHOST = 'localhost';
const DBNAME = 'intranet';
const DBUSER = 'root';
const DBPASS = '170286P@ra';

//CONFIG OF DATABASE INTRANET
const DBDRIVE_INTRANET = 'mysql';
const DBHOST_INTRANET = 'localhost';
const DBNAME_INTRANET = 'intranet';
const DBUSER_INTRANET = 'root';
const DBPASS_INTRANET = '170286P@ra';

//CONFIG OF DATABASE INTRANET-HOMOLOGAÇÃO
const DBDRIVE_HOMOLOGACAO = 'mysql';
const DBHOST_HOMOLOGACAO = 'localhost';
const DBNAME_HOMOLOGACAO = 'intranet';
const DBUSER_HOMOLOGACAO = 'root';
const DBPASS_HOMOLOGACAO = '170286P@ra';

///CONFIG LOGIN JWT
const JWTKEY = 'Ronyc0d3';

/// CONFIG PHPMAILER
const    HOST_MAIL = 'smtp.gmail.com';
const    PORT_MAIL = '587';
const    USER_MAIL = 'espaco.educar.palmas@gmail.com';
const    PASS_MAIL = 'eyjouwmrxvvjxllb';
const    FROM_NAME_MAIL = 'Espaço Educar';
const    FROM_EMAIL_MAIL = 'espaco.educar.palmas@gmail.com';
const    SUBJET_MAIL = 'Email de solicitação para recuperação de senha.';
const    ALT_BODY = 'Email solicitação recuperação de senha.Caso o remetente não use HTML';

//DIRETORY IMAGES

define("DIR_IMG", $_SERVER['DOCUMENT_ROOT'] . '/api-intranet-proj/uploads/');

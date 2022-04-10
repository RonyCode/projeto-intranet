<?php

namespace config\routes;

use Api\Controllers\ErrorController;
use Api\Controllers\LoginController;
use Api\Controllers\LoginRecoverPassController;
use Api\Controllers\LoginRegisterController;
use Api\Controllers\LoginResetPassController;
use Api\Controllers\MilitarGetAllController;
use Api\Controllers\MilitarSaveController;
use Api\Controllers\UserGetAllController;
use Api\Controllers\UserPhotoSaveController;
use Api\Controllers\UserSelectedController;
use Api\Infra\Router;

$routesProtected = 'Protected';
$arrayRotas = [
    "routes" => [
        '/login' => LoginController::class,
        '/login/cadastrar' => LoginRegisterController::class,
        '/login/recuperar' => LoginRecoverPassController::class,
        '/login/resetar' => LoginResetPassController::class,
        '/error' => ErrorController::class
    ],
    $routesProtected => [
        '/militares' => MilitarGetAllController::class,
        '/militar/salvar' => MilitarSaveController::class,
        '/usuarios' => UserGetAllController::class,
        '/usuario/id' => UserSelectedController::class,
        '/usuario/foto/salvar' => UserPhotoSaveController::class,

    ],
];
return (new Router())->addRoute($url, $arrayRotas, $routesProtected);

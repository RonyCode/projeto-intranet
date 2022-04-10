<?php

namespace config\routes;

use Api\Controllers\LoginController;
use Api\Controllers\DeleteStdController;
use Api\Controllers\ErrorController;
use Api\Controllers\FuncionarioGetAllController;
use Api\Controllers\UserGetAllController;
use Api\Controllers\UserSelectedController;
use Api\Controllers\LoginRecoverPassController;
use Api\Controllers\LoginRegisterController;
use Api\Controllers\LoginResetPassController;
use Api\Controllers\UserPhotoSaveController;
use Api\Controllers\SaveStdController;
use Api\Controllers\SelectStdController;
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
//        '/militares' => FuncionarioGetAllController::class,
        '/usuarios' => UserGetAllController::class,
        '/usuario/id/' . $id => UserSelectedController::class,
        '/usuario/foto/adicionar' => UserPhotoSaveController::class,
        '/usuario/foto/deletar' => UserPhotoSaveController::class,

    ],
];
return (new Router())->addRoute($url, $arrayRotas, $routesProtected);

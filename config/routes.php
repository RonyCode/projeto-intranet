<?php

namespace config\routes;

use Api\Controllers\AuthController;
use Api\Controllers\DeleteStdController;
use Api\Controllers\ErrorController;
use Api\Controllers\GetAllStdController;
use Api\Controllers\GetUserController;
use Api\Controllers\RecoverPassController;
use Api\Controllers\RegisterLoginController;
use Api\Controllers\ResetPassController;
use Api\Controllers\SavePhotoUserController;
use Api\Controllers\SaveStdController;
use Api\Controllers\SelectStdController;
use Api\Infra\Router;

$routesProtected = 'Protected';
$arrayRotas = [
    "routes" => [
        '/login' => AuthController::class,
        '/login/cadastrar' => RegisterLoginController::class,
        '/login/recuperar' => RecoverPassController::class,
        '/login/resetar' => ResetPassController::class,
        '/error' => ErrorController::class
    ],
    $routesProtected => [
        '/users' => GetAllStdController::class,
        '/user/id/' . $id => SelectStdController::class,
        '/user/salvar' => SaveStdController::class,
        '/aluno/deletar' => DeleteStdController::class,
        '/user' => GetUserController::class,
        '/user/foto/adicionar' => SavePhotoUserController::class,

    ],
];
return (new Router())->addRoute($url, $arrayRotas, $routesProtected);

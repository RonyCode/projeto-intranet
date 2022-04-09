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
use Api\Controllers\SavePhotoUserController;
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
        '/funcionarios' => FuncionarioGetAllController::class,
        '/usuarios' => UserGetAllController::class,
        '/user/id/' . $id => SelectStdController::class,
        '/user/salvar' => SaveStdController::class,
        '/aluno/deletar' => DeleteStdController::class,
        '/user' => UserSelectedController::class,
        '/user/foto/adicionar' => SavePhotoUserController::class,

    ],
];
return (new Router())->addRoute($url, $arrayRotas, $routesProtected);

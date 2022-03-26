<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Model\User;
use Api\Repository\RepoUsers;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RegisterLoginController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (!isset($_POST) || $_POST == false || empty($_POST)) {
                throw new Exception();
            }
            $register = filter_var($request->getParsedBody()['register'], FILTER_SANITIZE_STRING);
            $cpf = filter_var($request->getParsedBody()['cpf'], FILTER_SANITIZE_STRING);
            $birthday = filter_var($request->getParsedBody()['birthday'], FILTER_SANITIZE_STRING);
            $email = filter_var($request->getParsedBody()['email'], FILTER_VALIDATE_EMAIL);
            $pass = filter_var($request->getParsedBody()['pass'], FILTER_SANITIZE_STRING);
            $passCheck = filter_var($request->getParsedBody()['pass_check'], FILTER_SANITIZE_STRING);
            if ($pass !== $passCheck) {
                throw new Exception("Senha não confere");
            }

            $user = new User(
                null, null, null,
                $register, $cpf, $pass,
                null, null, null,
                null, $birthday, $email,
                null, null, null
            );
            var_dump($user);
            $response = (new RepoUsers())->addUser($user);
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Erro nos verbos HTTPs ou nome dos campos');
        }
    }
}

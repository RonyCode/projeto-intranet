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

class LoginRegisterController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (!isset($_POST) || $_POST == false || empty($_POST)) {
                throw new Exception();
            }
            $register = filter_var($request->getParsedBody()['matricula'], FILTER_SANITIZE_STRING);
            $cpf = filter_var($request->getParsedBody()['cpf'], FILTER_SANITIZE_STRING);
            $birthday = filter_var($request->getParsedBody()['nascimento'], FILTER_SANITIZE_STRING);
            $email = filter_var($request->getParsedBody()['email'], FILTER_VALIDATE_EMAIL);
            $pass = filter_var($request->getParsedBody()['senha'], FILTER_SANITIZE_STRING);
            $passCheck = filter_var($request->getParsedBody()['senha_check'], FILTER_SANITIZE_STRING);
            if ($pass !== $passCheck) {
                throw new Exception("Senha não confere");
            }


            $user = new User(
                null, null, null,
                $email, $pass, $cpf, $birthday,
                null, null, null, null,
                null, null, null,
                null, $register, null,
                null, null, null,null
            );

            $response = (new RepoUsers())->addUser($user);
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Erro nos verbos HTTPs ou nome dos campos');
        }
    }
}

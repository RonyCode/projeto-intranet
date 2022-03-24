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

class ResetPassController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $cpf = filter_var($request->getParsedBody()['cpf'], FILTER_SANITIZE_STRING);
            $email = filter_var($request->getParsedBody()['email'], FILTER_VALIDATE_EMAIL);
            $pass = filter_var($request->getParsedBody()['pass'], FILTER_SANITIZE_STRING);
            $user = new User(
                null, null, null,
                null, $cpf, $pass,
                null, null, null,
                null, null, $email,
                null, null, null
            );
            $response = (new RepoUsers())->checkHashEmail($user);
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Hash não recebido pelo email');
        }
    }
}

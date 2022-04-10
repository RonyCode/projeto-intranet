<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Model\Usuario;
use Api\Repository\RepoUsers;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginResetPassController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): Response
    {
        try {
            $hash = filter_var($request->getParsedBody()['hash'], FILTER_SANITIZE_STRING);
            $cpf = filter_var($request->getParsedBody()['cpf'], FILTER_SANITIZE_STRING);
            $pass = filter_var($request->getParsedBody()['senha'], FILTER_SANITIZE_STRING);

            $user = new Usuario(
                null, null, null,
                null, $pass, $cpf, null,
                null, null, null, null,
                null, null, null,
                null, null, null,
                null, null, null, null, null
            );
            $response = (new RepoUsers())->checkHashEmail($user, $hash);
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError(
                'Hash não recebido pelo email ou erro no POST campo necessário:
                 hash, cpf, senha exatamente neste formato'
            );
        }
    }
}

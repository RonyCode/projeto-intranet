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

class RecoverPassController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (!isset($_POST) || $_POST == false || empty($_POST)) {
                throw new Exception();
            }
        $cpf = filter_var($request->getParsedBody()['cpf'], FILTER_SANITIZE_STRING);
        $user = new User(
            null, null, null,
            null, $cpf, null,
            null, null, null,
            null, null, null,
            null, null, null
        );
        $response = (new RepoUsers())->recoverPass($user);
        return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Verifique o email correto ou error nos verbos HTTPs');
        }
    }
}

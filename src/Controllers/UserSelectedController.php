<?php


namespace Api\Controllers;


use Api\Helper\ResponseError;
use Api\Model\Usuario;
use Api\Repository\RepoUsers;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserSelectedController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (!isset($_POST) || $_POST == false || empty($_POST)) {
                throw new Exception();
            }
            $idUser = filter_var($request->getParsedBody()['id_user'], FILTER_SANITIZE_STRING);
            $user = new Usuario(
                $idUser, null, null, null, null, null, null, null,
                null, null, null, null, null, null,
                null, null, null, null, null, null,
                null, null
            );
            $response = (new RepoUsers())->selectUserByIdUser($user);
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError(
                'Token inválido ou erro no POST campo necessário: id_user exatamente neste formato'
            );
        }
    }
}

<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Repository\RepoMilitar;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MilitarGetAllController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $response = (new RepoMilitar())->getAllMilitar();
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Não autenticado token, ou problemas em buscar todos os militares');
        }
    }
}

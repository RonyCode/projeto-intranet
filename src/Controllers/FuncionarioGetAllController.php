<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Repository\RepoFuncionario;
use Api\Repository\RepoStudents;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FuncionarioGetAllController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $response = (new RepoFuncionario())->getAllFuncionario();
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Não autenticado token, ou problemas em buscar todos os funcionarios');
        }
    }
}

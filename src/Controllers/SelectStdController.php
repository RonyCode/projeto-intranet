<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Infra\Router;
use Api\Model\Student;
use Api\Repository\RepoStudents;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SelectStdController implements RequestHandlerInterface
{
    use ResponseError;

    public function __construct()
    {
    }

    public function handle($request): ResponseInterface
    {
        $url = Router::normalizeUrl();
        try {
            if (is_null($url) || empty($url)) {
                throw new Exception();
            }
            $student = new Student(
                $url[0],
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            );

            $response = (new RepoStudents())->selectStd($student);
            return new Response(200, [], json_encode($response, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('Não autenticado ou error nos verbos HTTPs');
        }
    }
}

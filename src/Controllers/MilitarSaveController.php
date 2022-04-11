<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Model\Militar;
use Api\Repository\RepoMilitar;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MilitarSaveController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): Response
    {
        try {
            if (!isset($_POST) || $_POST == false || empty($_POST)) {
                throw new Exception();
            }
            isset($_POST['id_m']) ? $id = filter_var($_POST['id_m'], FILTER_VALIDATE_INT) : $id = null;
            isset($_POST['lotacao']) ? $lotacao = filter_var($_POST['lotacao'], FILTER_VALIDATE_INT) : $lotacao = null;
            $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
            $nomeGuerra = filter_var($_POST['nome_guerra'], FILTER_SANITIZE_STRING);
            $postoGraduacao = filter_var($_POST['posto_graduacao'], FILTER_SANITIZE_STRING);
            $matricula = filter_var($_POST['matricula'], FILTER_SANITIZE_STRING);
            $rgMilitar = filter_var($_POST['rg_militar'], FILTER_SANITIZE_STRING);
            $tipoSanguee = filter_var($_POST['tipo_sangue'], FILTER_SANITIZE_STRING);
            $funcao = filter_var($_POST['funcao'], FILTER_SANITIZE_STRING);
            $situacao = filter_var($_POST['situacao'], FILTER_SANITIZE_STRING);

            $militar = new Militar(
                $id | null,
                $nome,
                $nomeGuerra,
                $postoGraduacao,
                $matricula,
                $rgMilitar,
                $tipoSanguee,
                $lotacao | null,
                $funcao,
                $situacao
            );
            var_dump($militar);

            $addMilitar = (new RepoMilitar())->saveMilitar($militar);
            return new Response(200, [], json_encode($addMilitar, JSON_UNESCAPED_UNICODE));
        } catch (Exception) {
            $this->responseCatchError('
             Não autenticado ou error no POST campos necessários:
             id_user para update ou sem id_user para add, nome, nome_guerra, posto_graduacao, matricula, 
             rg_militar, tipo_sangue, lotacao, funcao exatamente neste formato');
        }
    }
}

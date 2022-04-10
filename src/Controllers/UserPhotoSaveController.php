<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Model\UsuarioFoto;
use Api\Repository\RepoFotos;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserPhotoSaveController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            empty($_FILES) ? throw new Exception() :
                $image = ($request->withUploadedFiles($_FILES['photo']))->getUploadedFiles();
            isset($_POST['id_user']) ? $idUser = filter_var($request->getParsedBody()['id_user'],
                FILTER_VALIDATE_INT) : throw new  Exception();

            // UsuarioFoto Class have custom sizes for more performance
            $img = new UsuarioFoto($image, $idUser, 220, 220);
            $response = (new RepoFotos())->saveFoto($img);

            return new Response(200, [], str_replace('\\', '', json_encode($response)));
        } catch (Exception) {
            $this->responseCatchError('Imagem não selecionada ou erro com id imagem.');
        }
    }
}

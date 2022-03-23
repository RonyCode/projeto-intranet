<?php

namespace Api\Controllers;

use Api\Helper\ResponseError;
use Api\Model\Image;
use Api\Repository\RepoImages;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SavePhotoUserController implements RequestHandlerInterface
{
    use ResponseError;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            empty($_FILES) ? throw new Exception() : $image =
                ($request->withUploadedFiles($_FILES['photo']))->getUploadedFiles();
            isset($_POST['id']) ? $idPhoto = filter_var(
                $request->getParsedBody()['id'],
                FILTER_VALIDATE_INT
            ) : throw new  Exception();
            $img = new Image($image, $idPhoto, 220, 220);
            $response = (new RepoImages())->saveImg($img);
            return new Response(200, [], str_replace('\\', '', json_encode($response)));
        } catch (Exception) {
            $this->responseCatchError('Imagem não selecionada ou erro com id imagem.');
        }
    }
}

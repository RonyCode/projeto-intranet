<?php

namespace Api\Repository;

use Api\Helper\ResponseError;
use Api\Infra\GlobalConn;
use Api\Infra\UploadImages;
use Api\Interface\ImageInterface;
use Api\Model\Image;
use Exception;

class RepoImages extends GlobalConn implements ImageInterface
{
    use ResponseError;

    public function __destruct()
    {
    }

    public function saveImg(Image $image): array
    {
        try {
            //     Create Directory with image before insert into MYSQL AND refresh old images
            //============================================================//
            array_map('unlink', glob("/var/www/html/api-ronycode/uploads/" . $image->getPhotoId() . '/*'));
            (new UploadImages())->saveImgResized($image, true);
            $stmt = self::conn()->prepare(
                'UPDATE user SET photo_name = :photo_name, src = :src, size = :size WHERE id = :id'
            );
            $stmt->bindValue(':id', $image->getPhotoId());
            $stmt->bindValue(':photo_name', $image->getPhotoNameRandomized());
            $stmt->bindValue(':src', $image->getPhotoSrc());
            $stmt->bindValue(':size', 'width_' .
                $image->getPhotoCustomWidth() . ' / ' .
                'heigth_' . $image->getPhotoCustomHeight());
            $stmt->execute();
            if ($stmt->rowCount() < 0) {
                throw new Exception();
            }
            return [
                'data' => $image->getPhotoSrc(),
                'status' => 'success',
                'code' => 201,
                "message" => "Imagem <strong> " . $image->getPhotoName() . " </strong> salva com sucesso!"
            ];
        } catch (Exception) {
            $this->responseCatchError('Imagens com mesmo nome não aceito.');
        }
    }
}

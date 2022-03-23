<?php

namespace Api\Infra;

use Api\Helper\ResponseError;
use Api\Model\Image;
use Exception;

class UploadImages
{
    use ResponseError;

    public function __construct()
    {
    }

    public function saveImgResized(Image $image, bool $randomNames): Image
    {

        try {
            $width = $image->getPhotoCustomWidth();
            $height = $image->getPhotoCustomHeight();

            list($width_orig, $height_orig, $type, $atributo) = getimagesize($image->getPhotoTmpName());

            if ($width_orig > $height_orig) {
                $height = ($width / $width_orig) * $height_orig;
            } elseif ($width_orig < $height_orig) {
                $width = ($height / $height_orig) * $width_orig;
            }
            $novaimagem = imagecreatetruecolor($width, $height);
            if ($type > 3 || $type == 0) {
                echo 'nao aceita outros formatos que não png, jpeg, ou gif';
                exit();
            }

            switch ($type) {
                case 1:
                    $origem = imagecreatefromgif($image->getPhotoTmpName());
                    imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    imagegif($novaimagem, $this->dirToImageFromUpload($image, $randomNames));
                    break;

                case 2:
                    $origem = imagecreatefromjpeg($image->getPhotoTmpName());
                    imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    imagejpeg($novaimagem, $this->dirToImageFromUpload($image, $randomNames));
                    break;

                case 3:
                    $origem = imagecreatefrompng($image->getPhotoTmpName());
                    imagecopyresampled($novaimagem, $origem, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    imagepng($novaimagem, $this->dirToImageFromUpload($image, $randomNames));
                    break;
            }
            if (isset($novaimagem) && isset($origem)) {
                imagedestroy($novaimagem);
                imagedestroy($origem);
            }
            return $image;
        } catch (Exception) {
            $this->responseCatchError(
                "Arquivo com extensão inválida, verifique o tipo de extensão, para uploads somente .PNG ou .JPEG"
            );
        }
    }

    private function dirToImageFromUpload(Image $image, $randomNames): string
    {
        try {
            if (!is_dir($image->getPhotoDir() . $image->getPhotoId())) {
                mkdir($image->getPhotoDir() . $image->getPhotoId(), 0777);
            }
            $nameUploaded = $image->getPhotoDir()
                . $image->getPhotoId() . '/'
                . $this->randomizerNames($randomNames)
                . $this->tirarAcento($image->getPhotoName())
                . $image->getPhotoExtension();
            $image->setPhotoSrc($nameUploaded);
            $image->setPhotoNameRandomized($this->randomizerNames($randomNames)
                . $this->tirarAcento($image->getPhotoName())
                . $image->getPhotoExtension());
            return $nameUploaded;
        } catch (Exception) {
            $this->responseCatchError(
                "Não foi possível localizar diretório, verifique na raiz do diretório, folderName com barra ao final"
            );
        }
    }

    private function randomizerNames($toggle): string
    {
        if ($toggle) {
            return md5(rand(1, 99999))
                . '_';
        }
        return '';
    }

    private function tirarAcento($texto): string
    {
        $com_acento = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í'
        , 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å'
        , 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü'
        , 'Ú', 'Ÿ',);
        $sem_acento = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i',
            'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A',
            'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U',
            'U', 'Y',);
        $final = str_replace($com_acento, $sem_acento, $texto);
        $com_pontuacao = array('´', '`', '¨', '^', '~', ' ', '-');
        $sem_pontuacao = array('', '', '', '', '', '_', '_');
        return str_replace($com_pontuacao, $sem_pontuacao, $final);
    }

    public function getNameImg(Image $image, $randomNames)
    {
        return $this->randomizerNames($randomNames)
            . $this->tirarAcento($image->getPhotoName())
            . $image->getPhotoExtension();
    }
}

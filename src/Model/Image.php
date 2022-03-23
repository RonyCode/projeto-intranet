<?php

namespace Api\Model;

class Image
{
    private ?string $photoName;
    private ?string $photoNameRandomized;
    private ?string $photoSrc;
    private ?string $photoExtension;
    private ?string $photoTmpName;
    private ?string $photoDir;
    private ?int $photoError;

    public function __construct(
        private ?array $photoPost,
        private ?string $photoId,
        private ?int $photoCustomWidth,
        private ?int $photoCustomHeight
    ) {
        if ($this->photoPost['error'] != 0) {
            switch ($this->photoPost['error']) {
                case 1:
                    echo 'O arquivo enviado excede o limite definido na diretiva upload_max_filesize';
                    break;
                case 2:
                    echo 'O arquivo excede o limite definido em MAX_FILE_SIZE no formulário HTML';
                    break;
                case 3:
                    echo 'O upload do arquivo foi feito parcialmente';
                    break;
                case 4:
                    echo 'Nenhum arquivo foi enviado.';
                    break;
                case 6:
                    echo 'Pasta temporária ausênte.';
                    break;
            }
            exit();
        }
        $this->photoName = $this->photoPost['name'];
        $this->photoTmpName = $this->photoPost['tmp_name'];
        $this->photoExtension = $this->photoPost['name'];
        $this->photoDir = DIR_IMG;
        $this->photoError = $this->photoPost['error'];
    }

    public function getPhotoExtension(): ?string
    {
        if (!isset(pathinfo($this->photoExtension)['extension'])) {
            $extensao = getimagesize($this->getPhotoTmpName());
            return image_type_to_extension($extensao[2]);
        }
        return '';
    }

    public function getPhotoTmpName(): ?string
    {
        return $this->photoTmpName;
    }

    public function getPhotoDir(): ?string
    {
        return $this->photoDir;
    }


    public function getPhotoName(): ?string
    {
        return $this->photoName = pathinfo($this->photoPost['name'])['basename'];
    }

    public function getPhotoError(): string|static
    {
        switch ($this->photoError) {
            case 0:
                return 'Arquivo enviado com sucesso!';
            case 1:
                return 'O arquivo enviado excede o limite definido na diretiva upload_max_filesize';
            case 2:
                return 'O arquivo excede o limite definido em MAX_FILE_SIZE no formulário HTML';
            case 3:
                return 'O upload do arquivo foi feito parcialmente';
            case 4:
                return 'Nenhum arquivo foi enviado.';
            case 6:
                return 'Pasta temporária ausênte.';
            case 7:
                return 'Falha em escrever o arquivo em disco';
        }
        return $this;
    }

    public function getPhotoCustomHeight(): ?string
    {
        return $this->photoCustomHeight;
    }

    public function getPhotoCustomWidth(): ?string
    {
        return $this->photoCustomWidth;
    }

    public function getPhotoSrc(): ?string
    {
        $array = explode('/', $this->photoSrc);
        return 'http://localhost/api-ronycode/uploads/' . $this->getPhotoId() . '/' . end($array);
    }

    public function setPhotoSrc(?string $photoSrc): void
    {
        $this->photoSrc = $photoSrc;
    }

    public function getPhotoId(): ?string
    {
        return $this->photoId;
    }

    public function getPhotoNameRandomized(): ?string
    {
        return $this->photoNameRandomized;
    }

    public function setPhotoNameRandomized(?string $photoNameRandomized): void
    {
        $array = explode('/', $this->photoSrc);
        $this->photoNameRandomized = end($array);
    }
}

<?php

namespace Api\Repository;

use Api\Helper\ResponseError;
use Api\Infra\GlobalConn;
use Api\Infra\UploadImages;
use Api\Interface\ImageInterface;
use Api\Model\UsuarioFoto;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class RepoFotos extends GlobalConn implements ImageInterface
{
    use ResponseError;

    public function __destruct()
    {
    }

    public function saveFoto(UsuarioFoto $foto): array
    {
        try {
            //CHECK IF USER EXISTS
            $this->selectUserFoto($foto->getIdUser());
            //VERIFY IF USER HAVE FOTO FOR ADD OR UPDATE
            if (self::selectFoto($foto)) {
                return self::updFoto($foto);
            }
            return self::addFoto($foto);
        } catch (Exception) {
            $this->responseCatchError('Não foi possível SALVAR foto do usuário tente novamente');
        }
    }

    private function selectUserFoto($idUser): array
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM usuario where id_user=:idUser"
            );
            $stmt->bindValue(':idUser', $idUser);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return $stmt->fetchAll();
        } catch (Exception) {
            $this->responseCatchError('Não existe este usuário  para adicionar ou atualizar foto!');
        }
    }

    private function selectFoto(UsuarioFoto $foto): bool|array
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM usuario_foto JOIN usuario u on usuario_foto.id_foto = u.id_foto WHERE id_user=:idUser"
            );
            $stmt->bindValue(':idUser', $foto->getIdUser());
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return $stmt->fetchAll();
        } catch (Exception) {
            return false;
        }
    }

    private function updFoto(UsuarioFoto $foto): array
    {
        try {
            //     Create Directory with image before insert into MYSQL AND refresh old images
            //============================================================//
            array_map('unlink', glob(DIR_IMG . $foto->getIdUser() . '/*'));
            (new UploadImages())->saveImgResized($foto, true);
            $stmt = self::conn()->prepare(
                'UPDATE usuario_foto AS UF, usuario AS U 
                                SET UF.nome_foto = :fotoNome, 
                                    UF.src = :src, UF.tamanho = :tamanho 
                                WHERE UF.id_foto = U.id_foto AND U.id_user = :idUser'
            );
            $stmt->bindValue(':idUser', $foto->getIdUser());
            $stmt->bindValue(':fotoNome', $foto->getPhotoNameRandomized());
            $stmt->bindValue(':src', $foto->getPhotoSrc());
            $stmt->bindValue(':tamanho', 'width_' .
                $foto->getPhotoCustomWidth() . ' / ' .
                'heigth_' . $foto->getPhotoCustomHeight());
            $stmt->execute();
            if ($stmt->rowCount() < 0) {
                throw new Exception();
            }
            return [
                'data' => $foto->getPhotoSrc(),
                'status' => 'success',
                'code' => 201,
                "message" => "Imagem <strong> " . $foto->getPhotoName() . " </strong> Atualizada com sucesso!"
            ];
        } catch (Exception) {
            $this->responseCatchError('Imagens não atualizada confira os ids.');
        }
    }

    #[ArrayShape(['data' => "null|string", 'status' => "string", 'code' => "int", "message" => "string"])]
    private function addFoto(UsuarioFoto $foto): array
    {
        try {
            //     Create Directory with image before insert into MYSQL AND refresh old images
            //============================================================//
            array_map('unlink', glob(DIR_IMG . $foto->getIdUser() . '/*'));
            (new UploadImages())->saveImgResized($foto, true);
            $pdo = self::conn();
            $stmt = $pdo->prepare(
                'INSERT INTO usuario_foto (nome_foto, src, tamanho) VALUES(:fotoNome,:src,:tamanho)'
            );
            $stmt->bindValue(':fotoNome', $foto->getPhotoNameRandomized());
            $stmt->bindValue(':src', $foto->getPhotoSrc());
            $stmt->bindValue(':tamanho', 'width_' .
                $foto->getPhotoCustomWidth() . ' / ' .
                'heigth_' . $foto->getPhotoCustomHeight());
            $stmt->execute();

            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }

            //After includes photo data in table, get lastId for insert on FK id_foto in usuario
            $idFoto = $pdo->lastInsertId();
            $stmt = self::conn()->prepare("UPDATE usuario SET id_foto = :idFoto WHERE id_user = :idUser");
            $stmt->bindValue(':idUser', $foto->getIdUser());
            $stmt->bindValue(':idFoto', $idFoto);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }

            return [
                'data' => $foto->getPhotoSrc(),
                'status' => 'success',
                'code' => 201,
                "message" => "Imagem <strong> " . $foto->getPhotoName() . " </strong> salva com sucesso!"
            ];
        } catch (Exception) {
            $this->responseCatchError('Imagens não adicionada confira os ids ou tamanho e nome da foto.');
        }
    }
}

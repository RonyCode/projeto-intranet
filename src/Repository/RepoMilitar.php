<?php

namespace Api\Repository;

use Api\Helper\ResponseError;
use Api\Infra\GlobalConn;
use Api\Interface\MilitarInterface;
use Api\Model\Militar;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;

class RepoMilitar extends GlobalConn implements MilitarInterface
{
    use ResponseError;

    public function __construct()
    {
    }

    #[ArrayShape(['data' => "array", 'status' => "string", 'code' => "int"])]
    public function getAllMilitar(): array
    {
        try {
            $stmt = self::conn()->prepare("SELECT * FROM militar");
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            $militar = self::hidrateMilitarList($stmt);
            return ['data' => $militar, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Não foi possível listar todos os militares");
        }
    }

    private static function hidrateMilitarList(PDOStatement $stmt): array
    {
        $user = [];
        $userData = $stmt->fetchAll();
        foreach ($userData as $data) {
            $user[] = self::newObjMilitar($data)->dataSerialize();
        }
        return $user;
    }

    #[Pure] private static function newObjMilitar($data): Militar
    {
        return new Militar(
            $data['id_m'],
            $data['nome'],
            $data['nome_guerra'],
            $data['posto_graduacao'],
            $data["rg_militar"],
            $data['tipo_sangue'],
            $data['lotacao'],
            $data['funcao'],
        );
    }

    public function saveMilitar(Militar $militar): array
    {
        try {
            if (self::selectMilitar($militar)) {
                return self::updMilitar($militar);
            }
            return self::addMilitar($militar);
        } catch (Exception) {
            $this->responseCatchError('Não foi possível SALVAR militar tente novamente');
        }
    }

    private function selectMilitar(Militar $militar): bool
    {
        try {
            $stmt = self::conn()->prepare("SELECT * FROM militar m JOIN usuario u on m.id_m = u.id_militar 
                                                                                                WHERE id_m = :idM");
            $stmt->bindValue(":idM", $militar->getIdM());
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return true;
        } catch (Exception) {
            $this->responseCatchError('Não existe usuário com este militar!');
        }
    }

    #[ArrayShape(['data' => "bool", 'status' => "string", 'code' => "int", "message" => "string"])]
    private function updMilitar(Militar $militar): array
    {
        try {
            $stmt = self::conn()->prepare("UPDATE militar SET 
                   nome=:nome, nome_guerra = :nomeGuerra,
                   posto_graduacao = :postoGraduacao,
                   rg_militar = :rgMilitar, tipo_sangue = :tipoSangue,
                   lotacao = :lotacao, funcao = :funcao WHERE id_m = :idM ");

            $stmt->bindValue(":idM", $militar->getIdM(), PDO::PARAM_INT);
            $stmt->bindValue(":nome", $militar->getNome(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":nomeGuerra", $militar->getNomeGuerra(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":postoGraduacao", $militar->getPostoGraduacao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":rgMilitar", $militar->getRgMilitar(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":tipoSangue", $militar->getTipoSangue(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":lotacao", $militar->getLotacao(), PDO::PARAM_INT);
            $stmt->bindValue(":funcao", $militar->getFuncao(), PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200, "message" => "Militar atualizado com sucesso!"];
        } catch (Exception) {
            $this->responseCatchError('Não foi possível ATUALIZAR Militar tente novamente');
        }
    }

    private function addMilitar(Militar $militar): array
    {
        try {
            $pdo = self::conn();
            $stmt = $pdo->prepare("INSERT INTO militar 
                    (nome, nome_guerra, posto_graduacao,  rg_militar, tipo_sangue, funcao) 
            VALUES (:nome, :nomeGuerra, :postoGraduacao,  :rgMilitar, :tipoSangue, :funcao)");
            $stmt->bindValue(":nome", $militar->getNome(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":nomeGuerra", $militar->getNomeGuerra(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":postoGraduacao", $militar->getPostoGraduacao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":rgMilitar", $militar->getRgMilitar(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":tipoSangue", $militar->getTipoSangue(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":funcao", $militar->getFuncao(), PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }

            return [
                'data' => true,
                'status' => 'success',
                'code' => 200, "message" => "Militar cadastrado com sucesso!
                    "];
        } catch (Exception) {
            $this->responseCatchError(
                'Não foi possível CADASTRAR Militar, os CAMPOS matricula e rg_militar são UNIQUE  tente novamente'
            );
        }
    }

    private function deleteMilitar(Militar $militar): array
    {
        try {
            $stmt = self::conn()->prepare("DELETE FROM militar WHERE id_m = :idM");
            $stmt->bindValue(":idM", $militar->getIdM(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return $stmt->fetchAll();
        } catch (Exception) {
            $this->responseCatchError('Não foi possível DELETAR militar tente novamente');
        }
    }

}

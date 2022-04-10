<?php


namespace Api\Repository;

use Api\Helper\ResponseError;
use Api\Helper\ValidateParams;
use Api\Infra\GlobalConn;
use Api\Interface\PrivilegiosInterface;
use Api\Model\Privilegio;
use Exception;
use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;

class RepoPrivilegios extends GlobalConn implements PrivilegiosInterface
{
    use ResponseError;


    public function __construct()
    {
    }

    public function getAllPriv(): array
    {
        try {
            $stmt = self::conn()->prepare("SELECT * FROM privilegio");
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            $student = self::hidratePrivList($stmt);
            return ['data' => $student, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Não foi possível listar todos os privilégios");
        }
    }

    private static function hidratePrivList(PDOStatement $stmt): array
    {
        $priv = [];
        $userData = $stmt->fetchAll();
        foreach ($userData as $data) {
            $priv[] = self::newObjPriv($data)->dataSerialize();
        }
        return $priv;
    }

    #[Pure] private static function newObjPriv($data): Privilegio
    {
        $dataConcessao = (new ValidateParams())->dateFormatDbToBr($data['data_concessao']);
        return new Privilegio(
            $data['id_priv'],
            $data['super_priv'],
            $data['servico'],
            $data['sub_servico'],
            $dataConcessao,
            $data['resp_concessao'],
        );
    }

    public function selectPriv(Privilegio $privilegio): array
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM privilegio WHERE id_priv = :idPriv"
            );
            $stmt->bindValue(':idPriv', $privilegio->getIdPriv(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return $stmt->fetch();
        } catch (Exception) {
            $this->responseCatchError(
                'privilégio não encontrado no banco de dados , confira o código de privilégio e tente novamente.'
            );
        }
    }

    public function savePriv(Privilegio $privilegio): array
    {
//        CHECK IF USER EXISTS
//        $this->selectUserPriv($privilegio->getIdUser());
//        VERIFY IF USER HAVE FOTO FOR ADD OR UPDATE
        if ($privilegio->getIdPriv()) {
            return self::updPriv($privilegio);
        } else {
            return self::addPriv($privilegio);
        }
    }

    private function updPriv(Privilegio $privilegio): array
    {
        try {
            $stmt = self::conn()->prepare(
                'UPDATE privilegio SET 
                    super_priv = :superPriv,
                    servicos = :servico,
                    sub_servico = :subServico, 
                    data_concessao = :dataConcessao,
                    resp_concessao = :respConcessao
                       WHERE id_priv = :idPriv'
            );
            $stmt->bindValue(':idPriv', $privilegio->getIdPriv(), PDO::PARAM_INT);
            $stmt->bindValue(':superPriv', $privilegio->getSuperPriv(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':servico', $privilegio->getServico(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':subServico', $privilegio->getSubServico(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataConcessao', $privilegio->getDataConcessao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':respConcessao', $privilegio->getRespConcessao(), PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Privilégio não encontrado, privilégio já cadastrado ou já atualizado");
        }
    }

    private function addPriv(Privilegio $privilegio): array
    {
        try {
            $stmt = self::conn()->prepare(
                'INSERT INTO privilegio (
                                super_priv, servicos, sub_servico, data_concessao, resp_concessao) 
                       VALUES (:superPriv, :servico, :subServico, :dataConcessao,:respConcessao)'
            );
            $stmt->bindValue(':superPriv', $privilegio->getSuperPriv(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':servico', $privilegio->getServico(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':subServico', $privilegio->getSubServico(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataConcessao', $privilegio->getDataConcessao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':respConcessao', $privilegio->getRespConcessao(), PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Privilégio não encontrado, privilégio já cadastrado ou já atualizado");
        }
    }

    public function delPriv(Privilegio $privilegio): array
    {
        try {
            $stmt = self::conn()->prepare('DELETE FROM privilegio WHERE id_priv = :idPriv');
            $stmt->bindValue(':idPriv', $privilegio->getIdPriv(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Privilégio não encontrado, ou já deletado.");
        }
    }

    private function selectUserPriv($idUser)
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM privilegio JOIN servico s on privilegio.sub_servico = s.sub_servico WHERE id_priv = :idPriv"
            );
            $stmt->bindValue(':idPriv', $idUser->getIdPriv(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return $stmt->fetch();
        } catch (Exception) {
            $this->responseCatchError(
                'privilégio não encontrado no banco de dados , confira o código de privilégio e tente novamente.'
            );
        }

    }

}
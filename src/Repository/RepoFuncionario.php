<?php

namespace Api\Repository;

use Api\Helper\ResponseError;
use Api\Helper\ValidateParams;
use Api\Infra\GlobalConn;
use Api\Interface\FuncionarioInterface;
use Api\Model\Funcionario;
use Exception;
use PDO;
use PDOStatement;

class RepoFuncionario extends GlobalConn implements FuncionarioInterface
{
    use ResponseError;

    public function __construct()
    {
    }

    public function getAllFuncionario(): array
    {
        try {
            $stmt = self::conn()->prepare("SELECT * FROM FUNCIONARIO");
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            $student = self::hidrateFuncionarioList($stmt);
            return ['data' => $student, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Não foi possível listar todos os alunos");
        }
    }

    private function hidrateFuncionarioList(PDOStatement $stmt): array
    {
        $student = [];
        $stdData = $stmt->fetchAll();
        foreach ($stdData as $data) {
            $student[] = self::newObjFuncionario($data)->dataSerialize();
        }
        return $student;
    }

    private function newObjFuncionario($data): Funcionario
    {

        $dataIncludao = (new ValidateParams())->dateFormatDbToBr($data['DATAINCLUSAO']);
        $dataNascimento = (new ValidateParams())->dateFormatDbToBr($data['DATANASCIMENTO']);
        $dataExpedicao = (new ValidateParams())->dateTimeFormatDbToBr($data['DATAEXPEDICAO']);
        $dataSituacao = (new ValidateParams())->dateTimeFormatDbToBr($data['DATASITUACAO']);

        return new Funcionario(
            $data["CODFUNC"],
            $data["MATRICULA"],
            $data["MATRICULANOVA"],
            $data["VINCULO"],
            $data["RG"],
            $data["ORGAOEMISSOR"],
            $dataIncludao,
            $data["NOME"],
            $data["NOMEGUERRA"],
            $data["POSTOGRADUACAO"],
            $data["CODPOSTOGRADUACAO_QUADROS"],
            $data["NOMEPAI"],
            $data["NOMEMAE"],
            $data["TS"],
            $data["FRH"],
            $data["NATURALIDADE"],
            $data["UFNATURALIDADE"],
            $data["FD1"],
            $data["FD2"],
            $dataNascimento,
            $data["CPF"],
            $data["PASEP"],
            $data["REGNASCIMENTO"],
            $data["LOCALEXPEDICAO"],
            $dataExpedicao,
            $data["TIPO"],
            $data["SITUACAO"],
            $data["FOTO"],
            $dataSituacao
        );
    }

    public function selectFuncionario(Funcionario $funcionario): array
    {
        try {
            $stmt = self::conn()->prepare('SELECT * FROM FUNCIONARIO WHERE CODFUNC = :id');
            $stmt->bindValue(':id', $funcionario->getCodFuncionario(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $student = self::hidrateFuncionarioList($stmt);
                return ['data' => $student, 'status' => 'success', 'code' => 200];
            } else {
                throw new Exception();
            }
        } catch (Exception) {
            $this->responseCatchError("Funcionário não encontrado, ou não cadastrado");
        }
    }

    public function saveFuncionario(Funcionario $funcionario): array
    {
        if ($funcionario->getCodFuncionario()) {
            return self::updateFuncionario($funcionario);
        } else {
            return self::addFuncionario($funcionario);
        }
    }

    private function updateFuncionario(Funcionario $funcionario): array
    {
        try {
            $stmt = self::conn()->prepare(
                'UPDATE FUNCIONARIO SET 
                    MATRICULA = :matricula ,
                    MATRICULANOVA = :matriculaNova, 
                    VINCULO = :vinculo, 
                    RG = :rg, 
                    ORGAOEMISSOR = :orgaoEmissor,
                    DATAINCLUSAO = :dataInclusao, 
                    NOME = :nome, 
                    NOMEGUERRA  = :nomeguerra,
                    POSTOGRADUACAO = :postoGraduacao,
                    CODPOSTOGRADUACAO_QUADROS = :codPostoGraduacao,
                    NOMEPAI = :nomePai,
                    NOMEMAE = :nomeMae,
                    TS = :ts,
                    FRH = :frh,
                    NATURALIDADE = :naturalidade,
                    UFNATURALIDADE = :ufNaturalidade,
                    FD1 = :fd1,
                    FD2 = :fd2,
                    DATANASCIMENTO = :dataNascimento,
                    CPF = :cpf,
                    PASEP = :pasep,
                    REGNASCIMENTO = :regNascimento,
                    LOCALEXPEDICAO = :localExpedicao,
                    DATAEXPEDICAO = :dataExpedicao,
                    TIPO = :tipo,
                    SITUACAO = :situacao,
                    FOTO = :foto,
                    DATASITUACAO = :dataSituacao 
                    WHERE CODFUNC = :id'
            );
            $stmt->bindValue(':id', $funcionario->getCodFuncionario(), PDO::PARAM_INT);
            $stmt->bindValue(':matricula', $funcionario->getMatricula(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':matriculaNova', $funcionario->getNovaMatricula(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':vinculo', $funcionario->getVinculo(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':rg', $funcionario->getRg(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':orgaoEmissor', $funcionario->getOrgaoEmissor(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataInclusao', $funcionario->getDataInclusao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nome', $funcionario->getNome(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nomeGuerra', $funcionario->getNomeGuerra(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':postoGraduacao', $funcionario->getPostoGraduacao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':codPostoGraduacao', $funcionario->getCodPostoGraduacaoQuadros(), PDO::PARAM_INT);
            $stmt->bindValue(':nomePai', $funcionario->getNomePai(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nomeMae', $funcionario->getNomeMae(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':ts', $funcionario->getTs(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':frh', $funcionario->getFRH(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':naturalidade', $funcionario->getNaturalidade(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':ufNaturalidade', $funcionario->getUfNaturalidade(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':fd1', $funcionario->getFd1(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':fd2', $funcionario->getFd2(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataNascimento', $funcionario->getDataNascimento(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':cpf', $funcionario->getCpf(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':pasep', $funcionario->getPasep(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':regNascimento', $funcionario->getRegNascimento(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':localExpedicao', $funcionario->getLocalExpedicao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataExpedicao', $funcionario->getDataExpedicao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':tipo', $funcionario->getTipo(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':situacao', $funcionario->getSituacao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':foto', $funcionario->getFoto(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataSituacao', $funcionario->getDataSituacao(), PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Funcionário não encontrado, já cadastrado ou funcionário já atualizado");
        }
    }

    private function addFuncionario(Funcionario $funcionario): array
    {
        try {
            $stmt = self::conn()->prepare(
                "INSERT INTO FUNCIONARIO (  
                    MATRICULA, MATRICULANOVA, 
                    VINCULO, RG, ORGAOEMISSOR,
                    DATAINCLUSAO, NOME ,
                    NOMEGUERRA, POSTOGRADUACAO ,
                    CODPOSTOGRADUACAO_QUADROS, 
                    NOMEPAI, NOMEMAE,
                    TS, FRH, NATURALIDADE, 
                    UFNATURALIDADE, FD1 ,
                    FD2, DATANASCIMENTO,
                    CPF, PASEP, REGNASCIMENTO, 
                    LOCALEXPEDICAO, DATAEXPEDICAO,TIPO,
                    SITUACAO, FOTO, DATASITUACAO
                    )  VALUES ( 
                                :matricula, :matriculaNova,:vinculo, 
                                :rg, :orgaoEmissor, 
                                :dataInclusao, :nome,
                                :nomeGuerra, :postoGraduacao,
                                :codPostoGraduacao, :nomePai,
                                :nomeMae, :ts, :frh, :naturalidade,
                                :ufNaturalidade, :fd1, :fd2, :dataNascimento,
                                :cpf, :pasep, :regNascimento, :localExpedicao,
                                :dataExpedicao,:tipo, :situacao, :foto, :dataSituacao
                                ) "
            );
            $stmt->bindValue(':matricula', $funcionario->getMatricula(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':matriculaNova', $funcionario->getNovaMatricula(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':vinculo', $funcionario->getVinculo(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':rg', $funcionario->getRg(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':orgaoEmissor', $funcionario->getOrgaoEmissor(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataInclusao', $funcionario->getDataInclusao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nome', $funcionario->getNome(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nomeGuerra', $funcionario->getNomeGuerra(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':postoGraduacao', $funcionario->getPostoGraduacao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':codPostoGraduacao', $funcionario->getCodPostoGraduacaoQuadros(), PDO::PARAM_INT);
            $stmt->bindValue(':nomePai', $funcionario->getNomePai(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':nomeMae', $funcionario->getNomeMae(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':ts', $funcionario->getTs(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':frh', $funcionario->getFRH(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':naturalidade', $funcionario->getNaturalidade(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':ufNaturalidade', $funcionario->getUfNaturalidade(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':fd1', $funcionario->getFd1(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':fd2', $funcionario->getFd2(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataNascimento', $funcionario->getDataNascimento(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':cpf', $funcionario->getCpf(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':pasep', $funcionario->getPasep(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':regNascimento', $funcionario->getRegNascimento(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':localExpedicao', $funcionario->getLocalExpedicao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataExpedicao', $funcionario->getDataExpedicao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':tipo', $funcionario->getTipo(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':situacao', $funcionario->getSituacao(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':foto', $funcionario->getFoto(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataSituacao', $funcionario->getDataSituacao(), PDO::PARAM_STR_CHAR);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200, "message" => "Cadastrado com sucesso!"];
        } catch (Exception) {
            $this->responseCatchError(
                "Funcionário já cadastrado problemas com campos ou banco de dados."
            );
        }
    }

    public function deleteFuncionario(Funcionario $funcionario): array
    {
        try {
            $stmt = self::conn()->prepare('DELETE FROM FUNCIONARIO WHERE CODFUNC = :id');
            $stmt->bindValue(':id', $funcionario->getCodFuncionario(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return ['data' => true, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Usuário não encontrado, ou já deletado.");
        }
    }
}

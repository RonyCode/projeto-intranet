<?php

namespace Api\Repository;

use Api\Helper\JwtHandler;
use Api\Helper\ResponseError;
use Api\Helper\TemplateEmail;
use Api\Helper\ValidateParams;
use Api\Infra\EmailForClient;
use Api\Infra\GlobalConn;
use Api\Interface\UserInterface;
use Api\Model\Usuario;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;

class RepoUsers extends GlobalConn implements UserInterface
{
    use ResponseError;
    use TemplateEmail;

    public function __construct()
    {
    }

    public function getAllUser(): array
    {
        try {
            $stmt = self::conn()->prepare("SELECT * FROM usuario");
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            $usuario = self::hidrateUserList($stmt);
            return ['data' => $usuario, 'status' => 'success', 'code' => 200];
        } catch (Exception) {
            $this->responseCatchError("Não foi possível listar todos os usuários");
        }
    }

    private static function hidrateUserList(PDOStatement $stmt): array
    {
        $user = [];
        $userData = $stmt->fetchAll();
        foreach ($userData as $data) {
            $user[] = self::newObjUser($data)->dataSerialize();
        }
        return $user;
    }

    #[Pure] private static function newObjUser($data): Usuario
    {
        $nascimento = (new ValidateParams())->dateFormatDbToBr($data['nascimento']);
        $dataUser = (new ValidateParams())->dateTimeFormatDbToBr($data['data_user']);
        $dataExpRg = (new ValidateParams())->dateFormatDbToBr($data['data_exp_rg']);
        $dataInclusao = (new ValidateParams())->dateFormatDbToBr($data['data_inclusao']);
        $dataSituacao = (new ValidateParams())->dateFormatDbToBr($data['data_situacao']);

        return new Usuario(
            $data['id_user'],
            $data['nome'],
            $dataUser,
            $data['email'],
            $data['senha'],
            $data['cpf'],
            $nascimento,
            $data["rg"],
            $data['orgao_exp'],
            $dataExpRg,
            $data['local_exp'],
            $data['naturalidade'],
            $data['nome_pai'],
            $data['nome_mae'],
            $dataInclusao,
            $data['matricula'],
            $data['pasep'],
            $data['reg_nascimento'],
            $dataSituacao,
            $data['id_foto'],
            $data['id_militar'],
            $data['id_senha_respawn']
        );
    }

    public function userAuthToken(Usuario $user): array
    {
        try {
            $row = $this->selectUserByCPF($user);
            if (!password_verify($user->getSenha(), $row["senha"])) {
                throw new Exception();
            }
            $jwt = (new JwtHandler())->jwtEncode(
                'localhost/api-intranet-proj/public/ by Ronycode',
                [$row['email'], $row['id_user']]
            );
            return [
                'id' => $row['id_user'],
                'email' => $row['email'],
                'nome' => $row['nome'],
                'token' => $jwt,
                'id_photo' => $row['id_foto'],
                'status' => 'success',
                'code' => 201,
            ];
        } catch (Exception) {
            $this->responseCatchError("Não autenticado, area restrita, verifique o login novamente");
        }
    }

    private function selectUserByCPF(Usuario $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM usuario WHERE cpf = :cpf"
            );
            $stmt->bindValue(':cpf', $user->getCpf(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }

            return $stmt->fetch();
        } catch (Exception) {
            $this->responseCatchError(
                'Usuário com este CPF não encontrado no banco de dados , tente novamente.'
            );
        }
    }

    public function selectUserByIdUser(Usuario $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM usuario WHERE id_user = :idUser"
            );
            $stmt->bindValue(':idUser', $user->getIdUser(), PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return $stmt->fetch();
        } catch (Exception) {
            $this->responseCatchError(
                'Usuário com este ID não encontrado no banco de dados , tente novamente.'
            );
        }
    }

    public function checkHashEmail(Usuario $user, $hash): array
    {
        try {
            $stmt = $this->selectUserByCPF($user);
            $stmtHash = $this->selectHashTmp($user);

            //VERIFIED TWICE TABLE USER_AUTH AND HASH_TEMP AND HASH SENT FROM EMAIL NO EXPIRED
            if (!password_verify($stmt['email'], str_replace(" ", "+", $hash))) {
                throw new Exception();
            }
            if (!password_verify($stmt['email'], $stmtHash["hash_temp"])) {
                throw new Exception();
            }
            // ONCE TIME PASSES OF CONSULT, DELETE HASH TO USE ONCE TIME
            $hashOnce = self::conn()->prepare(
                "UPDATE senha_respawn SET hash_temp = null WHERE cpf = :cpf"
            );
            $hashOnce->bindValue(":cpf", $user->getCpf(), PDO::PARAM_STR_CHAR);
            $hashOnce->execute();
            return $this->resetPass($user);
        } catch (Exception) {
            $this->responseCatchError('hash expirada ou inválida');
        }
    }

    public function selectHashTmp(Usuario $user): array
    {
        try {
            //CHECKS IF USER EXIST AND CALL THE HASH VALID AND NO EXPIRED
            $this->selectUserByCPF($user);
            $hash_temp = self::conn()->prepare("SELECT * FROM senha_respawn  WHERE  cpf = :cpf");
            $hash_temp->bindValue(":cpf", $user->getCpf(), PDO::PARAM_STR_CHAR);
            $hash_temp->execute();
            if ($hash_temp->rowCount() <= 0) {
                throw  new Exception();
            }
            return $hash_temp->fetch();
        } catch (Exception) {
            $this->responseCatchError(
                'CPF não encontrado Ou link do HASH pelo e-mail expirado!'
            );
        }
    }

    private function resetPass(Usuario $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "UPDATE usuario SET senha = :pass WHERE cpf = :cpf"
            );
            $stmt->bindValue(":cpf", $user->getCpf(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(":pass", password_hash($user->getSenha(), PASSWORD_ARGON2I));
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            $row = $stmt->fetch();
            return [
                'data' => $row,
                'status' => 'success',
                'code' => 201,
                "message" => "Tudo certo ! nova senha validada."
            ];
        } catch (Exception) {
            $this->responseCatchError('Não foi possível concluir a validação da nova senha (hash), tente novamente');
        }
    }

    public function recoverPass(Usuario $user): array
    {

        try {
            $userFetch = $this->selectUserByCPF($user);
            $stmtUp = self::conn()->prepare(
                "INSERT INTO  senha_respawn (name_user, cpf, hash_temp, last_date_modif, date_expires) 
                                           VALUES (:name, :cpf, :hash_temp, :last_date_modif, :date_expires) "
            );

            $stmtUp->bindValue(":name", $userFetch["nome"], PDO::PARAM_STR_CHAR);
            $stmtUp->bindValue(":cpf", $user->getCpf(), PDO::PARAM_STR_CHAR);
            $stmtUp->bindValue(":hash_temp", password_hash($userFetch["email"], PASSWORD_ARGON2I));
            $stmtUp->bindValue(":last_date_modif", date('Y-m-d H:i:s'), PDO::PARAM_STR_CHAR);
            //HASH TEMP EXPIRES AFTER 24 HOURS CHECK THE FOLDER CONFIG , AND EVENT MYSQL ON CONFIG.PHP
            $stmtUp->bindValue(":date_expires", date('Y-m-d H:i:s', (strtotime('+ 24 hour'))), PDO::PARAM_STR_CHAR);
            $stmtUp->execute();
            if ($stmtUp->rowCount() <= 0) {
                throw  new Exception();
            } else {
                $rowHash = $this->selectHashTmp($user);
                $mail = (new EmailForClient())
                    ->add(
                        SUBJET_MAIL,
                        $this->bodyEmail(["user" => $userFetch["email"],
                            "hash" => $rowHash["hash_temp"]]),
                        $userFetch['email'],
                        FROM_NAME_MAIL
                    )
                    ->send();
            }
            return [
                'data' => $mail,
                'status' => 'success',
                'code' => 201,
                "message" => "Email enviado para recuperar sua senha"
            ];
        } catch (Exception) {
            $this->responseCatchError("Usuário não encontrado, verifique se o CPF está correto");
        }
    }

    #[ArrayShape(['data' => "bool", 'status' => "string", 'code' => "int", "message" => "string"])]
    public function addUser(Usuario $user): array
    {
        try {
//            CONFIG FIELDS CPF AND EMAIL UNIQUE REGISTER FOR NO ERRORS!!! SEE FOLDER CONFIG FILE CONFIG.PHP
            $stmt = self::conn()->prepare("INSERT INTO usuario (matricula, cpf, nascimento, email, senha)
                                                             VALUES (:matricula, :cpf, :dataNasc, :email, :senha)");
            $stmt->bindValue(':matricula', $user->getMatricula(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':cpf', $user->getCpf(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':dataNasc', $user->getNascimento(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR_CHAR);
            $stmt->bindValue(':senha', password_hash($user->getSenha(), PASSWORD_ARGON2I));
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            return [
                'data' => true,
                'status' => 'success',
                'code' => 200,
                "message" => 'Usuário cadastrado com sucesso!'
            ];
        } catch (Exception) {
            $this->responseCatchError(
                'Usuário não pode ter o mesmo CPF, MATRICULA, ou EMAIL confira os posts enviados tente novamente.'
            );
        }
    }
}

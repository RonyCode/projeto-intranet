<?php

namespace Api\Repository;

use Api\Helper\JwtHandler;
use Api\Helper\ResponseError;
use Api\Helper\TemplateEmail;
use Api\Helper\ValidateParams;
use Api\Infra\EmailForClient;
use Api\Infra\GlobalConn;
use Api\Interface\UserInterface;
use Api\Model\User;
use Exception;
use JetBrains\PhpStorm\Pure;
use PDOStatement;

class RepoUsers extends GlobalConn implements UserInterface
{
    use ResponseError;
    use TemplateEmail;

    public function __construct()
    {
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

    #[Pure] private static function newObjUser($data): User
    {

        $birthday = (new ValidateParams())->dateFormatDbToBr($data['NASCIMENTO']);
        $dateHour = (new ValidateParams())->dateTimeFormatDbToBr($data['DATAHORA']);
        $date_pass = (new ValidateParams())->dateTimeFormatDbToBr($data['DATASENHA']);
        $lastAccess = (new ValidateParams())->dateTimeFormatDbToBr($data['ULTIMOACESSO']);

        return new User(
            $data['CODUSUARIO'],
            $dateHour,
            $data['NOME'],
            $data['MATRICULA'],
            $data['CPF'],
            $data['SENHA'],
            $date_pass,
            $data['RESPSENHA'],
            $data['TIPO'],
            $data['SITUACAO'],
            $birthday,
            $data['EMAIL'],
            $lastAccess,
            $data['CONF'],
            $data['FOTO']
        );
    }

    public function userAuthToken(User $user): array
    {
        try {
            $row = $this->selectUser($user);

            if (!password_verify($user->getPass(), $row["SENHA"])) {
                throw new Exception();
            }

            $jwt = (new JwtHandler())->jwtEncode(
                'localhost/api-intranet-proj/public/ by Ronycode',
                [$row['EMAIL'], $row['CODUSUARIO']]
            );
            return [
                'id' => $row['CODUSUARIO'],
                'email' => $row['EMAIL'],
                'nome' => $row['NOME'],
                'token' => $jwt,
                'photo' => $row['FOTO'],
                'status' => 'success',
                'code' => 201,
            ];
        } catch (Exception) {
            $this->responseCatchError("Não autenticado, area restrita, verifique o login novamente");
        }
    }

    public function selectUser(User $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "SELECT * FROM AUT_USER WHERE CPF = :cpf"
            );
            $stmt->bindValue(':cpf', $user->getCpf());
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

    public function checkHashEmail(User $user, $hash): array
    {
        try {
            $stmt = $this->selectUser($user);
            $stmtHash = $this->selectHashTmp($user);

            //VERIFIED TWICE TABLE USER_AUTH AND HASH_TEMP AND HASH SENT FROM EMAIL NO EXPIRED
            if (password_verify($stmt['EMAIL'], str_replace(" ", "+", $hash))) {
                throw new Exception();
            }
            if (!password_verify($stmt['EMAIL'], $stmtHash["HASH_TEMP"])) {
                throw new Exception();
            }

            // ONCE TIME PASSES OF CONSULT, DELETE HASH TO USE ONCE TIME
            $hashOnce = self::conn()->prepare(
                "UPDATE SENHA_RECOVER_RESPAW SET HASH_TEMP = null WHERE CPF = :cpf"
            );
            $hashOnce->bindValue(":cpf", $user->getCpf());
            $hashOnce->execute();
            return $this->resetPass($user);
        } catch (Exception) {
            $this->responseCatchError('hash expirada ou inválida');
        }
    }

    public function selectHashTmp(User $user): array
    {
        try {
            //CHECKS IF USER EXIST AND CALL THE HASH VALID AND NO EXPIRED
            $this->selectUser($user);
            $hash_temp = self::conn()->prepare("SELECT * FROM SENHA_RECOVER_RESPAW  WHERE  CPF = :cpf");
            $hash_temp->bindValue(":cpf", $user->getCpf());
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

    private function resetPass(User $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "UPDATE AUT_USER SET SENHA = :pass WHERE CPF = :cpf"
            );
            $stmt->bindValue(":cpf", $user->getCpf());
            $stmt->bindValue(":pass", password_hash($user->getPass(), PASSWORD_ARGON2I));
            $stmt->execute();
            if ($stmt->rowCount() <= 0) {
                throw new Exception();
            }
            $row = $stmt->fetch();
            return [
                'data' => $row,
                'status' => 'success',
                'code' => 201,
                "message" => "Usuário verificado e validado!"
            ];
        } catch (Exception) {
            $this->responseCatchError('Senha nova já usada , ou inválida');
        }
    }

    public function recoverPass(User $user): array
    {

        try {
            $userFetch = $this->selectUser($user);

            $stmtUp = self::conn()->prepare(
                "INSERT INTO  SENHA_RECOVER_RESPAW (NAME_USER, CPF, HASH_TEMP, LAST_DATE_MODIF, DATE_EXPIRES) VALUES(:name, :cpf, :hash_temp, :last_date_modif, :date_expires) "
            );

            $stmtUp->bindValue(":name", $userFetch["NOME"]);
            $stmtUp->bindValue(":cpf", $user->getCpf());
            $stmtUp->bindValue(":hash_temp", password_hash($userFetch["EMAIL"], PASSWORD_ARGON2I));
            $stmtUp->bindValue(":last_date_modif", date('Y-m-d H:i:s'));
            //HASH TEMP EXPIRES AFTER 24 HOURS CHECK THE FOLDER CONFIG , AND EVENT MYSQL ON CONFIG.PHP
            $stmtUp->bindValue(":date_expires", date('Y-m-d H:i:s', (strtotime('+ 24 hour'))));
            $stmtUp->execute();
            if ($stmtUp->rowCount() <= 0) {

                throw  new Exception();
            }

            $rowHash = $this->selectHashTmp($user);
            $mail = (new EmailForClient())
                ->add(
                    SUBJET_MAIL,
                    $this->bodyEmail(["user" => $userFetch["EMAIL"],
                        "hash" => $rowHash["HASH_TEMP"]]),
                    $userFetch['EMAIL'],
                    FROM_NAME_MAIL
                )
                ->send();

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

    public function addUser(User $user): array
    {
        try {

//            CONFIG FIELDS CPF AND EMAIL UNIQUE REGISTER FOR NO ERRORS!!! SEE FOLDER CONFIG FILE CONFIG.PHP
            $stmt = self::conn()->prepare(
                "INSERT INTO AUT_USER (MATRICULA,CPF, NASCIMENTO,EMAIL,SENHA) VALUES(:matricula, :cpf, :dataNasc, :email, :pass)"
            );
            $stmt->bindValue(':matricula', $user->getRegistration());
            $stmt->bindValue(':cpf', $user->getCpf());
            $stmt->bindValue(':dataNasc', $user->getBirthday());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':pass', password_hash($user->getPass(), PASSWORD_ARGON2I));
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
                'Usuário já cadastrado ou não pode ser cadastrado com este CPF, tente novamente.'
            );
        }
    }
}

<?php

namespace Api\Repository;

use Api\Helper\JwtHandler;
use Api\Helper\ResponseError;
use Api\Helper\TemplateEmail;
use Api\Helper\ValidateParams;
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

    public function userAuth(User $user): array
    {
        try {
            $row = $this->selectUser($user);
            $validHash = password_verify($user->getPass(), $row["SENHA"]);
            if (!$validHash) {
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

    public function checkHashEmail(User $user): array
    {
        try {
            $userFetch = $this->selectUser($user);
            if (!password_verify($userFetch['SENHA'], $user->getPass())) {
                throw new Exception();
            }
            return $this->resetPass($user);
        } catch (Exception) {
            $this->responseCatchError('hash expirada ou inválida');
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
                "INSERT INTO  SENHA_RECOVER_RESPAW (NAME_USER, CPF, HASH_TEMP,LAST_DATE_MODIF,DATE_EXPIRES) VALUES(:name, :cpf, :hash_temp, :last_date_modif, :date_expires) "
            );

            $stmtUp->bindValue(":name", $userFetch["NOME"]);
            $stmtUp->bindValue(":cpf", $user->getCpf());
            $stmtUp->bindValue(":hash_temp", password_hash($userFetch["EMAIL"], PASSWORD_ARGON2I));
            $stmtUp->bindValue(":last_date_modif", date('Y-m-d H:i:s'));
            $stmtUp->bindValue(":date_expires", date('Y-m-d H:i:s', (strtotime('+ 1 min'))));
            $stmtUp->execute();
            if ($stmtUp->rowCount() <= 0) {
                throw new Exception();
            }
//            $mail = (new EmailForClient())
//                ->add(
//                    SUBJET_MAIL,
//                    $this->bodyEmail([$userFetch["EMAIL"],
//                        "hash" => $userFetch['SENHA']]),
//                    $userFetch['EMAIL'],
//                    FROM_NAME_MAIL
//                )
//                ->send();
            return [
//                'data' => $mail,
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
            $stmt = self::conn()->prepare(
                "INSERT INTO AUT_USER (REGISTRATION,CPF, NASCIMENTO,EMAIL,SENHA) VALUES(:matricula, :cpf, :dataNasc, :email, :pass)"
            );
            $stmt->bindValue(':matricula', $user->getRegistration());
            $stmt->bindValue(':cpf', $user->getName());
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
                'Usuário já cadastrado ou não pode ser cadastrado com este email, tente novamente.'
            );
        }
    }
}

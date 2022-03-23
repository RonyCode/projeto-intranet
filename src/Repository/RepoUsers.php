<?php

namespace Api\Repository;

use Api\Helper\JwtHandler;
use Api\Helper\ResponseError;
use Api\Helper\TemplateEmail;
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
        return new User(
            $data['CODUSUARIO'],
            $data['DATAHORA'],
            $data['NOME'],
            $data['MATRICULA'],
            $data['CPF'],
            $data['SENHA'],
            $data['DATASENHA'],
            $data['RESPSENHA'],
            $data['TIPO'],
            $data['SITUACAO'],
            $data['NASCIMENTO'],
            $data['EMAIL'],
            $data['ULTIMOACESSO'],
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
                'photo_name' => $row['FOTO'],
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
            $stmt->bindValue(':cpf', $user->getConf());
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

//    public function checkHashEmail(User $user): array
//    {
//        try {
//            $row = $this->selectUser($user);
//            if (!password_verify($row['email'], $user->getHash())) {
//                throw new Exception();
//            }
//            return $this->resetPass($user);
//        } catch (Exception) {
//            $this->responseCatchError('hash expirada ou inválida');
//        }
//    }

    private function resetPass(User $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "UPDATE user SET pass = :pass WHERE email = :email"
            );
            $stmt->bindValue(":email", $user->getEmail());
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
            $stmtUp = self::conn()->prepare(
                "UPDATE user SET hash = :hash, 
                date_hash = :date_hash WHERE email = :email"
            );
            $stmtUp->bindValue(":email", $user->getEmail());
            $stmtUp->bindValue(":hash", password_hash($user->getEmail(), PASSWORD_ARGON2I));
            $stmtUp->bindValue(":date_hash", date('Y-m-d H:i:s'));
            $stmtUp->execute();
            if ($stmtUp->rowCount() <= 0) {
                throw new Exception();
            }
            $row = $this->selectUser($user);
            $mail = (new EmailForClient())
                ->add(
                    SUBJET_MAIL,
                    $this->bodyEmail(['user' => $user->getEmail(),
                        "hash" => $row['hash']]),
                    $row['email'],
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
            $this->responseCatchError("Usuário não encontrado, verifique se o email está correto");
        }
    }

    public function addUser(User $user): array
    {
        try {
            $stmt = self::conn()->prepare(
                "INSERT INTO user (username,email, pass) VALUES(:username,:email, :pass)"
            );
            $stmt->bindValue(':username', $user->getUsername());
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

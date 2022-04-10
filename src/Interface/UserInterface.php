<?php

namespace Api\Interface;

use Api\Model\Usuario;

interface UserInterface
{
    public function userAuthToken(Usuario $user): array;

    public function selectUserByIdUser(Usuario $user): array;

    public function checkHashEmail(Usuario $user, $hash): array;

    public function selectHashTmp(Usuario $user): array;

    public function recoverPass(Usuario $user): array;

    public function addUser(Usuario $user): array;

}

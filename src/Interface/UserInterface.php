<?php

namespace Api\Interface;

use Api\Model\User;

interface UserInterface
{
    public function userAuthToken(User $user): array;

    public function selectUser(User $user): array;

    public function checkHashEmail(User $user, $hash): array;

    public function selectHashTmp(User $user): array;

    public function recoverPass(User $user): array;

    public function addUser(User $user): array;

}

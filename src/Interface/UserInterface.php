<?php

namespace Api\Interface;

use Api\Model\User;

interface UserInterface
{
    public function userAuth(User $user): array;

    public function recoverPass(User $user): array;

    public function addUser(User $user): array;

//    public function checkHashEmail(User $user): array;

}

<?php

namespace Api\Interface;

use Api\Model\Privilegio;

interface PrivilegiosInterface
{
    public function getAllPriv(): array;
    public function selectPriv(Privilegio $privilegio): array;
    public function savePriv(Privilegio $privilegio): array;
    public function delPriv(Privilegio $privilegio): array;



}
<?php

namespace Api\Interface;

use Api\Model\Funcionario;

interface FuncionarioInterface
{
    public function getAllFuncionario(): array;

    public function selectFuncionario(Funcionario $funcionario): array;

    public function deleteFuncionario(Funcionario $funcionario): array;

    public function saveFuncionario(Funcionario $funcionario): array;
}

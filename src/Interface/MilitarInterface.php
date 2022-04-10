<?php


namespace Api\Interface;


use Api\Model\Militar;

interface MilitarInterface
{
    public function saveMilitar(Militar $militar): array;
    public function getAllMilitar(): array;


}
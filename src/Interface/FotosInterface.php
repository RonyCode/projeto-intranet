<?php


namespace Api\Interface;


use Api\Model\UsuarioFoto;

interface FotosInterface
{
    public function saveFoto(UsuarioFoto $foto): array;

}
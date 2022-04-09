<?php

namespace Api\Model;

use Api\Helper\ValidateParams;

class User
{


    public function __construct(
        private ?int $idUser,
        private ?string $nome,
        private ?string $dataUser,
        private ?string $email,
        private ?string $senha,
        private ?string $cpf,
        private ?string $nascimento,
        private ?string $rg,
        private ?string $orgaoExp,
        private ?string $dataExp,
        private ?string $localExp,
        private ?string $naturalidade,
        private ?string $nomePai,
        private ?string $nomeMae,
        private ?string $dataInclusao,
        private ?string $matricula,
        private ?string $pasep,
        private ?string $regNascimento,
        private ?string $dataSituacao,
        private ?string $privilegio,
        private ?string $foto
    )
    {
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    /**
     * @return string|null
     */
    public function getNome(): ?string
    {
        return (new ValidateParams())->validateName($this->nome);
    }

    /**
     * @return string|null
     */
    public function getDataUser(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->dataUser);
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getSenha(): ?string
    {
        return $this->senha;
        // IF WANT, UNCOMMENT THIS FOR USE THE REGEX THAT VALIDATES PASSWORD WITH 8 CHARACTERS WITH AT LEAST 1 LETTER
//        return (new ValidateParams())->validatePass($this->pass);
    }

    /**
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return (new ValidateParams())->validateCpf($this->cpf);
    }

    /**
     * @return string|null
     */
    public function getNascimento(): ?string
    {
        return (new ValidateParams())->validateBirthday($this->nascimento);
    }

    /**
     * @return string|null
     */
    public function getRg(): ?string
    {
        return $this->rg;
    }

    /**
     * @return string|null
     */
    public function getOrgaoExp(): ?string
    {
        return $this->orgaoExp;
    }

    /**
     * @return string|null
     */
    public function getDataExp(): ?string
    {
        return (new ValidateParams())->dateFormatBrToDb($this->dataExp);
    }

    /**
     * @return string|null
     */
    public function getLocalExp(): ?string
    {
        return $this->localExp;
    }

    /**
     * @return string|null
     */
    public function getNaturalidade(): ?string
    {
        return $this->naturalidade;
    }

    /**
     * @return string|null
     */
    public function getNomePai(): ?string
    {
        return $this->nomePai;
    }

    /**
     * @return string|null
     */
    public function getNomeMae(): ?string
    {
        return $this->nomeMae;
    }

    /**
     * @return string|null
     */
    public function getDataInclusao(): ?string
    {
        return (new ValidateParams())->dateFormatBrToDb($this->dataInclusao);
    }

    /**
     * @return string|null
     */
    public function getMatricula(): ?string
    {
        return $this->matricula;
    }

    /**
     * @return string|null
     */
    public function getPasep(): ?string
    {
        return $this->pasep;
    }

    /**
     * @return string|null
     */
    public function getRegNascimento(): ?string
    {
        return $this->regNascimento;
    }

    /**
     * @return string|null
     */
    public function getDataSituacao(): ?string
    {
        return (new ValidateParams())->dateFormatBrToDb($this->dataSituacao);
    }

    /**
     * @return string|null
     */
    public function getPrivilegio(): ?string
    {
        return $this->privilegio;
    }

    /**
     * @return string|null
     */
    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }

}
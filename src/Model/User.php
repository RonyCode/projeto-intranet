<?php

namespace Api\Model;

use Api\Helper\ValidateParams;

class User
{
    public function __construct(
        private ?int $idUser,
        private ?string $dateHour,
        private ?string $name,
        private ?string $registration,
        private ?string $cpf,
        private ?string $pass,
        private ?string $datePass,
        private ?string $passAwnser,
        private ?string $type,
        private ?string $situation,
        private ?string $birthday,
        private ?string $email,
        private ?string $lastAccess,
        private ?int $conf,
        private ?string $photo,
    )
    {
    }

    /**
     * @return string|null
     */
    public function getDateHour(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->dateHour);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return (new ValidateParams())->validateName($this->name);
    }

    /**
     * @return string|null
     */
    public function getRegistration(): ?string
    {
        return $this->registration;
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
    public function getPass(): ?string
    {
        return $this->pass;
        // IF WANT, UNCOMMENT THIS FOR USE THE REGEX THAT VALIDATES PASSWORD WITH 8 CHARACTERS WITH AT LEAST 1 LETTER
//        return (new ValidateParams())->validatePass($this->pass);
    }

    /**
     * @return string|null
     */
    public function getDatePass(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->datePass);
    }

    /**
     * @return string|null
     */
    public function getPassAwnser(): ?string
    {
        return $this->passAwnser;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getSituation(): ?string
    {
        return $this->situation;
    }

    /**
     * @return string|null
     */
    public function getBirthday(): ?string
    {
        return (new ValidateParams())->validateBirthday($this->birthday);
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
    public function getLastAccess(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->lastAccess);
    }

    /**
     * @return int|null
     */
    public function getConf(): ?int
    {
        return $this->conf;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }
}
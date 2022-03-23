<?php

namespace Api\Model;

use Api\Helper\ValidateParams;
use Cassandra\Date;
use DateTime;

class User
{
    public function __construct(
        private ?int $idUser,
        private ?DateTime $dateHour,
        private ?string $name,
        private ?string $registration,
        private ?string $cpf,
        private ?string $pass,
        private ?DateTime $datePass,
        private ?string $passAwnser,
        private ?string $type,
        private ?string $situation,
        private ?Date $birthday,
        private ?string $email,
        private ?DateTime $lastAccess,
        private ?int $conf,
        private ?string $photo,
    )
    {
    }

    /**
     * @return DateTime|null
     */
    public function getDateHour(): ?DateTime
    {
        return $this->dateHour;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
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
        return (new ValidateParams())->validaCPF($this->cpf);
    }

    /**
     * @return string|null
     */
    public function getPass(): ?string
    {
        return $this->pass;
    }

    /**
     * @return DateTime|null
     */
    public function getDatePass(): ?DateTime
    {
        return $this->datePass;
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
     * @return DATE|null
     */
    public function getBirthday(): ?DATE
    {
        return $this->birthday;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return DateTime|null
     */
    public function getLastAccess(): ?DateTime
    {
        return $this->lastAccess;
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
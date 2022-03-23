<?php

namespace Api\Model;

use Api\Helper\ValidateParams;

class Student
{
    public function __construct(
        private ?int $id,
        private ?string $name,
        private ?string $phone,
        private ?string $email,
        private ?string $address,
        private ?string $birthday,
        private ?string $dayStudent,
        private ?int $contractNumber,
        private ?string $datePayment,
        private ?string $grade,
        private ?string $progress,
        private ?string $situation,
        private ?string $report,
        private ?string $responsible,
        private ?string $responsiblePhone
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return (new ValidateParams())->validateName($this->name);
    }

    public function getEmail(): ?string
    {
        return (new ValidateParams())->validateEmail($this->email);
    }

    public function getAddress(): ?string
    {
        return (new ValidateParams())->validateAddress($this->address);
    }

    public function getBirthday(): ?string
    {
        return (new ValidateParams())->validateBirthday($this->birthday);
    }

    public function getPhone(): ?string
    {
        return (new ValidateParams())->validatePhone($this->phone);
    }

    public function getAge(): ?string
    {
        return (new ValidateParams())->validateAge($this->birthday);
    }

    public function getDayStudent(): ?string
    {
        return $this->dayStudent;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }
    public function getProgress(): ?string
    {
        return $this->progress;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }

    public function getDatePayment(): ?string
    {
        return (new ValidateParams())->dateFormatBrToDb($this->datePayment);
    }

    public function getContractNumber(): ?int
    {
        return (new ValidateParams())->validateInteger($this->contractNumber);
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function getResponsible(): ?string
    {
        return $this->responsible;
    }

    public function getResponsiblePhone(): ?string
    {
        return (new ValidateParams())->validatePhone($this->responsiblePhone);
    }
}

<?php


namespace Api\Model;


use Api\Helper\ValidateParams;

class Privilegio
{
    public function __construct(

        private ?int $idPriv,
        private ?int $idUser,
        private ?string $superPriv,
        private ?string $servico,
        private ?string $subServico,
        private ?string $dataConcessao,
        private ?string $respConcessao,
        private ?string $lotacao,
        private ?string $funcao
    )
    {
    }

    /**
     * @return int|null
     */
    public function getIdPriv(): ?int
    {
        return (new ValidateParams())->validateInteger($this->idPriv);
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return (new ValidateParams())->validateInteger($this->idUser);
    }

    /**
     * @return string|null
     */
    public function getSuperPriv(): ?string
    {
        return (new ValidateParams())->validateSigla2Letras($this->superPriv);
    }

    /**
     * @return string|null
     */
    public function getServico(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->servico);
    }

    /**
     * @return string|null
     */
    public function getSubServico(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->subServico);
    }

    /**
     * @return string|null
     */
    public function getDataConcessao(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->dataConcessao);
    }

    /**
     * @return string|null
     */
    public function getRespConcessao(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->respConcessao);
    }

    /**
     * @return string|null
     */
    public function getLotacao(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->lotacao);
    }

    /**
     * @return string|null
     */
    public function getFuncao(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->funcao);
    }

    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }
}
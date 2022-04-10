<?php


namespace Api\Model;


use Api\Helper\ValidateParams;

class Funcionario
{
    public function __construct(
        private ?int $idFunc,
//        private ?string $matricula,
//        private ?string $rg,
//        private ?string $dataInclusao,
//        private ?string $nome,
//        private ?string $nomeGuerra,
//        private ?string $postoGraduacao,
//        private ?string $nomePai,
//        private ?string $nomeMae,
//        private ?string $ts,
//        private ?string $naturalidade,
//        private ?string $dataNascimento,
//        private ?string $cpf,
//        private ?string $pasep,
//        private ?string $regNascimento,
//        private ?string $localExpedicao,
//        private ?string $dataExpedicao,
//        private ?string $tipo,
//        private ?string $situacao,
        private ?string $foto,
        private ?string $dataSituacao,
        private ?string $lotacao,

    )
    {
    }



    /**
     * @return int|null
     */
    public function getCodFuncionario(): ?int
    {
        return (new ValidateParams())->validateInteger($this->);
    }

    /**
     * @return string|null
     */
    public function getMatricula(): ?string
    {
        return (new ValidateParams())->validateMatricula($this->matricula);
    }

    /**
     * @return string|null
     */
    public function getNovaMatricula(): ?string
    {
        return (new ValidateParams())->validateMatricula($this->novaMatricula);
    }

    /**
     * @return string|null
     */
    public function getVinculo(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->vinculo);
    }

    /**
     * @return string|null
     */
    public function getRg(): ?string
    {
        return (new ValidateParams())->validateRg($this->rg);
    }

    /**
     * @return string|null
     */
    public function getOrgaoEmissor(): ?string
    {
        return (new ValidateParams())->validateOrgaoEmissor($this->orgaoEmissor);
    }

    /**
     * @return string|null
     */
    public function getDataInclusao(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->dataInclusao);
    }

    /**
     * @return string|null
     */
    public function getNome(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->nome);
    }

    /**
     * @return string|null
     */
    public function getNomeGuerra(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->nomeGuerra);
    }

    /**
     * @return string|null
     */
    public function getPostoGraduacao(): ?string
    {
        return $this->postoGraduacao;
    }

    /**
     * @return int|null
     */
    public function getCodPostoGraduacaoQuadros(): ?int
    {
        return (new ValidateParams())->validateInteger($this->codPostoGraduacaoQuadros);
    }

    /**
     * @return string|null
     */
    public function getNomePai(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->nomePai);
    }

    /**
     * @return string|null
     */
    public function getNomeMae(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->nomeMae);
    }

    /**
     * @return string|null
     */
    public function getTs(): ?string
    {
        return (new ValidateParams())->validateSigla2Letras($this->ts);
    }

    /**
     * @return string|null
     */
    public function getFRH(): ?string
    {
        return (new ValidateParams())->validateFrh($this->fRH);
    }

    /**
     * @return string|null
     */
    public function getNaturalidade(): ?string
    {
        return (new ValidateParams())->validateStringComAcento($this->naturalidade);
    }

    /**
     * @return string|null
     */
    public function getUfNaturalidade(): ?string
    {
        return (new ValidateParams())->validateSigla2Letras($this->ufNaturalidade);
    }

    /**
     * @return string|null
     */
    public function getFd1(): ?string
    {
        return $this->fd1;
    }

    /**
     * @return string|null
     */
    public function getFd2(): ?string
    {
        return $this->fd2;
    }

    /**
     * @return string|null
     */
    public function getDataNascimento(): ?string
    {
        return (new ValidateParams())->validateBirthday($this->dataNascimento);
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
    public function getLocalExpedicao(): ?string
    {
        return $this->localExpedicao;
    }

    /**
     * @return string|null
     */
    public function getDataExpedicao(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->dataExpedicao);
    }

    /**
     * @return string|null
     */
    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    /**
     * @return string|null
     */
    public function getSituacao(): ?string
    {
        return $this->situacao;
    }

    /**
     * @return string|null
     */
    public function getFoto(): ?string
    {
        return $this->foto;
    }

    /**
     * @return string|null
     */
    public function getDataSituacao(): ?string
    {
        return (new ValidateParams())->dateTimeFormatBrToDb($this->dataSituacao);
    }

    /**
     * @return array
     */
    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }
    /**
     * @return string|null
     */
    public function getLotacao(): ?string
    {
        return $this->lotacao;
    }

    /**
     * @return string|null
     */
    public function getPrivileios(): ?string
    {
        return $this->privileios;
    }

    /**
     * @return string|null
     */
    public function getFuncao(): ?string
    {
        return $this->funcao;
    }

}
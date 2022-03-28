<?php


namespace Api\Model;


use Api\Helper\ValidateParams;

class Funcionario
{
    public function __construct(
        private ?int $codFuncionario,
        private ?string $matricula,
        private ?string $novaMatricula,
        private ?string $vinculo,
        private ?string $rg,
        private ?string $orgaoEmissor,
        private ?string $dataInclusao,
        private ?string $nome,
        private ?string $nomeGuerra,
        private ?string $postoGraduacao,
        private ?int $codPostoGraduacaoQuadros,
        private ?string $nomePai,
        private ?string $nomeMae,
        private ?string $ts,
        private ?string $fRH,
        private ?string $naturalidade,
        private ?string $ufNaturalidade,
        private ?string $fd1,
        private ?string $fd2,
        private ?string $dataNascimento,
        private ?string $cpf,
        private ?string $pasep,
        private ?string $regNascimento,
        private ?string $localExpedicao,
        private ?string $dataExpedicao,
        private ?string $tipo,
        private ?string $situacao,
        private ?string $foto,
        private ?int $dataSituacao,
    )
    {
    }

    /**
     * @return int|null
     */
    public function getCodFuncionario(): ?int
    {
        return (new ValidateParams())->validateInteger($this->codFuncionario);
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
        return (new ValidateParams())->validateName($this->vinculo);
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
        return (new ValidateParams())->validateName($this->nome);
    }

    /**
     * @return string|null
     */
    public function getNomeGuerra(): ?string
    {
        return (new ValidateParams())->validateName($this->nomeGuerra);
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
        return (new ValidateParams())->validateName($this->nomePai);
    }

    /**
     * @return string|null
     */
    public function getNomeMae(): ?string
    {
        return (new ValidateParams())->validateName($this->nomeMae);
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
        return $this->fRH;
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
    public function getUfNaturalidade(): ?string
    {
        return $this->ufNaturalidade;
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
        return $this->dataNascimento;
    }

    /**
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return $this->cpf;
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
        return $this->dataExpedicao;
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
     * @return int|null
     */
    public function getDataSituacao(): ?int
    {
        return $this->dataSituacao;
    }

    /**
     * @return array
     */
    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }

}
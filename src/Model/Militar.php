<?php

namespace Api\Model;

class Militar
{
    public function __construct(
        private ?int $idM,
        private ?string $nome,
        private ?string $nomeGuerra,
        private ?string $postoGraduacao,
        private ?string $matricula,
        private ?string $rgMilitar,
        private ?string $tipoSangue,
        private ?string $lotacao,
        private ?string $funcao,
        private ?string $situacao
    )
    {
    }

    /**
     * @return int|null
     */
    public function getIdM(): ?int
    {
        return $this->idM;
    }

    /**
     * @return string|null
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @return string|null
     */
    public function getNomeGuerra(): ?string
    {
        return $this->nomeGuerra;
    }

    /**
     * @return string|null
     */
    public function getPostoGraduacao(): ?string
    {
        return $this->postoGraduacao;
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
    public function getRgMilitar(): ?string
    {
        return $this->rgMilitar;
    }

    /**
     * @return string|null
     */
    public function getTipoSangue(): ?string
    {
        return $this->tipoSangue;
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
    public function getFuncao(): ?string
    {
        return $this->funcao;
    }

    /**
     * @return string|null
     */
    public function getSituacao(): ?string
    {
        return $this->situacao;
    }

    public function dataSerialize(): array
    {
        return get_object_vars($this);
    }
}

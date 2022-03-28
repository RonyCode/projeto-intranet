<?php

namespace Api\Helper;

use DateTime;
use DateTimeImmutable;
use Exception;

class ValidateParams
{
    use ResponseError;

    private string $date;

    public function __construct()
    {
        date_default_timezone_set('America/Araguaina');

    }


    public function validateCpf($cpf)
    {

        try {
            $regex = "/^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/";
            if (!preg_match($regex, $cpf, $match)) {
                throw new Exception();
            } else {
                return $cpf;
            }
        } catch (Exception) {
            $this->responseCatchError("O campo CPF deve seguir esta máscara 11 números sem ponto . ou traço -  XXXXXXXXXXX");
        }

    }


    public function dateFormatDbToBr($objDate): ?string
    {
        try {
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $objDate);
            if (!$date) {
                throw new Exception();
            } else {
                return $date->format('d/m/Y H:i:s');
            }
        } catch (Exception) {
            $this->responseCatchError(
                "Os dados referente a data dever ser exatamente assim XXXX-XX-XX vindo do banco de dados."
            );
        }
    }

    public function dateTimeFormatDbToBr($objDate): ?string
    {
        try {
            if ($objDate === null) {
                return null;
            }
            $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $objDate);
            if (!$date) {
                throw new Exception();
            } else {
                return $date->format('d/m/Y H:i:s');
            }
        } catch (Exception) {
            $this->responseCatchError(
                "Os dados referente a data dever ser exatamente assim XXXX-XX-XX vindo do banco de dados."
            );
        }
    }

    public function validateEmail(null|string $email): ?string
    {
        try {
            $valitedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            $valitedEmail === false ? throw new Exception() : '';
            return $valitedEmail;
        } catch (Exception) {
            $this->responseCatchError('Email inválido, favor digitar um email válido');
        }
    }

    public function validatePass(string $pass): ?string
    {
        try {
            $regex = "/^\S*(?=\S{8,})(?=\S*[a-zA-Z])(?=\S*[\d])\S*$/";
            if (!preg_match($regex, $pass, $match)) {
                throw new Exception();
            }

            return $pass;
        } catch (Exception) {
            $this->responseCatchError("Erro, senha dever ter pelo menos 1 letra, 1 numero e no mínimo 8 caracteres.");
        }
    }

    public function validateName(string $name): ?string
    {
        try {
            $regex = "/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/";
            if (!preg_match($regex, $name, $match)) {
                throw new Exception();
            }

            return $name;
        } catch (Exception) {
            $this->responseCatchError(
                "Digite apenas letras no campo nome, números ou caracteres especiais não serão aceitos."
            );
        }
    }

    public function validateSigla2Letras(string $sigla): ?string
    {
        try {
            $regex = "/^[A-Z]{0,2}$/";
            if (!preg_match($regex, $sigla, $match)) {
                throw new Exception();
            }

            return $sigla;
        } catch (Exception) {
            $this->responseCatchError(
                "Tipo sanguíneo inválido o campo dever seguir exatamente esta máscara X ou XX!"
            );
        }
    }

    public function validateFrh(string $fRh): string
    {
        try {
            $regex = "/^[A-Z].{0,3}$/";
            if (!preg_match($regex, $fRh, $match)) {
                throw new Exception();
            }

            return $fRh;
        } catch (Exception) {
            $this->responseCatchError(
                "Fator RH sanguíneo deve seguir exatamente esta máscara LETRA MAIÚSCULA com 3 caracteres 
                seguidos de ponto POS. ou NEG.  !"
            );
        }
    }


    public function validateAddress(string $address): ?string
    {
        try {
            $regex = "/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ 0-9]+$/";
            if (!preg_match($regex, $address, $match)) {
                throw new Exception();
            }
            return mb_strtoupper($address);
        } catch (Exception) {
            $this->responseCatchError(
                "Digite apenas letras ou numeros no campo endereço, caracteres especiais não serão aceitos"
            );
        }
    }

    public function validateRg(string $rg): ?string
    {
        try {

            $regex = "/(\d{1,2}\.)(\d{3}\-)(\d{2})/";
            if (!preg_match($regex, $rg, $match)) {
                throw new Exception();
            }
            return mb_strtoupper($rg);
        } catch (Exception) {
            $this->responseCatchError(
                "Rg inválido o campo rg dever seguir exatamente esta máscara 99.999-99!"
            );
        }
    }


    function validateOrgaoEmissor(string $orgaoEmissor): ?string
    {
        try {

            $regex = "/^([A-Z]{0,5})$/";
            if (!preg_match($regex, $orgaoEmissor, $match)) {
                throw new Exception();
            }
            return mb_strtoupper($orgaoEmissor);
        } catch (Exception) {
            $this->responseCatchError(
                "Orgão Emissor inválido, o campo dever seguir exatamente esta máscara 5 letras MAIÚSCULAS: XXXXX!"
            );
        }
    }

    public function validatePhone(string $phone): ?string
    {
        try {
            $regex = "/^\(?[1-9]{2}\)? ?(?:[2-8]|9[1-9])[0-9]{3}\-?[0-9]{4}$/";
            if (!preg_match($regex, $phone, $match)) {
                throw new Exception();
            }
            return $phone;
        } catch (Exception) {
            $this->responseCatchError(
                "Error: numero de Telefone inválido, use exatamente esse formato (99) 99999-9999."
            );
        }
    }

    public function validateAge(string $birthday): ?string
    {
        try {
            $this->date = $birthday;
            $date = $this->dateFormatBrToDb($birthday);

            if ($date === null) {
                throw new Exception();
            } else {
                $dateFormated = new DateTime($date);
                $interval = $dateFormated->diff(new DateTime(date('Y/m/d ')));
                return $interval->format('%Y');
            }
        } catch (Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');
        }
    }

    public function dateFormatBrToDb($objDate): ?string
    {
        try {
            $date = DateTimeImmutable::createFromFormat('d/m/Y', $objDate);
            if (!$date) {
                throw new Exception();
            } else {
                return $date->format('Y-m-d');
            }
        } catch (Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');
        }
    }


    public function dateTimeFormatBrToDb($objDate): ?string
    {
        try {
            $date = DateTimeImmutable::createFromFormat('d/m/Y', $objDate);
            if (!$date) {
                throw new Exception();
            } else {
                return $date->format("Y-m-d H:i:s");
            }
        } catch (Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');
        }
    }

    public function validateBirthday(string $birthday): ?string
    {
        try {
            return $this->dateFormatBrToDb($birthday);
        } catch (Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');
        }
    }

    public function validateInteger(int $numeral): ?int
    {
        try {
            $regex = "/^[1-9]\d*$/";
            if (!preg_match($regex, $numeral, $match)) {
                throw new Exception();
            }
            return $numeral;
        } catch (Exception) {
            $this->responseCatchError(
                "Error: Somente números serão aceito para esse formulário."
            );
        }
    }

    public function validateMatricula(string $numeral): ?string
    {
        try {
            $regex = "/^\d{0,8}$/";

            if (!preg_match($regex, $numeral, $match, PREG_UNMATCHED_AS_NULL)) {
                throw new Exception();
            } else {
                return $numeral;
            }
        } catch (Exception) {
            $this->responseCatchError(
                "Error: matrícula deve conter no máximo 8 números exemplo 99999999 sem o traço e código verificador"
            );
        }
    }
}

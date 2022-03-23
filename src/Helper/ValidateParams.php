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
    }


    public function validaCPF($cpf)
    {
        try {
            // Extrai somente os números
            $cpf = preg_replace('/[^0-9]/is', '', $cpf);

            // Verifica se foi informado todos os digitos corretamente
            if (strlen($cpf) != 11) {
                return false;
            }

            // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }

            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return $cpf;
        } catch (Exception) {
            $this->responseCatchError(
                "O CPF digitado não existe por favor tente novamente."
            );
        }


        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;

    }


    public function dateFormatDbToBr($objDate): string
    {
        try {
            $date = DateTimeImmutable::createFromFormat('Y-m-d', $objDate);
            if (!$date) {
                throw new Exception();
            } else {
                return $date->format('d/m/Y');
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

    public function validatePass(string $pass): string
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

    public function validateName(string $name): string
    {
        try {
            $regex = "/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/";
            if (!preg_match($regex, $name, $match)) {
                throw new Exception();
            }

            $name = explode(' ', $name);
            $nametrated = [];
            foreach ($name as $nameSepared) {
                $nametrated[] = ucfirst(mb_strtolower($nameSepared));
            }
            return (implode(' ', $nametrated));
        } catch (Exception) {
            $this->responseCatchError(
                "Digite apenas letras no campo nome, números ou caracteres especiais não serão aceitos."
            );
        }
    }

    public function validateAddress(string $address): string
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

    public function validatePhone(string $phone): string
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

    public function validateAge(string $birthday): string
    {
        try {
            $this->date = $birthday;
            $date = $this->dateFormatBrToDb($birthday);

            if ($date === null) {
                throw new Exception();
            } else {
                $dateFormated = new DateTime($date);
                $interval = $dateFormated->diff(new DateTime(date('Y/m/d')));
                return $interval->format('%Y');
            }
        } catch (Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');
        }
    }

    public function dateFormatBrToDb($objDate): string
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

    public function validateBirthday(string $birthday): string
    {
        try {
            return $this->dateFormatBrToDb($birthday);
        } catch (Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');
        }
    }

    public function validateInteger(int $numeral): int
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
}

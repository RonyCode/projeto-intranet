<?php

namespace Api\Helper;

use Exception;

class CheckTokenAuth
{
    use ResponseError;

    public function validToken(): array
    {
        try {
            $httpHeader = apache_request_headers();
            isset($httpHeader['Authorization']) &&
            str_contains($httpHeader['Authorization'], 'Bearer ') ? $token = str_replace(
                'Bearer ',
                '',
                $httpHeader['Authorization']
            ) : $token = false;
            $response = (new JwtHandler())->jwtDecode($token);
//            $response[0] === false ? throw new Exception() : '';
            return $response;
        } catch (Exception) {
            $this->responseCatchError('Token inválido ou inexistente');
        }
    }
}

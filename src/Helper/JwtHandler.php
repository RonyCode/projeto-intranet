<?php

namespace Api\Helper;

use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use InvalidArgumentException;
use UnexpectedValueException;

class JwtHandler
{
    protected string $jwt_secrect;
    protected array $token;
    protected int $issuedAt;
    protected int $expire;
    protected string $jwt;

    public function __construct()
    {
        // set your default time-zone
        date_default_timezone_set('America/Araguaina');
        $this->issuedAt = time();
        // Token Validity (3600 second = 1hr)
        $this->expire = $this->issuedAt + 604800; //7 days 3600 seconds = 1 hour * 24 hours * 7 days
        // Set your secret or signature
        $this->jwt_secrect = JWTKEY;
    }

    // ENCODING THE TOKEN
    public function jwtEncode($iss, array $data): string
    {
        $this->token = array(
            //Adding the identifier to the token (who issue the token)
            "iss" => $iss,
            "aud" => $iss,
            // Adding the current timestamp to the token, for identifying that when the token was issued.
            "iat" => $this->issuedAt,
            // Token expiration
            "exp" => $this->expire,
            // Payload
            "data" => $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secrect);
        return $this->jwt;
    }

    //DECODING THE TOKEN
    public function jwtDecode($jwt_token): string|array
    {
        try {
            $decode = JWT::decode($jwt_token, $this->jwt_secrect, array('HS256'));
            return $decode->data;
        } catch (
        ExpiredException | SignatureInvalidException |
        BeforeValidException | DomainException | InvalidArgumentException | UnexpectedValueException $e
        ) {
            return [false, $e->getMessage()];
        }
    }
}

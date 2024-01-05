<?php


namespace Jggurgel\Pext\Lib;


class JWT
{
    public function encode($payload = [])
    {
        $key = 'your-secret-key-here';

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $header = json_encode($header);
        $header = base64_encode($header);


        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "{$header}.{$payload}", $key, true);
        $signature = base64_encode($signature);

        $token = "{$header}.{$payload}.{$signature}";

        return $token;
    }

    public function decode(string $token)
    {
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload;
    }
}

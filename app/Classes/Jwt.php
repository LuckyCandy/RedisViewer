<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-05-21
 * Time: 16:37
 */

namespace App\Classes;

use Firebase\JWT\JWT as FJwt;

class Jwt
{
    /**
     * @param array $payload
     * @param int $expire
     * @return string
     */
    public static function make(array $payload, $expire = 7200)
    {
        $now = time();
        return FJwt::encode([
            'exp' => $now + $expire,
            'iss' => env('APP_NAME'),
            'iat' => $now,
            'data' => $payload
        ], env('APP_KEY'));
    }

    /**
     * @param $token
     * @return array | null
     */
    public static function getPayloadFrom($token)
    {
        try {
            $payload = FJwt::decode($token, env('APP_KEY'), ['HS256']);
            return $payload ? (array) $payload->data : null;
        } catch (\UnexpectedValueException $e) {
            return null;
        }
    }
}
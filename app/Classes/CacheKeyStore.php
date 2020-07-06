<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-06-11
 * Time: 15:27
 */

namespace App\Classes;


class CacheKeyStore
{
    public static function getRedisClientCacheKey($userId)
    {
        return env('APP_NAME', 'Laravel') . '#Redis-Work-Client-' . $userId;
    }
}
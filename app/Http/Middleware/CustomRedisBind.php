<?php

namespace App\Http\Middleware;

use App\Classes\CacheKeyStore;
use App\Exceptions\CustomException;
use Closure;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class CustomRedisBind
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws
     */
    public function handle($request, Closure $next)
    {
        $client = Cache::get(CacheKeyStore::getRedisClientCacheKey(auth()->user()->id));
        if (!$client) {
            throw new CustomException('绑定服务失败，请重新选择');
        }
        app()->singleton('custom.redis', function ($app) use ($client, $request){
            $config = $app->make('config')->get('database.redis', []);
            $driver = Arr::pull($config, 'client', 'phpredis');
            $config = [
                'client' => $driver,
                'default' => [
                    'host' => $client->address,
                    'password' => $client->password,
                    'port' => $client->port,
                    'database' => $client->db,
                ]
            ];
            $connect = (new RedisManager($app, $driver, $config))->connection();
            $connect->setOption(\Redis::OPT_SCAN, \Redis::SCAN_RETRY);
            if ($selectDb = $request->get('db')) {
                $connect->select($selectDb);
            } else {
                $connect->select($client->db);
            }

            return $connect;
        });
        $response = $next($request);
        app('custom.redis')->disconnect();

        return $response;
    }
}

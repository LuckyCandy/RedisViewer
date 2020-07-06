<?php

namespace App\Http\Controllers\Api\Redis;

use App\Classes\CacheKeyStore;
use App\Http\Requests\RedisClientRequest;
use App\Models\RedisClient;
use Illuminate\Http\Request;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClientController extends \App\Http\Controllers\Controller
{
    public function list()
    {
        return response()->jsr(200,
            RedisClient::where('user_id', auth()->user()->id)
                ->orderBy('updated_at', 'desc')
                ->get()
        );
    }

    public function create(RedisClientRequest $request)
    {
        $params = $request->only(['name', 'password', 'port', 'address', 'db_no']);

        if ($this->testConnection(
            Arr::get($params, 'address'), Arr::get($params, 'password'), Arr::get($params, 'port')
        )) {
            $client = new RedisClient();
            $client->name = Arr::get($params, 'name', 'Client#' . Str::random(6));
            $client->password = Arr::get($params, 'password');
            $client->port = Arr::get($params, 'port');
            $client->address = Arr::get($params, 'address');
            $client->db = Arr::get($params, 'db', 0);
            $client->user_id = auth()->user()->id;
            return $client->save() ? response()->jsr() : response()->jsr(500, [], '增加服务失败，请重试~');
        } else {
            return response()->jsr(500, [], '该配置无法建立连接，请检查填写是否正确');
        }
    }

    public function update($id, RedisClientRequest $request)
    {
        $client = RedisClient::find($id);
        if (!$client) {
            return response()->jsr(500, [], '服务已被删除，更新失败~');
        }

        $params = $request->only(['name', 'password', 'port', 'address', 'db_no']);
        if ($this->testConnection(
            Arr::get($params, 'address'), Arr::get($params, 'password') ?? $client->password, Arr::get($params, 'port')
        )) {

            $client->name = Arr::get($params, 'name', 'Client#' . Str::random(6));
            if ($newPassword = Arr::get($params, 'password')) {
                $client->password = $newPassword;
            }
            $client->port = Arr::get($params, 'port');
            $client->address = Arr::get($params, 'address');
            $client->db = Arr::get($params, 'db', 0);
            $client->user_id = auth()->user()->id;
            return $client->save() ? response()->jsr() : response()->jsr(500, [], '更新服务失败，请重试~');
        } else {
            return response()->jsr(500, [], '该配置无法建立连接，请检查填写是否正确');
        }
    }

    public function delete($id)
    {
        return RedisClient::destroy($id) ?
            response()->jsr() :
            response()->jsr(500, [], '删除失败');
    }

    public function setOnWork($id)
    {
        $user = auth()->user();
        $client = RedisClient::where('user_id', $user->id)->where('id', $id)->first();
        if (!$client) {
            return response()->jsr(500, [], '选择的服务已被删除，请重新选择');
        }
        if (!$client->is_working) {
            $client->is_working = true;
            if (!Cache::put(CacheKeyStore::getRedisClientCacheKey($user->id), $client) || !$client->save()) {
                return response()->jsr(500, [], '未知错误，请重试');
            }
        }
        /* 缓存当前配置 */
        $manager = $this->_getRedisManager($client->address, $client->password, $client->port);
        try {
            $redis = $manager->connection();
            return response()->jsr(200, $redis->info());
        } catch (\Exception $e) {
            return response()->jsr(500, [], '连接失败，请检查连接配置是否可用');
        }
    }


    protected function testConnection($host, $password, $port)
    {
        $redisManager = $this->_getRedisManager($host, $password, $port);
        try {
            $redisManager->connection();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function _getRedisManager($host, $password, $port)
    {
        $config = app()->make('config')->get('database.redis', []);
        $driver = Arr::pull($config, 'client', 'phpredis');
        return new RedisManager(app(), $driver, [
            'client' => $driver,
            'default' => [
                'host' => $host, 'password' => $password, 'port' => $port, 'database' => 0,
            ]
        ]);
    }
}

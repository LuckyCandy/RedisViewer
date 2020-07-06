<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-06-17
 * Time: 11:44
 */

namespace App\Classes\Redis;


use Illuminate\Support\Arr;

class StringValueGetter implements ValueGetter
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function get($key, $ops = [])
    {
        $info = $this->redis->pipeline(function($pipe) use ($key) {
            $pipe->get($key);
            $pipe->ttl($key);
        });

        return [
            'key' => $key, 'value' => $info[0], 'ttl' => $info[1]
        ];
    }

    /**
     * 操作
     * @param $key
     * @param $type string 操作类型
     * @param $ops array 操作类型
     * @return mixed
     */
    public function op($key, $type, $ops = [])
    {
        if ($type == 'update') {
            if (Arr::get($ops, 'ttl', -1) == -1) {
                $this->redis->set($key, Arr::get($ops, 'value', ''));
            } else {
                $this->redis->setex($key, Arr::get($ops, 'ttl', 0), Arr::get($ops, 'value', ''));
            }
        }
    }
}
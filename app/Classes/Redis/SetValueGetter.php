<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-06-29
 * Time: 15:29
 */

namespace App\Classes\Redis;


use App\Exceptions\CustomException;
use Illuminate\Support\Arr;

class SetValueGetter implements ValueGetter
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function get($key, $ops = [])
    {
        return $this->redis->smembers($key);
    }

    /**
     * 操作
     * @param $key
     * @param $action string 操作类型
     * @param $ops array 操作参数
     * @return mixed
     * @throws
     */
    public function op($key, $action, $ops = [])
    {
        $value = Arr::get($ops, 'value');
        switch ($action) {
            case 'insert':
                if (!$value) {
                    throw new CustomException('不能添加空值');
                }
                $this->redis->sadd($key, $value);
                return [];
            case 'delete':
                $this->redis->srem($key, $value);
                return [];
        }
    }
}
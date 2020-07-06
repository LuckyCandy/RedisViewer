<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-06-17
 * Time: 11:44
 */

namespace App\Classes\Redis;


use App\Exceptions\CustomException;
use Illuminate\Support\Arr;

class ListValueGetter implements ValueGetter
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function get($key, $ops = [])
    {
        $index = Arr::get($ops, 'index', 0);
        $info = $this->redis->pipeline(function($pipe) use ($key, $index) {
            $pipe->llen($key);
            $pipe->lrange($key, $index, $index);
        });

        return [
            'key' => $key, 'value' => Arr::first($info[1]), 'index' => $index, 'size' => $info[0]
        ];
    }

    /**
     * 操作
     * @param $key
     * @param $action string 操作类型
     * @param $ops array 操作类型
     * @return mixed
     * @throws
     */
    public function op($key, $action, $ops = [])
    {
        $index = Arr::get($ops, 'index', 0);
        switch ($action) {
            case 'get':
                $result = $this->redis->lrange($key, $index, $index);
                return ['value' => Arr::first($result)];
            case 'delete':
                $value = Arr::get($ops, 'value', '');
                $info = $this->redis->pipeline(function($pipe) use ($key, $index, $value) {
                    $pipe->lrem($key, $value, 1);
                    $pipe->llen($key);
                    $pipe->lrange($key, $index, $index);
                    $pipe->lrange($key, $index - 1, $index - 1);
                });
                return [
                    'index' => $index <= $info[1] - 1 ? $index : $index - 1,
                    'value' =>  Arr::first($index <= $info[1] - 1 ? $info[2] : $info[3]),
                    'size' => $info[1],
                    'key' => $key
                ];
            case 'insert':
                $value = Arr::get($ops, 'value', '');
                $index = Arr::get($ops, 'index', '');
                $currentIndexValue = Arr::first($this->redis->lrange($key, $index, $index));
                $size = $this->redis->linsert($key, \REDIS::BEFORE, "{$currentIndexValue}", $value);
                if ($size == -1) {
                    throw new CustomException('操作失败，请重试');
                }
                return [
                    'index' => $index,
                    'value' =>  $value,
                    'size' => $size,
                    'key' => $key
                ];
            default:
                $value = Arr::get($ops, 'value', '');
                $index = Arr::get($ops, 'index', '');
                $this->redis->lset($key, $index, $value);
                return [];
        }
    }
}
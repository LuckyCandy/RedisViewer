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

class HashValueGetter implements ValueGetter
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function get($key, $ops = [])
    {
        $result = $this->redis->hgetall($key);

        $data = [];
        foreach ($result as $key => $value) {
            $data[] = [
                'value' => $value,
                'key' => $key
            ];
        }
        return $data;
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
        $mkey = Arr::get($ops, 'mkey', '');
        $value = Arr::get($ops, 'value', 0);
        switch ($action) {
            case 'update':
                $this->redis->hSet($key, $mkey, $value);
                return [];
            case 'delete':
                $this->redis->hDel($key, $mkey);
                return [];
            case 'insert':
                if (!$this->redis->hSetNx($key, $mkey, $value)) {
                    throw new CustomException('添加失败，请检查key是否重复');
                }
                return ['key' => $mkey, 'value' => $value];
        }
    }
}
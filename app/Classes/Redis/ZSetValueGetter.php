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

class ZSetValueGetter implements ValueGetter
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function get($key, $ops = [])
    {
        $minScore = Arr::get($ops, 'min_score', 0);
        $maxScore = Arr::get($ops, 'max_score', 10000);
        $pageSize = Arr::get($ops, 'page_size', 20);
        $pageNo = Arr::get($ops, 'page_no', 1);
        $offset = $pageSize * ($pageNo - 1);
        $total = $this->redis->zcard($key);
        $result = $this->redis->zRangeByScore($key, $minScore, $maxScore, array('withscores' => true, 'limit' => array('offset' => $offset, 'count' => $pageSize)));

        $data = [];
        foreach ($result as $key => $value) {
            $data[] = [
                'score' => $value,
                'content' => $key
            ];
        }

        return ['total' => $total, 'result' => $data];
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
        $value = Arr::get($ops, 'value', '');
        $score = Arr::get($ops, 'score', 0);
        switch ($action) {
            case 'add':
                if (!$score) {
                    throw new CustomException('请输入要加的分数');
                }
                $this->redis->zIncrBy($key, $score, $value);
                return [];
            case 'delete':
                $this->redis->zrem($key, $value);
                return [];
            case 'insert':
                $this->redis->zadd($key, $score, $value);
                return [];
        }
    }
}
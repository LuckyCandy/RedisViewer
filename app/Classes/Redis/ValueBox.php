<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-06-17
 * Time: 11:34
 */

namespace App\Classes\Redis;


class ValueBox
{
    /**
     * @var ValueGetter
     */
    private $_getter;

    private $_redis;

    public function __construct($redis, $type = 1)
    {
        $this->_redis = $redis;
        switch ($type) {
            case 1:
                $this->_getter = new StringValueGetter($redis);
                break;
            case 2:
                $this->_getter = new SetValueGetter($redis);
                break;
            case 3:
                $this->_getter = new ListValueGetter($redis);
                break;
            case 4:
                $this->_getter = new ZSetValueGetter($redis);
                break;
            case 5:
                $this->_getter = new HashValueGetter($redis);
                break;
        }
    }

    public function get($key, $ops = []) {
        return $this->_getter->get($key, $ops);
    }

    public function op($key, $type, $ops = []) {
        return $this->_getter->op($key, $type, $ops);
    }

}
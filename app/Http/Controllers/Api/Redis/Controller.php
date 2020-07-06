<?php

namespace App\Http\Controllers\Api\Redis;

use App\Classes\Redis\ValueBox;
use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Controller extends \App\Http\Controllers\Controller
{
    public function keys(Request $request)
    {
        $redis = app('custom.redis');
        $search = ['count' => 500];
        if ($keyPattern = $request->get('key_pattern')) {
            $search['match'] = $keyPattern;
        }
        $scanKeys = $redis->scan(null, $search);
        if ($scanKeys === false || count($scanKeys) == 0) {
            $keys = [];
        } else {
            $keys = Arr::get($scanKeys, 1);
        }
        $result = [];
        foreach ($redis->pipeline(function($pipe) use ($keys, $result){
            foreach ($keys as $key) {
                $pipe->type($key);
            }
        }) as $index => $type) {
            $result[] = [
                'key' => $keys[$index],
                'type' => $type
            ];
        }


        return response()->jsr(200, $result);
    }

    public function get(Request $request)
    {
        Validator::make($request->all(), [
            'key' => 'bail|required',
            'type' => 'bail|required|numeric|max:5|min:1'
        ])->validate();

        $redis = app('custom.redis');

        return response()->jsr(
            200, (new ValueBox($redis, $request->get('type')))->get($request->get('key'), $request->get('ops', []))
        );
    }

    public function remove(Request $request)
    {
        Validator::make($request->all(), [
            'key' => 'bail|required',
        ])->validate();

        $redis = app('custom.redis');

        return $redis->del($request->get('key')) ? response()->jsr() : response()->jsr(500, [], '删除失败，请重试~');
    }

    public function op(Request $request)
    {
        Validator::make($request->all(), [
            'key' => 'bail|required',
            'type' => 'bail|required',
            'action' => 'bail|required',
        ])->validate();

        $redis = app('custom.redis');

        try {
            return response()->jsr(
                200, (new ValueBox($redis, $request->get('type')))->op($request->get('key'), $request->get('action'), $request->get('ops'))
            );
        } catch (CustomException $e) {
            return response()->jsr(500, [], $e->getMessage());
        }

    }
}

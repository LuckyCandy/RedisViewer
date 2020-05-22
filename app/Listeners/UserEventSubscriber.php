<?php

namespace App\Listeners;

use App\Models\SysLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserEventSubscriber
{
    public function handleUserLogin($event)
    {
        $user = $event->user;

        (new SysLog(
            [
                'operator_id' => $user->id,
                'desc' => "账号{$user->name}登录系统"
            ]
        ))->save();
    }

    /**
     * 为事件订阅者注册监听器
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@handleUserLogin'
        );
    }
}

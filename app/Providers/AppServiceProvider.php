<?php

namespace App\Providers;

use App\Http\Guards\JwtGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('jwt', function ($app, $name, $config){
            $guard = new JwtGuard(
                $app['auth']->createUserProvider($config['provider'] ?? null),
                $app['request'],
                $config['input_key'] ?? 'token',
                $config['storage_key'] ?? 'token',
                $config['hash'] ?? false,
                $config['expire_in'] ?? 7200
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}

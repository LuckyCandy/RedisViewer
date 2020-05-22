<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('jsr', function ($code=200, $data=[], $msg='') {
            $content =  array(
                'code'    =>  $code,
                'data'    =>  $data,
                'msg'     =>  $msg
            );
            return response()->json($content);
        });
    }
}

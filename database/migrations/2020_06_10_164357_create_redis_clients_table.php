<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedisClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redis_clients', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id', false, true)->comment('用户id');
            $table->string('name')->comment('服务名');
            $table->string('address')->comment('服务地址');
            $table->string('password')->comment('连接密码');
            $table->integer('port', false, true)->default('6379')->comment('端口号');
            $table->tinyInteger('db', false, true)->comment('默认选择连接的db');
            $table->boolean('is_working')->default(false)->comment('是否正在使用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redis_clients');
    }
}

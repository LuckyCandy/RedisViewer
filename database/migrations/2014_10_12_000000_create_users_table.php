<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('用户名');
            $table->string('avatar')->comment('用户头像');
            $table->tinyInteger('is_admin')->comment('是否超管:1-是;-2-否');
            $table->tinyInteger('is_block')->comment('是否禁用:1-是;-2-否');
            $table->string('email')->unique()->comment('账户');
            $table->string('password')->comment('密码');
            $table->string('reset_password_token')->comment('重置密码验证凭证');
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
        Schema::dropIfExists('users');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysLog extends Model
{
    protected $table = 'sys_log';

    protected $fillable = ['operator_id', 'desc'];
}

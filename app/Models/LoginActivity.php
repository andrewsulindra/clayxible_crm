<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $table = 'login_activities';

    protected $fillable = ['user_id', 'login_time', 'user_agent', 'ip_address'];
}

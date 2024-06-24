<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuid;
use Spatie\Permission\Traits\HasRoles;
use Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasRoles, LogsActivity, HasApiTokens;

    // protected $primaryKey = 'id';
    // protected $keyType = 'string';
    // public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'last_login', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Deactivate resource
     * @return boolean
     */
    public function deactivate()
    {
        $this->is_active = 0;
        return $this->save();
    }

    /**
     * Activate resource
     * @return boolean
     */
    public function activate()
    {
        $this->is_active = 1;
        return $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::check() ? Auth::user()->email : 'System';
            $model->updated_by = Auth::check() ? Auth::user()->email : 'System';
        });

        // static::creating(function ($model) {
        //     try {
        //         $data = random_bytes(16);
        //         $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        //         $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        //         $model->id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        //     } catch (Throwable $e) {
        //         abort(500, $e->getMessage());
        //     }
        // });

    }

}

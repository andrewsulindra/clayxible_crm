<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::check() ? Auth::user()->email : 'System';
            $model->updated_by = Auth::check() ? Auth::user()->email : 'System';
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::check() ? Auth::user()->email : 'System';
        });
    }
}

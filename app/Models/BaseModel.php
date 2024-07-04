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
            $model->created_by = Auth::check() ? Auth::user()->id : '1';
            $model->updated_by = Auth::check() ? Auth::user()->id : '1';
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::check() ? Auth::user()->id : '1';
        });
    }
}

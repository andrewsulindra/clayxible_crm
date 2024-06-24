<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use DB;

class Owner extends BaseModel
{
    use LogsActivity, HasApiTokens;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $table = 'owner';

    protected $fillable = [
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'email',
        'phone',
        'mobile_phone',
        'notes',
        'is_active',
        'owner_category_id',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'city_name',
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

    public function getCityNameAttribute()
    {
        $cities = DB::table('cities')->where('id', $this->city)->first();
        if ($cities == NULL) {
            return null;
        } else {
            return $cities->name;
        }
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use DB;
use Auth;

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
        'group_id',
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

    public static function getOwnerList()
    {
        $query = self::where('is_active', '1')->orderBy('name', 'asc');

        if (Auth::user()->hasAnyRole('Sales')) {
            $query = self::where('created_by', Auth::user()->id)
                        ->where('group_id', Auth::user()->group_id)
                         ->where('is_active', '1')
                         ->orderBy('name', 'asc');
        } else if (Auth::user()->hasAnyRole('Manager')){
            $query = self::where('group_id', Auth::user()->group_id)
                        ->where('is_active', '1')
                        ->orderBy('name', 'asc');
        }

        return $query->get();
    }

    public static function checkOwnerBelongsToUser($user_id, $group_id) {
        if (Auth::user()->hasAnyRole('Sales')) {
            if ($user_id != Auth::user()->id || $group_id != Auth::user()->group_id) {
                abort(404);
            }
        } else if (Auth::user()->hasAnyRole('Manager')){
            if ($group_id != Auth::user()->group_id) {
                abort(404);
            }
        }
    }

}

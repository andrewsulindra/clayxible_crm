<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use Auth;

class Project extends BaseModel
{
    use LogsActivity, HasApiTokens;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $table = 'project';

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
        'owner_id',
        'sales_id',
        'specifications',
        'notes',
        'project_status',
        'group_id',
        'is_active',
        'project_category_id',
        'created_by',
        'updated_by'
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

    public function change_status($status)
    {
        $this->project_status = $status;
        return $this->save();
    }

    public function Sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id');
    }

    public static function checkProjectBelongsToUser($user_id, $group_id) {
        if (Auth::user()->hasAnyRole('Sales')) {
            if ($user_id != Auth::user()->id || $group_id != Auth::user()->group_id) {
                abort(404);
            }
        } else if (Auth::user()->hasAnyRole('Manager')) {
            if ($group_id != Auth::user()->group_id) {
                abort(404);
            }
        }
    }
}

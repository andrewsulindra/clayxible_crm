<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;

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
        'notes',
        'project_status',
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
}

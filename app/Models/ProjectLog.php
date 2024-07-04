<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use App\Models\User;

class ProjectLog extends BaseModel
{
    use LogsActivity, HasApiTokens;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $table = 'project_log';

    protected $fillable = [
        'project_id',
        'type',
        'old',
        'new',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'project_status_name_old',
        'project_status_name_new',
        'created_by_name',
        'created_by_image',
        'project_sales_name_old',
        'project_sales_name_new',
    ];

    public static function createLog($projectId, $type, $oldValue, $newValue)
    {
        return self::create([
            'project_id' => $projectId,
            'type' => $type,
            'old' => $oldValue,
            'new' => $newValue
        ]);
    }

    public function getProjectStatusNameOldAttribute()
    {
        if ($this->attributes['type'] == config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS')) {
            return projectStatusName($this->attributes['old']);
        }

        return null;
    }

    public function getProjectStatusNameNewAttribute()
    {
        if ($this->attributes['type'] == config('constants.PROJECT_LOG_TYPE_CHANGE_STATUS')) {
            return projectStatusName($this->attributes['new']);
        }

        return null;
    }

    public function getCreatedByNameAttribute()
    {
        $user = User::where('id', $this->attributes['created_by'])->first();
        return $user->name;
    }

    public function getCreatedByImageAttribute()
    {
        $user = User::where('id', $this->attributes['created_by'])->first();
        return $user->image;
    }

    public function getProjectSalesNameOldAttribute()
    {
        if ($this->attributes['type'] == config('constants.PROJECT_LOG_TYPE_CHANGE_SALES')) {
            $user = User::where('id', $this->attributes['old'])->first();
            return $user->name;
        }
    }

    public function getProjectSalesNameNewAttribute()
    {
        if ($this->attributes['type'] == config('constants.PROJECT_LOG_TYPE_CHANGE_SALES')) {
            $user = User::where('id', $this->attributes['new'])->first();
            return $user->name;
        }
    }
}

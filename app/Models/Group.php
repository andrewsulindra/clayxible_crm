<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use App\Models\User;

class Group extends BaseModel
{
    use LogsActivity, HasApiTokens;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $table = 'group';

    protected $fillable = [
        'name'
    ];

    public function Project() {
        return $this->hasMany(Project::class, 'group_id', 'id')->where('is_active', 1);
    }
}

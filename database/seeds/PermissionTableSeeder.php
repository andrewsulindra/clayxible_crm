<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = Role::where('name', 'Super Admin')->firstOrFail();
        $permissions = config('master.permissions');
        foreach ($permissions as $module_name => $module_permissions) {
        	foreach ($module_permissions as $permission) {
        		$data = Permission::updateOrCreate([
        			'module_name' => $module_name,
        			'name' => $permission
        		]);
                $super_admin->givePermissionTo($permission);
        	}
        }
    }
}

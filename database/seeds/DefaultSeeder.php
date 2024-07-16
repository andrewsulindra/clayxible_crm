<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* Create default roles */
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $role = Role::firstOrCreate(['name' => 'Manager']);
        $role = Role::firstOrCreate(['name' => 'Sales']);


        //Create Default User
        $user = User::firstOrCreate([
        	'name' => 'Administrator',
        	'email' => 'master@noemail.com',
        	'password' => Hash::make('N5tqkUHTczRn'),
            'image' => generateUserImage('Administrator'),
            'group_id' => 1,
        	'is_active' => 1
        ]);

        $user->assignRole('Super Admin');

        $user = User::firstOrCreate([
        	'name' => 'Clayxible',
        	'email' => 'clayxible@gmail.com',
            'password' => Hash::make('password'),
            'image' => generateUserImage('Clayxible'),
            'group_id' => 1,
        	'is_active' => 1
        ]);

        $user->assignRole('Super Admin');

        $user = User::firstOrCreate([
        	'name' => 'Rudy',
        	'email' => 'rudy.clayxible@gmail.com',
            'password' => Hash::make('password'),
            'image' => generateUserImage('Rudy'),
            'group_id' => 1,
        	'is_active' => 1
        ]);

        $user->assignRole('Super Admin');




        // fake
        // $user = User::firstOrCreate([
        // 	'name' => 'Manager1',
        // 	'email' => 'manager1@noemail.com',
        // 	'password' => Hash::make('manager'),
        //     'image' => generateUserImage('DManager1'),
        //     'group_id' => 1,
        // 	'is_active' => 1
        // ]);

        // $user->assignRole('Manager');

        // $user = User::firstOrCreate([
        // 	'name' => 'Sales1',
        // 	'email' => 'sales1@noemail.com',
        // 	'password' => Hash::make('sales'),
        //     'image' => generateUserImage('BSales1'),
        //     'group_id' => 1,
        // 	'is_active' => 1
        // ]);

        // $user->assignRole('Sales');

        // $user = User::firstOrCreate([
        // 	'name' => 'Sales2',
        // 	'email' => 'sales2@noemail.com',
        // 	'password' => Hash::make('sales'),
        //     'image' => generateUserImage('CSales2'),
        //     'group_id' => 1,
        // 	'is_active' => 1
        // ]);

        // $user->assignRole('Sales');
    }
}

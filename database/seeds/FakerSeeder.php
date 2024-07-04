<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class FakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(2, 3) as $i) {
            $user_id = $i;
            foreach (range(1, 10) as $index) {
                // Insert into the owner table
                $ownerId = DB::table('owner')->insertGetId([
                    'name' => $faker->name,
                    'address1' => $faker->streetAddress,
                    'address2' => $faker->secondaryAddress,
                    'city' => $faker->numberBetween(1, 800),
                    'state' => NULL,
                    'country' => NULL,
                    'email' => $faker->unique()->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'mobile_phone' => $faker->phoneNumber,
                    'notes' => $faker->sentence,
                    'is_active' => '1',
                    'owner_category_id' => $faker->numberBetween(1, 2),
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);

                // Insert into the project table with the owner_id from the previous insert
                DB::table('project')->insert([
                    'name' => $faker->company,
                    'address1' => $faker->streetAddress,
                    'address2' => $faker->secondaryAddress,
                    'city' => $faker->numberBetween(1, 800),
                    'state' => NULL,
                    'country' => NULL,
                    'email' => $faker->unique()->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'mobile_phone' => $faker->phoneNumber,
                    'owner_id' => $ownerId, // Use the created owner id
                    'sales_id' => $user_id,
                    'notes' => $faker->sentence,
                    'project_status' => '1',
                    'is_active' => '1',
                    'project_category_id' => $faker->numberBetween(1, 2),
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);
            }
        }

    }
}

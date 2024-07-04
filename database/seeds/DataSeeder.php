<?php

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Owner;
use App\Models\ProjectCategory;
use App\Models\OwnerCategory;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectCategory::firstOrCreate(['name' => 'Apartment']);
        ProjectCategory::firstOrCreate(['name' => 'Rumah Tinggal']);

        OwnerCategory::firstOrCreate(['name' => 'Arsitek']);
        OwnerCategory::firstOrCreate(['name' => 'Interior Design']);

    }
}

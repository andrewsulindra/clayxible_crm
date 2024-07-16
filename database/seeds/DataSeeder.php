<?php

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Owner;
use App\Models\ProjectCategory;
use App\Models\OwnerCategory;
use App\Models\Group;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectCategory::firstOrCreate(['name' => 'Rumah Tinggal']);
        ProjectCategory::firstOrCreate(['name' => 'Resto']);
        ProjectCategory::firstOrCreate(['name' => 'Hotel dan Resort']);
        ProjectCategory::firstOrCreate(['name' => 'Gedung Swasta']);
        ProjectCategory::firstOrCreate(['name' => 'Gedung Pemerintah']);

        OwnerCategory::firstOrCreate(['name' => 'Owner']);
        OwnerCategory::firstOrCreate(['name' => 'Arsitek']);
        OwnerCategory::firstOrCreate(['name' => 'Design Interior']);
        OwnerCategory::firstOrCreate(['name' => 'Kontraktor']);
        OwnerCategory::firstOrCreate(['name' => 'MK']);


        Group::firstOrCreate(['name' => 'Principal']);
        // Group::firstOrCreate(['name' => 'Agent A']);
        // Group::firstOrCreate(['name' => 'Agent B']);

    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportSqlSeeder extends Seeder
{
    public function run()
    {
        // Path to your SQL file
        $sqlFile = database_path('custom_script/city.sql');

        // Read SQL file
        $sql = file_get_contents($sqlFile);

        // Execute SQL statements
        DB::unprepared($sql);
    }
}


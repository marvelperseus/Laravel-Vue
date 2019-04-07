<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class PatientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Eloquent\Patient::class, 200)->create();
    }
}

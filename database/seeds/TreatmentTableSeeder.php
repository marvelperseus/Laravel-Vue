<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class TreatmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        DB::table('treatments')->delete();

        DB::table('treatments')->insert([
            [
                'treatment' => '初診カウンセリング',
                'time' => 60,
                'created_at' => now(),
                'updated_at' => now(),
                'clinic_id' => 2
            ]
        ]);

        DB::table('treatments')->insert([
            [
                'treatment' => '初診カウンセリング',
                'time' => 60,
                'created_at' => now(),
                'updated_at' => now(),
                'clinic_id' => 3
            ]
        ]);

        DB::table('treatments')->insert([
            [
                'treatment' => '初診カウンセリング',
                'time' => 60,
                'created_at' => now(),
                'updated_at' => now(),
                'clinic_id' => 4
            ]
        ]);

        DB::table('treatments')->insert([
            [
                'treatment' => '初診カウンセリング',
                'time' => 60,
                'created_at' => now(),
                'updated_at' => now(),
                'clinic_id' => 5
            ]
        ]);

    }
}

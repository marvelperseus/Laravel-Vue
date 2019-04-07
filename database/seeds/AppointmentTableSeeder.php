<?php

use Illuminate\Database\Seeder;

class AppointmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('appointments')->delete();
        factory(\App\Models\Eloquent\Appointment::class, 'takahashi', 30)->create();
        factory(\App\Models\Eloquent\Appointment::class, 'nonomura', 30)->create();
        factory(\App\Models\Eloquent\Appointment::class, 'matsuoka', 30)->create();
        factory(\App\Models\Eloquent\Appointment::class, 'kanamori', 30)->create();
    }
}

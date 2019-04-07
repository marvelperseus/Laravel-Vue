<?php

use Illuminate\Database\Seeder;

class AppointmentTableSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Eloquent\AppointmentTableSetting::class, 'takahashi')->create();
        factory(\App\Models\Eloquent\AppointmentTableSetting::class, 'nonomura')->create();
        factory(\App\Models\Eloquent\AppointmentTableSetting::class, 'matsuoka')->create();
        factory(\App\Models\Eloquent\AppointmentTableSetting::class, 'kanamori')->create();
    }
}

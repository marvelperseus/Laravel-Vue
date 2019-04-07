<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
		//            VldevSeeder::class,
		//            ClinicTableSeeder::class,
		//            PatientTableSeeder::class,
		//            TreatmentTableSeeder::class,
		//            AppointmentTableSettingSeeder::class,
		//            AppointmentTableSeeder::class,
		//            MailInboxTableSeeder::class,
		//
		UsersTableSeeder::class,
        ]);
    }
}

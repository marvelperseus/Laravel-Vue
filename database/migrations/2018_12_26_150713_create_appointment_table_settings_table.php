<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentTableSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_table_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('display_unit_number');
            $table->text('unit_names')->nullable();
            $table->unsignedInteger('time_delimiter')->nullable();
            $table->string('display_items')->nullable();
            $table->timestamps();

            $table->unsignedInteger('clinic_id');
            $table->foreign('clinic_id')->references('id')->on('clinics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_table_settings');
    }
}

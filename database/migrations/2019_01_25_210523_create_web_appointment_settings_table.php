<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebAppointmentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_appointment_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('auto_regist');
            $table->string('cancel_auto_regist');
            $table->text('units');
            $table->text('target');
            $table->unsignedInteger('start_day')->nullable();
            $table->unsignedInteger('term')->nullable();
            $table->unsignedInteger('time_delimiter')->nullable();
            $table->unsignedInteger('day_limit_count')->nullable();
            $table->unsignedInteger('max_limit_count')->nullable();
            $table->boolean('web_cancel_flag');
            $table->unsignedInteger('permit_cancel_day')->nullable();
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
        Schema::dropIfExists('web_appointment_settings');
    }
}

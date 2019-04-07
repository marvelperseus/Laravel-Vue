<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporaryAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->unsignedInteger('treatment_id');
            $table->unsignedTinyInteger('unit');
            $table->string('way')->nullable();
            $table->text('note')->nullable();
            $table->text('form_content');
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
        Schema::dropIfExists('temporary_appointments');
    }
}

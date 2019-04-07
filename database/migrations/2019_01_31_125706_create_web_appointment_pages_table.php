<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebAppointmentPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_appointment_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('special_note')->nullable();
            $table->text('cancel_message')->nullable();
            $table->text('end_message')->nullable();
            $table->text('form_objects')->nullable();
            $table->text('thankspage_url')->nullable();
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
        Schema::dropIfExists('web_appointment_pages');
    }
}

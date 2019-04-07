<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('karte_number')->nullable();
            $table->string('name');
            $table->string('kana_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->text('contact_way')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->string('zip')->nullable();
            $table->text('address')->nullable();
            $table->string('insurance_number')->nullable();
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
        Schema::dropIfExists('patients');
    }
}

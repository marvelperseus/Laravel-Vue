<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecallMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recall_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('draft_flag');
            $table->string('patient_status');
            $table->unsignedTinyInteger('timing_number');
            $table->string('timing_unit');
            $table->time('time');
            $table->string('title')->nullable();
            $table->text('body');
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
        Schema::dropIfExists('recall_masters');
    }
}

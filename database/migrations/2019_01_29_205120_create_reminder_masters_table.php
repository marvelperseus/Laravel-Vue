<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReminderMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('draft_flag');
            $table->unsignedTinyInteger('timing');
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
        Schema::dropIfExists('reminder_masters');
    }
}

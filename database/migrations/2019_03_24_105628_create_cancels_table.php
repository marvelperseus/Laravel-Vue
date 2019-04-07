<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancels', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('canceled_at');
            $table->dateTime('cancel_way');
            $table->dateTime('note')->nullable();
            $table->dateTime('follow_at')->nullable();
            $table->unsignedInteger('new_appointment_id')->nullable();
            $table->unsignedInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('appointments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancels');
    }
}

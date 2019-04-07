<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsClinicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('zip')->nullable()->after('tel');
            $table->text('address')->nullable()->after('zip');
            $table->text('url')->nullable()->after('address');
            $table->string('app_key')->unique()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('zip');
            $table->dropColumn('address');
            $table->dropColumn('url');
            $table->dropColumn('key');
        });
    }
}

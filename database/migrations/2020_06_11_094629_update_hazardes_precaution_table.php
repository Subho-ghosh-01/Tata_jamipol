<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHazardesPrecautionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hazardes', function (Blueprint $table) {
            $table->string('hazarde')->nullable()->after('direction');
            $table->string('precaution')->nullable()->after('hazarde');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hazardes', function (Blueprint $table) {
            //
        });
    }
}

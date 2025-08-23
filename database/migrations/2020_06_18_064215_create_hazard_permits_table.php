<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHazardPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hazard_permits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('permit_id');
            $table->string('direction');
            $table->unsignedInteger('hazarde');
            $table->unsignedInteger('precaution');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hazard_permits');
    }
}

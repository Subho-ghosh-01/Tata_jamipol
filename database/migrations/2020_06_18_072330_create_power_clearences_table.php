<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowerClearencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_clearences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('permit_id');  
            $table->string('positive_isolation_no');  
            $table->string('equipment');  
            $table->string('location');  
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
        Schema::dropIfExists('power_clearences');
    }
}

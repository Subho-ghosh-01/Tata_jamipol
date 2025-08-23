<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaClearencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_clearences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('job_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('area_clearences');
    }
}

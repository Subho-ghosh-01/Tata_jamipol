<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatePassDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gate_pass_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('permit_id');
            $table->string('employee_name');
            $table->string('gate_pass_no');
            $table->string('designation');
            $table->string('age',10);
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
        Schema::dropIfExists('gate_pass_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name1',255);
            $table->string('telf1',255);
            $table->string('lovem',255);
            $table->string('lifnr',255);
            $table->string('smtp_addr',255);
            $table->unsignedInteger('sperq');
            $table->unsignedInteger('vendor_id');
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
        Schema::dropIfExists('vendor_details');
    }
}

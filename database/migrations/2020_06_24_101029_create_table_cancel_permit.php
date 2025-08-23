<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCancelPermit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_cancels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("permit_id");
            $table->date("date");
            $table->longText("violations_details");
            $table->string("img1");
            $table->string("img2");
            $table->string("img3");
            $table->unsignedInteger("cancel_id");

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
        Schema::dropIfExists('permit_cancels');
    }
}

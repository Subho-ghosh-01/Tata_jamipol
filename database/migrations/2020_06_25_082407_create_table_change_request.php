<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableChangeRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('permit_id');
            $table->unsignedInteger('old_id');
            $table->unsignedInteger('new_id');
            $table->string('type');
            $table->time('old_time');
            $table->time('new_time');
            $table->unsignedInteger('change_by_id');
            $table->string('status',20);
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
        Schema::dropIfExists('change_requests');
    }
}

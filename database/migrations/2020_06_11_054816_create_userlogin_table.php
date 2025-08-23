<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserloginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userlogins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('password',100);
            $table->string('vendor_code');
            $table->unsignedInteger('user_type');
            $table->unsignedInteger('user_sub_type');
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
        Schema::dropIfExists('userlogins');
    }
}

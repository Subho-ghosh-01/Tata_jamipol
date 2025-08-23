<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('division_id');
            $table->unsignedInteger('section_id');
            $table->string('order_no');
            $table->date('order_validity');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('job_id');
            $table->string('swp_number',255);
            $table->string('high_risk'); 
            $table->string('power_clearance');
            $table->string('confined_space');
            $table->unsignedInteger('issuer_id');
            $table->string('post_site_pic');
            $table->string('latlong');
            $table->string('safe_work',2);
            $table->string('all_person',2);
            $table->string('worker_working',2);
            $table->string('all_lifting_tools',2);
            $table->string('all_safety_requirement',2);
            $table->string('all_person_are_trained',2);
            $table->string('ensure_the_appplicablle',2);
            $table->string('shut_down',2);
            $table->string('power_clearance_number',2);
            $table->unsignedInteger('issuer_2');
            $table->string('area_clearence_required');
            $table->unsignedInteger('area_clearence_id');
            $table->unsignedInteger('entered_by');
            $table->string('status');
            $table->unsignedInteger('pending_from_id');
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
        Schema::dropIfExists('permits');
    }
}

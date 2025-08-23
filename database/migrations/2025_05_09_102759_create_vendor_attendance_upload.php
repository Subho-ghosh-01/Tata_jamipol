<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_attendance_upload', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('document'); // Use a string or text instead of varchar(MAX)
            $table->date('month');
            $table->dateTime('created_date');

            $table->unsignedBigInteger('hr_by')->nullable();
            $table->text('hr_remarks')->nullable();
            $table->string('hr_decision')->nullable();
            $table->dateTime('hr_decision_datetime')->nullable();

            $table->unsignedBigInteger('account_by')->nullable();
            $table->text('account_remarks')->nullable();
            $table->string('account_decision')->nullable();
            $table->dateTime('account_decision_datetime')->nullable();

            $table->string('status')->default('Pending');

            $table->timestamps(); // adds created_at and updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_attendance_upload');
    }
};

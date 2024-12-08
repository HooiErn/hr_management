<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviewer', function (Blueprint $table) {
            $table->id();
            $table->string('job_title')->nullable();
            $table->string('candidate_id');
            $table->string('name')->nullable();
            $table->string('ic_number', 12)->unique(); 
            $table->integer('age')->nullable();
            $table->string('race')->nullable(); 
            $table->string('gender')->nullable();  
            $table->string('birth_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('highest_education')->nullable(); 
            $table->integer('work_experiences')->nullable();  
            $table->text('message')->nullable();
            $table->string('cv_upload')->nullable();
            $table->dateTime('interview_datetime')->nullable();  // Added interview_date and interview_time as combined datetime field
            $table->string('status')->nullable();
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
        Schema::dropIfExists('interviewers');
    }
};

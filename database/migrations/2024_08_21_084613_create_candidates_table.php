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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('candidate_id');
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->string('job_title')->nullable();
            $table->string('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('race')->nullable();  
            $table->string('phone_number')->nullable();
            $table->string('highest_education')->nullable(); 
            $table->integer('work_experiences')->nullable();  
            $table->text('message')->nullable();
            $table->string('cv_upload')->nullable();
            $table->dateTime('interview_datetime')->nullable(); 
            $table->string('role_name')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('candidates');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetsTable extends Migration
{
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interviewer_id')->constrained()->onDelete('cascade'); // Assuming you have an interviewers table
            $table->date('date');
            $table->dateTime('scheduled_time');
            $table->string('status')->default('Scheduled'); // You can adjust the default status as needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('timesheets');
    }
}
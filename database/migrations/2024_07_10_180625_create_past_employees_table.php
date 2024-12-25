<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePastEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('past_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('department')->nullable();
            $table->string('role_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('status')->default('resigned');
            $table->date('resignation_date')->nullable(); 
            $table->text('resignation_reason')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('past_employees');
    }
} 
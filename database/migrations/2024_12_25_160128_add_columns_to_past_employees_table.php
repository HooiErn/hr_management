<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPastEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('past_employees', function (Blueprint $table) {
            $table->date('resignation_date')->nullable(); // Adding a resignation date column
            $table->text('resignation_reason')->nullable(); // Adding a resignation reason column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('past_employees', function (Blueprint $table) {
            $table->dropColumn(['resignation_date', 'resignation_reason']); // Drop the columns if rollback
        });
    }
}

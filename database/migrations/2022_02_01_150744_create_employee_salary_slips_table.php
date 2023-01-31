<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalarySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary_slips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('month');
            $table->unsignedBigInteger('employee_id');
            $table->longText('extra_info');
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
        Schema::dropIfExists('employee_salary_slips');
    }
}

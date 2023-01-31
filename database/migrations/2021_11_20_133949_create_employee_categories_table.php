<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('title');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('image')->nullable();
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
        Schema::dropIfExists('employee_categories');
    }
}

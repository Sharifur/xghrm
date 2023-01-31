<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->timestamp('joinDate')->nullable();
            $table->timestamp('dateOfBirth')->nullable();
            $table->text('paymentInfo')->nullable();
            $table->string('emergencyNumber');
            $table->string('mobile');
            $table->text('address');
            $table->text('salary');
            $table->text('name');
            $table->text('email');
            $table->unsignedBigInteger('catId',false);
            $table->unsignedBigInteger('imageId',false)->nullable();
            $table->text('personalInfo')->nullable();
            $table->unsignedBigInteger('status')->default(0);
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
        Schema::dropIfExists('employees');
    }
}

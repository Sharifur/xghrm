<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('from')->default(0);
            $table->timestamp('month');
            $table->float('personal_earning')->nullable();
            $table->float('office_earning')->nullable();
            $table->string('statement')->nullable();
            $table->string('en_username')->nullable();
            $table->float('percentage',8,2)->nullable();
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
        Schema::dropIfExists('earnings');
    }
}

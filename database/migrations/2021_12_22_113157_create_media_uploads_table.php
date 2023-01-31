<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_uploads', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('path');
            $table->text('alt')->nullable();
            $table->string('size')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('extension',10)->nullable();
            $table->enum('type',['admin','user'])->default('admin');
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
        Schema::dropIfExists('media_uploads');
    }
}

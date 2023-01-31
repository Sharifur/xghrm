<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->unsignedBigInteger('author',false);
            $table->unsignedBigInteger('enItemId',false)->nullable();
            $table->unsignedBigInteger('category',false)->nullable();
            $table->timestamp('releaseDate')->nullable();
            $table->unsignedBigInteger('thumbnail',false)->nullable();
            $table->unsignedBigInteger('developer',false);
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
        Schema::dropIfExists('products');
    }
}

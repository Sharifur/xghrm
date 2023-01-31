<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            $table->unsignedBigInteger('email_verified')->default(0);
            $table->string('phone')->nullable();
            $table->text('email_verify_token')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->nullable();
            $table->unsignedBigInteger('banned')->default(0);
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
        Schema::dropIfExists('users');
    }
}

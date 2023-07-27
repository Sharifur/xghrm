<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn("employees","zktecho_user_id")){
                $table->unsignedBigInteger("zktecho_user_id")->default(null);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn("employees","zktecho_user_id")){
                $table->dropColumn("zktecho_user_id");
            }
        });
    }
};

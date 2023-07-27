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
        Schema::table('attendance_logs', function (Blueprint $table) {
            if (!Schema::hasColumn("attendance_logs","uuid")){
                $table->string("uuid")->default(\Illuminate\Support\Str::uuid());
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
        Schema::table('attendance_logs', function (Blueprint $table) {
            if (Schema::hasColumn("attendance_logs","uuid")){
                $table->dropColumn("uuid");
            }
        });
    }
};

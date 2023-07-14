<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnToAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            if (!Schema::hasColumn("attendance_logs","status")){
                $table->unsignedBigInteger("status")->default(1)->comment("0=not_approved,1=approved");
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
            if (Schema::hasColumn("attendance_logs","status")){
                $table->dropColumn("status");
            }
        });
    }
}

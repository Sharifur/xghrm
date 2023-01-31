<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees','att_id')){
                $table->string('att_id',30)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees','att_id')){
                $table->string('att_id',30)->nullable();
            }
        });
    }
};

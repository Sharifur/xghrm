<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('balance_sheet_items', function (Blueprint $table) {
            $table->string('currency', 3)->default('BDT')->after('amount');
            $table->decimal('bdt_amount', 12, 2)->nullable()->after('currency')->comment('Amount converted to BDT for calculations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balance_sheet_items', function (Blueprint $table) {
            $table->dropColumn(['currency', 'bdt_amount']);
        });
    }
};
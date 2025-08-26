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
            $table->string('frequency', 50)->nullable()->after('is_recurring');
            $table->decimal('original_amount', 12, 2)->nullable()->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balance_sheet_items', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'original_amount']);
        });
    }
};

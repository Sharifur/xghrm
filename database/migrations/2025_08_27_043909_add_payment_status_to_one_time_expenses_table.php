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
        Schema::table('one_time_expenses', function (Blueprint $table) {
            $table->enum('payment_status', ['paid', 'unpaid', 'pending'])->default('unpaid');
            $table->date('paid_date')->nullable();
            $table->text('payment_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('one_time_expenses', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_date', 'payment_notes']);
        });
    }
};

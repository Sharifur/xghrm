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
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->date('last_paid_date')->nullable()->after('sort_order');
            $table->enum('payment_status', ['paid', 'unpaid', 'pending', 'overdue'])->default('unpaid')->after('last_paid_date');
            $table->date('next_due_date')->nullable()->after('payment_status');
            $table->text('payment_notes')->nullable()->after('next_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropColumn(['last_paid_date', 'payment_status', 'next_due_date', 'payment_notes']);
        });
    }
};
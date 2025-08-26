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
        Schema::create('one_time_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('BDT');
            $table->decimal('bdt_amount', 12, 2)->comment('Amount converted to BDT for calculations');
            $table->date('expense_date')->default(now());
            $table->string('category')->default('General');
            $table->string('icon')->default('fas fa-receipt');
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['expense_date', 'category']);
            $table->index('created_by');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('one_time_expenses');
    }
};
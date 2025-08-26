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
        Schema::create('income_sources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('income_date');
            $table->string('income_type'); // client_payment, service_income, product_sales, etc.
            $table->string('source_type'); // one-time, recurring, contract
            $table->string('client_name')->nullable();
            $table->string('project_name')->nullable();
            $table->string('invoice_number')->nullable();
            $table->enum('status', ['pending', 'received', 'partially_received', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // bank_transfer, cash, cheque, card
            $table->decimal('received_amount', 15, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->date('received_date')->nullable();
            $table->json('attachments')->nullable(); // invoice, receipt files
            $table->text('notes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_frequency')->nullable(); // monthly, weekly, yearly
            $table->date('next_expected_date')->nullable();
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2); // amount after tax
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->index(['income_date', 'income_type']);
            $table->index(['status', 'is_recurring']);
            $table->index(['due_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_sources');
    }
};

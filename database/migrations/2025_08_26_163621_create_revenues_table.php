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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('service_type', ['webflow_template', 'shopify_app', 'web_development', 'consulting', 'maintenance', 'other'])->default('web_development');
            $table->decimal('amount', 10, 2);
            $table->enum('currency', ['BDT', 'USD'])->default('BDT');
            $table->decimal('bdt_amount', 10, 2);
            $table->enum('status', ['paid', 'pending', 'overdue'])->default('paid');
            $table->date('expected_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->timestamp('paid_date')->nullable();
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('service_type');
            $table->index('expected_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};

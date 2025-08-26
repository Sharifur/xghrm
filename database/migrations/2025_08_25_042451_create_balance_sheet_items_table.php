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
        Schema::create('balance_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balance_sheet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['asset', 'liability', 'equity']);
            $table->string('name');
            $table->decimal('amount', 15, 2);
            $table->text('tooltip')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_recurring')->default(false); // For forecasting
            $table->decimal('average_amount', 15, 2)->nullable(); // For forecasting
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['balance_sheet_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_sheet_items');
    }
};

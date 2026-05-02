<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('ai_applications', 'api_applications');
    }

    public function down(): void
    {
        Schema::rename('api_applications', 'ai_applications');
    }
};

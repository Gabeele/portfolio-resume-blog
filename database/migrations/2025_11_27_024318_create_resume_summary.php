<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resume_summary', function (Blueprint $table) {
            $table->foreignId('resume_id')->constrained('resumes');
            $table->foreignId('summary_id')->constrained('summaries');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_summary');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resume_reference', function (Blueprint $table) {
            $table->foreignId('resume_id')->constrained('resumes');
            $table->foreignId('reference_id')->constrained('references');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_reference');
    }
};

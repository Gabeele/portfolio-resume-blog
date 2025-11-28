<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resume_work_experience', function (Blueprint $table) {
            $table->foreignId('resume_id')->constrained('resumes');
            $table->foreignId('work_experience_id')->constrained('work_experiences');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_work_experience');
    }
};

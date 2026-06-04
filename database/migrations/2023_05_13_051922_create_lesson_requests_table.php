<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('request_file')->nullable();
            $table->string('solution_file')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('is_fulfilled')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->timestamp('requested_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_requests');
    }
};

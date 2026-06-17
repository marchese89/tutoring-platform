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
        Schema::table('theme_areas', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->unique(['theme_area_id', 'name']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->unique(['subject_id', 'name']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->unique(['course_id', 'number']);
        });

        Schema::table('exercises', function (Blueprint $table) {
            $table->unique(['course_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropUnique(['course_id', 'title']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropUnique(['course_id', 'number']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique(['subject_id', 'name']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropUnique(['theme_area_id', 'name']);
        });

        Schema::table('theme_areas', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};

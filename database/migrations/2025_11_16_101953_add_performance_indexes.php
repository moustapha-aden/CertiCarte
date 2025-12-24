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
        // Add indexes to students table
        Schema::table('students', function (Blueprint $table) {
            // Individual column indexes
            $table->index('matricule', 'students_matricule_index');
            $table->index('classe_id', 'students_classe_id_index');
            $table->index('created_at', 'students_created_at_index');
            $table->index('name', 'students_name_index');
            $table->index('gender', 'students_gender_index');
            $table->index('place_of_birth', 'students_place_of_birth_index');

            // Composite index for class-based queries with sorting
            $table->index(['classe_id', 'created_at'], 'students_classe_created_index');
        });

        // Add indexes to classes table
        Schema::table('classes', function (Blueprint $table) {
            $table->index('year_id', 'classes_year_id_index');
            $table->index('label', 'classes_label_index');
        });

        // Note: school_years.year already has unique index
        // Note: users.email already has unique index
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_matricule_index');
            $table->dropIndex('students_classe_id_index');
            $table->dropIndex('students_created_at_index');
            $table->dropIndex('students_name_index');
            $table->dropIndex('students_gender_index');
            $table->dropIndex('students_place_of_birth_index');
            $table->dropIndex('students_classe_created_index');
        });

        // Remove indexes from classes table
        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex('classes_year_id_index');
            $table->dropIndex('classes_label_index');
        });
    }
};

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
        Schema::create('student_import_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_import_id')->constrained()->onDelete('cascade');
            $table->integer('row_number');
            $table->string('error_type');
            $table->text('error_message');
            $table->json('row_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_import_errors');
    }
};

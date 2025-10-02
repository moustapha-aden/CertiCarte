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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('gender', ['M', 'F']);
            $table->string('photo')->nullable()->default('https://cdn-icons-png.flaticon.com/512/5850/5850276.png');
            $table->string('pays')->nullable();
            $table->enum('situation', ['NR', 'R']);
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

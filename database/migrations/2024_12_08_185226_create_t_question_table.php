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
        Schema::create('t_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id');
            $table->string('question');
            $table->enum('question_type', ['multiple_choice', 'essay']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_question');
    }
};

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
        Schema::create('t_answer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('t_submission')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('t_question')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->foreignId('choice_id')->nullable()->constrained('t_choice')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_answer');
    }
};

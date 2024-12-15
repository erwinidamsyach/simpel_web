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
        Schema::create('t_submission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('t_materi')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('m_siswa');
            $table->timestamp('start_at');
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('grade', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_submission');
    }
};

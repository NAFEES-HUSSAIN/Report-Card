<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->decimal('marks', 5, 2);
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['report_card_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_scores');
    }
};

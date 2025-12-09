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
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('study_set_id')->constrained('study_sets')->onDelete('cascade');
            $table->string('japanese_word', 255);
            $table->string('japanese_reading', 255);
            $table->text('meaning');
            $table->text('example_sentence')->nullable();
            $table->string('pitch_accent', 255)->nullable();
            $table->boolean('is_mastered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};

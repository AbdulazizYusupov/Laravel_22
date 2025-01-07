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
        Schema::create('att_chars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('att_id')->constrained('attributes')->onDelete('cascade');
            $table->foreignId('char_id')->constrained('characters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('att_chars');
    }
};

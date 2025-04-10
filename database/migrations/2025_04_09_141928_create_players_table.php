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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['Masculino', 'Femenino']);
            $table->unsignedTinyInteger('skill_level'); // 0 - 100

            // Solo para masculino
            $table->unsignedTinyInteger('strength')->nullable();   // 0 - 100
            $table->unsignedTinyInteger('speed')->nullable();      // 0 - 100

            // Solo para femenino
            $table->unsignedTinyInteger('reaction_time')->nullable(); // 0 - 100

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

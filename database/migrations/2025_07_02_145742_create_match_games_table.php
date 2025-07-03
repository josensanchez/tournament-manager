<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_player_id')->constrained('players')->nullable();
            $table->foreignId('second_player_id')->constrained('players')->nullable();
            $table->foreignId('winner_id')->nullable();
            $table->foreignId('tournament_id');
            $table->string('score')->nullable();
            $table->string('state');
            $table->integer('stage');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_games');
    }
};

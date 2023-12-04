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
        Schema::create('STARSHIPS', function (Blueprint $table) {
            $table->unsignedBigInteger('SWAPI_ID');
            $table->string('STARSHIP_CLASS', 100);
            $table->unsignedInteger('COUNT')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('STARSHIPS');
    }
};

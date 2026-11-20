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
        Schema::create('ouvrier_metier', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ouvrier_id');
            $table->foreign('ouvrier_id')->references('id')->on('ouvriers')->onDelete('cascade');
            $table->uuid('metier_id');
            $table->foreign('metier_id')->references('id')->on('metiers')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['ouvrier_id', 'metier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ouvrier_metier');
    }
};

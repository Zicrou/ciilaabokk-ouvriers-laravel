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
        Schema::create('ouvriers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->uuid('region_id');
            $table->foreign('region_id')
                ->references('id')
                ->on('regions')
                ->onDelete('cascade');
            $table->uuid('departement_id');
            $table->foreign('departement_id')
                ->references('id')
                ->on('departements')
                ->onDelete('cascade');
            $table->string('telephone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable(false);
            $table->string('telephone_2')->nullable()->nullable();
            $table->string('photo')->nullable();
            $table->integer('annees_experience')->nullable();
            $table->string("entreprises")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ouvriers');
    }
};

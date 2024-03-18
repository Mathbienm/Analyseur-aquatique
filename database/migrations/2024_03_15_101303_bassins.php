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
        Schema::create('bassins', function (Blueprint $table) {
            $table->id();
            $table->string('nom_bassin');
            $table->float('seuil_temperature');
            $table->float('seuil_ph');
            $table->string('ip_arduino');
            $table->string('frequence_retrieval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

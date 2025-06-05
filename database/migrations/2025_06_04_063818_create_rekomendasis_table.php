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
        Schema::create('rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->string('penggunaan');
            $table->string('jenis_tanah');
            $table->string('kadar_air');
            $table->string('lereng');
            $table->string('rekomendasi_tanaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasis');
    }
};

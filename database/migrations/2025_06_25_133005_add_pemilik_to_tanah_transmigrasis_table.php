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
        Schema::table('tanah_transmigrasis', function (Blueprint $table) {
            $table->string('pemilik')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanah_transmigrasis', function (Blueprint $table) {
            $table->dropColumn('pemilik');
        });
    }
};

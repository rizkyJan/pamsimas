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
        Schema::table('tagihans', function (Blueprint $table) {
            $table->decimal('denda', 10, 2)->default(0)->after('total_tagihan');
            $table->decimal('total_bayar', 10, 2)->default(0)->after('denda');
        });
    }

    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['denda', 'total_bayar']);
        });
    }
};

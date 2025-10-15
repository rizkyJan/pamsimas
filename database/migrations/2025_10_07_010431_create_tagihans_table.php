<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();

            // relasi ke pelanggan
            $table->foreignId('pelanggan_id')
                ->constrained('pelanggans')
                ->onDelete('cascade');

            // relasi ke bulan
            $table->foreignId('bulan_id')
                ->constrained('bulans')
                ->onDelete('cascade');

            // relasi ke tarif
            $table->foreignId('tarif_id')
                ->constrained('tarifs')
                ->onDelete('cascade');

            $table->integer('pemakaian');
            $table->decimal('total_tagihan', 10, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['belum bayar', 'sudah bayar'])->default('belum bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};

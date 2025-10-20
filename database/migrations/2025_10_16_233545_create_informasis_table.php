<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('informasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->text('isi')->nullable();
            $table->timestamps();
        });

        // Insert 1 baris default
        DB::table('informasis')->insert([
            'judul' => 'Informasi Awal',
            'isi' => 'Belum ada informasi saat ini.',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('informasis');
    }
};

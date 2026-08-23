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
        Schema::create('jurnal_pasiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pasien_id')->nullable()->constrained('pasiens')->nullOnDelete();
            $table->integer('total_minum')->default(0); // dalam ml
            $table->string('tekanan_darah')->nullable(); // contoh: 120/80
            $table->string('kondisi_akses')->default('Aman'); // Aman, Bengkak, Nyeri
            $table->boolean('gejala_sesak')->default(false);
            $table->boolean('gejala_kram')->default(false);
            $table->json('makanan')->nullable(); // menyimpan array makanan
            $table->integer('total_kalori')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_pasiens');
    }
};
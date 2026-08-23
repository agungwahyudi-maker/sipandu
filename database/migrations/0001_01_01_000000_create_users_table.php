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
        // 1. Tabel Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['petugas', 'pasien'])->default('pasien');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabel Password Reset Tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Tabel Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 4. Tabel Pasien (SIPANDU HD)
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Identitas Pasien
            $table->string('no_rm', 20)->unique()->comment('Nomor Rekam Medis');
            $table->string('nik', 16)->nullable()->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->string('no_hp_pasien', 20)->nullable();
            $table->string('no_hp_keluarga', 20)->nullable()->comment('Nomor WA Keluarga Pasien');
            
            // Profil Klinis Hemodialisa
            $table->enum('jenis_akses', ['cimino', 'cdl', 'femoral'])->default('cimino');
            $table->string('posisi_akses')->nullable()->comment('Contoh: AV Fistula Brachial Dextra');
            $table->decimal('berat_badan_kering', 5, 2)->nullable()->comment('Target BB Kering dalam Kg');
            $table->enum('jadwal_hd', ['senin_kamis', 'selasa_jumat', 'rabu_sabtu'])->nullable();
            $table->enum('sesi_hd', ['pagi', 'siang', 'sore'])->default('pagi');
            $table->enum('status_aktif', ['aktif', 'rawat_inap', 'transplantasi', 'meninggal', 'pindah'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
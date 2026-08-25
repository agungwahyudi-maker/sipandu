<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pasien extends Authenticatable
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'pasiens';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'no_rm',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp_pasien',
        'no_hp_keluarga',
        'jenis_akses',
        'posisi_akses',
        'berat_badan_kering',
        'jadwal_hd',
        'sesi_hd',
        'status_aktif',
    ];

    /**
     * Cast atribut ke tipe data yang sesuai.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan_kering' => 'decimal:2',
    ];

    /**
     * Relasi ke Model User (Pasien terhubung ke akun Login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getAuthPassword()
    {
        return null; // Pasien tidak menggunakan password
    }

    public function getRememberTokenName()
    {
        return null; // Pasien tidak menggunakan remember token
    }
}
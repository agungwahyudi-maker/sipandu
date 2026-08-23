<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalPasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pasien_id',
        'total_minum',
        'tekanan_darah',
        'kondisi_akses',
        'gejala_sesak',
        'gejala_kram',
        'makanan',
        'total_kalori',
    ];

    /**
     * Casting kolom makanan otomatis ke array PHP saat dibaca & JSON saat disimpan.
     */
    protected $casts = [
        'gejala_sesak' => 'boolean',
        'gejala_kram' => 'boolean',
        'makanan' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }
}
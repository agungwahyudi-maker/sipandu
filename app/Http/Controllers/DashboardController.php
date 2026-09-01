<?php

namespace App\Http\Controllers;

use App\Models\JurnalPasien;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Jika user adalah Pasien, load data terkait pasien
        if ($user->role === 'pasien' || $user->no_rm) {
            return Inertia::render('Dashboard', [
                'pasien' => $user->pasien,
            ]);
        }

        // ==========================================
        // QUERY DATABASE UNTUK PETUGAS / TIM MEDIS
        // ==========================================

        // 1. Stat Metrics
        $totalPasien = Pasien::count();
        $hdHariIniCount = JurnalPasien::whereDate('created_at', now())->count();
        $jurnalHariIniCount = JurnalPasien::whereDate('created_at', now())->count();

        // 2. Pasien Kritis (Tensi tinggi >= 160 ATAU kondisi akses tidak aman)
        $criticalPatients = JurnalPasien::with('pasien.user')
            ->whereDate('created_at', now())
            ->where(function ($query) {
                $query->where('kondisi_akses', '!=', 'Aman')
                      ->orWhere('gejala_sesak', true);
            })
            ->latest()
            ->get()
            ->map(function ($jurnal) {
                return [
                    'id' => $jurnal->id,
                    'no_rm' => $jurnal->pasien->no_rm ?? '-',
                    'nama' => $jurnal->pasien->user->name ?? 'Pasien',
                    'status' => $jurnal->kondisi_akses,
                    'tensi' => $jurnal->tekanan_darah ?? '-',
                    'no_hp_pasien' => $jurnal->pasien->no_hp_pasien ?? '-',
                    'minum' => $jurnal->total_minum . ' ml',
                    'keluhan' => $jurnal->gejala_sesak ? 'Sesak Napas Berat' : ($jurnal->gejala_kram ? 'Kram Otot' : 'Akses Bermasalah'),
                ];
            });

        // 3. Rekap Tren Mingguan (6 Hari Terakhir)
        $weeklyData = JurnalPasien::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->map(function ($row) {
                return [
                    'day' => \Carbon\Carbon::parse($row->date)->translatedFormat('D'),
                    'count' => $row->count,
                    'percentage' => min(100, ($row->count / 30) * 100), // Asumsi target max 30 pasien/hari
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_pasien' => $totalPasien,
                'hd_hari_ini' => $hdHariIniCount,
                'jurnal_hari_ini' => $jurnalHariIniCount,
                'sos_aktif' => 0, // Disesuaikan dengan tabel SOS jika ada
            ],
            'criticalPatients' => $criticalPatients,
            'weeklyData' => $weeklyData,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\JurnalPasien;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class JurnalPasienController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        if (in_array($user->role, ['petugas', 'admin'])) {
            $logs = JurnalPasien::with('user') // Eager loading relasi user/pasien
                ->latest()
                ->paginate(10);
        } else {
            // Jika pasien, hanya tampilkan log jurnal miliknya sendiri
            $logs = JurnalPasien::where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return Inertia::render('Pasien/Jurnal/Index', [
            'logs' => $logs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'total_minum'   => 'required|integer|min:0',
            'tekanan_darah' => 'nullable|string|max:20',
            'kondisi_akses' => 'required|string',
            'gejala_sesak'  => 'required|boolean',
            'gejala_kram'   => 'required|boolean',
            'makanan'       => 'nullable|array',
            'total_kalori'  => 'required|integer|min:0',
        ]);

        $user = $request->user();

        JurnalPasien::create([
            'user_id'       => $user->id,
            'pasien_id'     => $user->pasien?->id,
            'total_minum'   => $validated['total_minum'],
            'tekanan_darah' => $validated['tekanan_darah'],
            'kondisi_akses' => $validated['kondisi_akses'],
            'gejala_sesak'  => $validated['gejala_sesak'],
            'gejala_kram'   => $validated['gejala_kram'],
            'makanan'       => $validated['makanan'] ?? [],
            'total_kalori'  => $validated['total_kalori'],
        ]);

        return redirect()->back()->with('message', 'Jurnal harian berhasil disimpan!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user (Index).
     */
    public function index(Request $request): Response
    {
        $query = User::with('pasien');

        // Fitur Pencarian (Search by Name, Email, atau No RM Pasien)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pasien', function ($qPasien) use ($request) {
                      $qPasien->where('no_rm', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Fitur Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Fetch Data dengan Pagination
        $users = $query->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'pegawai',
                'created_at' => $user->created_at->format('d M Y'),
                'pasien' => $user->pasien ? [
                    'id' => $user->pasien->id,
                    'no_rm' => $user->pasien->no_rm,
                    'nik' => $user->pasien->nik,
                    'jadwal_hd' => $user->pasien->jadwal_hd,
                ] : null,
            ]);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    /**
     * Tampilkan form tambah user (Create).
     */
    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Simpan user baru ke database (Store).
     */
    public function store(Request $request)
    {
        // Validasi input user dan data pasien
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'role' => 'required|string|in:admin,petugas,pasien,pegawai',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Validasi Khusus Pasien (Mandatori jika role = pasien)
            'no_rm' => 'required_if:role,pasien|nullable|string|max:50|unique:pasiens,no_rm',
            'nik' => 'required_if:role,pasien|nullable|string|max:16',
            'jenis_kelamin' => 'required_if:role,pasien|nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp_pasien' => 'nullable|string|max:20',
            'no_hp_keluarga' => 'nullable|string|max:20',
            'jenis_akses' => 'nullable|string',
            'posisi_akses' => 'nullable|string',
            'berat_badan_kering' => 'nullable|numeric',
            'jadwal_hd' => 'nullable|string',
            'sesi_hd' => 'nullable|string',
            'status_aktif' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            // 2. Jika role adalah pasien, buat record data di tabel pasiens
            if ($request->role === 'pasien') {
                Pasien::create([
                    'user_id' => $user->id,
                    'no_rm' => $request->no_rm,
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->name,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'alamat' => $request->alamat,
                    'no_hp_pasien' => $request->no_hp_pasien,

                    'no_hp_keluarga'     => $request->no_hp_keluarga ?? '-',
                    'jenis_akses'        => $request->jenis_akses ?? 'femoral',
                    'posisi_akses'       => $request->posisi_akses ?? '-',
                    'berat_badan_kering' => $request->berat_badan_kering ?? 0,
                    'jadwal_hd'          => $request->jadwal_hd,
                    'sesi_hd'            => $request->sesi_hd,
                    'status_aktif'       => $request->status_aktif ?? 'aktif',
                ]);
            }
        });

        return redirect()->route('users.index')->with('success', 'User/Pasien berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user (Edit).
     */
    public function edit(User $user): Response
    {
        $user->load('pasien');

        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'pegawai',
                'pasien' => $user->pasien,
            ],
        ]);
    }

    /**
     * Update data user & pasien di database (Update).
     */
    public function update(Request $request, User $user)
    {
        $pasienId = $user->pasien ? $user->pasien->id : null;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:admin,petugas,pasien,pegawai',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            
            // Validasi Pasien jika role = pasien
            'no_rm' => ['required_if:role,pasien', 'nullable', 'string', Rule::unique('pasiens', 'no_rm')->ignore($pasienId)],
            'nik' => 'required_if:role,pasien|nullable|string|max:16',
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update User
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Update atau Buat Record Pasien
            if ($request->role === 'pasien') {
                Pasien::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'no_rm' => $request->no_rm,
                        'nik' => $request->nik,
                        'nama_lengkap' => $request->name,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'tempat_lahir' => $request->tempat_lahir,
                        'tanggal_lahir' => $request->tanggal_lahir,
                        'alamat' => $request->alamat,
                        'no_hp_pasien' => $request->no_hp_pasien,
                        'no_hp_keluarga' => $request->no_hp_keluarga,
                        'jenis_akses' => $request->jenis_akses,
                        'posisi_akses' => $request->posisi_akses,
                        'berat_badan_kering' => $request->berat_badan_kering,
                        'jadwal_hd' => $request->jadwal_hd,
                        'sesi_hd' => $request->sesi_hd,
                        'status_aktif' => $request->status_aktif ?? 'aktif',
                    ]
                );
            }
        });

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user dari database (Destroy).
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::transaction(function () use ($user) {
            // Hapus data pasien jika ada relasi
            if ($user->pasien) {
                $user->pasien()->delete();
            }
            $user->delete();
        });

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
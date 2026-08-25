<?php

namespace App\Http\Requests\Auth;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $loginInput = $this->input('login');

        // Jika input berupa Email (Petugas), password WAJIB diisi
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            return [
                'login'    => ['required', 'string'],
                'password' => ['required', 'string'],
            ];
        }

        // Jika input berupa No. RM (Pasien), password OPSIONAL / TIDAK DIBUTUHKAN
        return [
            'login'    => ['required', 'string'],
            'password' => ['nullable', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = trim($this->input('login'));
        $password   = $this->input('password');

        Log::info('--- LOG IN ATTEMPT ---', ['input' => $loginInput]);

        // 1. OPSI PETUGAS / ADMIN (Email + Password)
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            Log::info('Tipe Input: EMAIL (Petugas)');

            if (Auth::attempt(['email' => $loginInput, 'password' => $password], $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                Log::info('Login Petugas SUCCESS');
                return;
            }
            Log::warning('Login Petugas FAILED');
        } 
        // 2. OPSI PASIEN (No. RM via Tabel Users)
        else {
            Log::info('Tipe Input: NO RM (Pasien)');

            // Cari record akun di tabel users berdasarkan no_rm
            $user = User::where('no_rm', $loginInput)->first();

            if ($user) {
                Log::info('User Pasien ditemukan di DB', ['user_id' => $user->id, 'name' => $user->name]);

                // Authenticate instance User langsung ke Session Guard
                Auth::login($user, $this->boolean('remember'));
                request()->session()->regenerate();

                if (Auth::check()) {
                    Log::info('Auth::login SUCCESS. User Auth ID: ' . Auth::id());
                    RateLimiter::clear($this->throttleKey());
                    return;
                } else {
                    Log::error('Auth::login FAILED - Session gagal menyimpan instance User!');
                }
            } else {
                Log::warning('User Pasien TIDAK ditemukan di tabel users dengan No. RM: ' . $loginInput);
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => 'Email/Password salah atau Nomor RM tidak ditemukan.',
        ]);
    }
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
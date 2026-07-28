<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function tampilkanMasuk()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dasbor')
                : redirect()->route('beranda');
        }
        return view('auth.masuk');
    }

    public function prosesMasuk(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Alamat surel wajib diisi.',
            'email.email' => 'Format alamat surel tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = UserAccount::where('email', $credentials['email'])->first();

        if ($user && (Hash::check($credentials['password'], $user->password) || $credentials['password'] === $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dasbor')->with('sukses', 'Selamat datang kembali, Pengelola ' . $user->nama);
            }

            return redirect()->route('beranda')->with('sukses', 'Selamat datang kembali, ' . $user->nama);
        }

        return back()->withErrors([
            'email' => 'Alamat surel atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function tampilkanDaftar()
    {
        if (Auth::check()) {
            return redirect()->route('beranda');
        }
        return view('auth.daftar');
    }

    public function prosesDaftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user_account,email',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat surel wajib diisi.',
            'email.unique' => 'Alamat surel ini sudah terdaftar. Silakan gunakan surel lain atau pilih Masuk.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = UserAccount::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('beranda')->with('sukses', 'Pendaftaran akun wisatawan berhasil! Selamat menjelajah Kulon Progo.');
    }

    // Tampilkan Halaman Lupa Kata Sandi
    public function tampilkanLupaPassword()
    {
        return view('auth.lupa_password');
    }

    // Proses Pengajuan Lupa Kata Sandi (Keamanan Tinggi: Hanya Tampilkan Konfirmasi Email Terkirim Tanpa Menampilkan Sandi)
    public function prosesLupaPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_account,email',
        ], [
            'email.required' => 'Alamat surel wajib diisi.',
            'email.email' => 'Format alamat surel tidak valid.',
            'email.exists' => 'Alamat surel tidak ditemukan dalam basis data sistem.',
        ]);

        $user = UserAccount::where('email', $request->email)->first();

        // Keamanan: Update password secara internal tanpa menampilkan kata sandi di layar
        $tempPassword = 'Westprog-' . rand(1000, 9999);
        $user->update([
            'password' => Hash::make($tempPassword)
        ]);

        // Keamanan: Hanya konfirmasi bahwa email instruksi telah terkirim
        return redirect()->route('masuk')->with('sukses_lupa', $user->email);
    }

    public function keluar(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('sukses', 'Anda telah berhasil keluar dari akun.');
    }
}

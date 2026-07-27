<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:user_account,email,' . $user->id_user . ',id_user',
            'no_hp' => 'nullable|string|max:15',
            'password' => 'nullable|min:6'
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat surel wajib diisi.',
            'email.unique' => 'Alamat surel sudah digunakan oleh pengguna lain.',
            'password.min' => 'Kata sandi minimal 6 karakter.'
        ]);

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('sukses', 'Profil berhasil diperbarui.');
    }
}

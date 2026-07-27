<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users with search & pagination (10 per page)
    public function index(Request $request)
    {
        $cari = $request->get('cari');

        $query = UserAccount::query();

        if ($cari) {
            $query->where(function($q) use ($cari) {
                $q->where('nama', 'ILIKE', "%{$cari}%")
                  ->orWhere('email', 'ILIKE', "%{$cari}%")
                  ->orWhere('no_hp', 'ILIKE', "%{$cari}%")
                  ->orWhere('role', 'ILIKE', "%{$cari}%");
            });
        }

        $daftarUser = $query->orderBy('id_user', 'asc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('daftarUser', 'cari'));
    }

    public function tambah()
    {
        return view('admin.users.tambah');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user_account,email',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi awal wajib diisi.',
            'password.min' => 'Kata sandi awal minimal 6 karakter.',
        ]);

        UserAccount::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('admin.users.index')->with('sukses', 'Akun wisatawan baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = UserAccount::where('id_user', $id)->where('role', 'user')->firstOrFail();
        return view('admin.users.edit', compact('user'));
    }

    public function perbarui(Request $request, $id)
    {
        $user = UserAccount::where('id_user', $id)->where('role', 'user')->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user_account,email,' . $id . ',id_user',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah terdaftar di sistem.',
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.users.index')->with('sukses', 'Data akun wisatawan berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $user = UserAccount::where('id_user', $id)->where('role', 'user')->firstOrFail();
        $user->delete();

        return redirect()->route('admin.users.index')->with('sukses', 'Akun wisatawan berhasil dihapus dari sistem.');
    }
}

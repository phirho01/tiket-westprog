@extends('layouts.admin')

@section('judul', 'Ubah Akun Pengguna')

@section('konten')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-500 hover:text-primary inline-flex items-center gap-1 transition">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    <!-- Form Card (Clean Pure White Enterprise Theme) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-10 space-y-8">
        <div class="border-b border-slate-100 pb-5">
            <span class="text-xs font-bold text-primary uppercase tracking-widest block mb-1">Form Pengeditan Akun</span>
            <h1 class="font-heading text-2xl font-bold text-slate-900">Ubah Akun Wisatawan</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui data diri untuk {{ $user->nama }} (#USR-{{ $user->id_user }}).</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <span>{{ $error }}</span>
                    </p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.users.perbarui', $user->id_user) }}" method="POST" class="space-y-6" onsubmit="konfirmasiSimpanForm(event, 'Apakah Anda yakin ingin menyimpan perubahan akun {{ addslashes($user->nama) }} ini?')">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Lengkap Wisatawan <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Surel (Email) <span class="text-rose-600">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div>
                <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nomor Telepon / WhatsApp
                </label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Catatan Proteksi Keamanan Kata Sandi -->
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl text-xs text-amber-800 space-y-1">
                <p class="font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">lock</span>
                    <span>Proteksi Privasi Kata Sandi:</span>
                </p>
                <p class="pl-5 leading-relaxed">
                    Pengelola (Admin) tidak dapat mengubah kata sandi pengguna secara langsung. Pengguna hanya dapat memperbarui kata sandi secara mandiri melalui menu <strong>Profil Saya</strong> atau fitur <strong>Lupa Kata Sandi</strong>.
                </p>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    <span>Simpan Perubahan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

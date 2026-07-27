@extends('layouts.admin')

@section('judul', 'Tambah Pengguna Baru')

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
            <span class="text-xs font-bold text-primary uppercase tracking-widest block mb-1">Form Pengguna Wisatawan</span>
            <h1 class="font-heading text-2xl font-bold text-slate-900">Tambah Akun Wisatawan Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Lengkapi formulir di bawah untuk mendaftarkan akun wisatawan baru ke sistem.</p>
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

        <form action="{{ route('admin.users.simpan') }}" method="POST" class="space-y-6" onsubmit="konfirmasiSimpanForm(event, 'Apakah Anda yakin ingin menyimpan akun wisatawan baru ini?')">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nama Lengkap Wisatawan <span class="text-rose-600">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Surel (Email) <span class="text-rose-600">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    placeholder="nama@domain.com"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div>
                <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nomor Telepon / WhatsApp
                </label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                    placeholder="081234567890"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Kata Sandi -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kata Sandi <span class="text-rose-600">*</span>
                </label>
                <input type="password" name="password" id="password" required
                    placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    <span>Simpan Akun Wisatawan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

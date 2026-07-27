@extends('layouts.app')

@section('judul', 'Pengaturan Profil Saya - Westprog Ticket')

@section('konten')
<div class="max-w-xl mx-auto py-12 px-4">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('beranda') }}" class="text-xs font-semibold text-on-surface-variant hover:text-primary inline-flex items-center gap-1 transition">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Beranda Katalog</span>
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-lg p-8 space-y-6">
        <div class="border-b border-outline-variant/60 pb-4">
            <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Pengaturan Profil Saya</h1>
            <p class="font-body-md text-xs text-on-surface-variant mt-1">Perbarui informasi data pribadi dan kata sandi akun Anda.</p>
        </div>

        @if(session('success') || session('sukses'))
            <div class="p-4 bg-primary-container text-on-primary-container border border-primary/20 rounded-xl text-xs font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span>{{ session('success') ?? session('sukses') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-error-container text-on-error-container border border-error/20 p-4 rounded-xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5" onsubmit="handleFormSubmit(this)">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required 
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm bg-surface-container-low focus:bg-surface transition">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Alamat Surel (Email) <span class="text-error">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm bg-surface-container-low focus:bg-surface transition">
            </div>

            <div>
                <label for="no_hp" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" 
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm bg-surface-container-low focus:bg-surface transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Kata Sandi Baru (Opsional)</label>
                <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah sandi" 
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm bg-surface-container-low focus:bg-surface transition">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-outline-variant/60">
                <a href="{{ route('beranda') }}" class="px-5 py-2.5 rounded-xl text-on-surface-variant bg-surface-container-high hover:bg-surface-container-highest text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md">
                    Simpan Perbarui Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

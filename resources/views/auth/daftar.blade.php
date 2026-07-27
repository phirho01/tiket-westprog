@extends('layouts.app')

@section('judul', 'Daftar Akun Baru - Westprog Ticket')

@section('konten')
<div class="max-w-md mx-auto py-16 px-4">
    <div class="bg-surface-container-lowest rounded-3xl p-8 border border-outline-variant shadow-xl">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo_westprog.png') }}" 
                 alt="Logo Resmi Westprog Ticket" 
                 class="h-16 w-auto mx-auto mb-4 object-contain">

            <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Daftar Akun Baru</h1>
            <p class="font-body-md text-xs text-on-surface-variant mt-1">Lengkapi data diri Anda untuk membuat akun wisatawan</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-error-container text-on-error-container border border-error/20 px-4 py-3 rounded-2xl text-xs space-y-1.5">
                <p class="font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">warning</span>
                    <span>Kendala Pendaftaran:</span>
                </p>
                @foreach($errors->all() as $error)
                    <p class="pl-5 text-on-error-container">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('daftar.proses') }}" method="POST" class="space-y-5" onsubmit="handleFormSubmit(this)">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-xs font-bold text-on-surface mb-1.5 uppercase tracking-wider">
                    Nama Lengkap <span class="text-error">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required autofocus
                    placeholder="Nama Lengkap Anda"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary text-sm transition outline-none bg-surface-container-low focus:bg-surface text-on-surface">
            </div>

            <!-- Alamat Surel -->
            <div>
                <label for="email" class="block text-xs font-bold text-on-surface mb-1.5 uppercase tracking-wider">
                    Alamat Surel <span class="text-error">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    placeholder="nama@domain.com"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary text-sm transition outline-none bg-surface-container-low focus:bg-surface text-on-surface">
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="no_hp" class="block text-xs font-bold text-on-surface mb-1.5 uppercase tracking-wider">
                    Nomor Telepon / WhatsApp
                </label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                    placeholder="081234567890"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary text-sm transition outline-none bg-surface-container-low focus:bg-surface text-on-surface">
            </div>

            <!-- Kata Sandi -->
            <div>
                <label for="password" class="block text-xs font-bold text-on-surface mb-1.5 uppercase tracking-wider">
                    Kata Sandi <span class="text-error">*</span>
                </label>
                <input type="password" name="password" id="password" required
                    placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary text-sm transition outline-none bg-surface-container-low focus:bg-surface text-on-surface">
            </div>

            <!-- Tombol Submit Utama -->
            <button type="submit"
                class="w-full bg-primary hover:bg-primary-container text-on-primary font-bold py-3.5 rounded-xl transition duration-200 shadow-md text-sm cursor-pointer flex items-center justify-center gap-2">
                <span>Daftar Akun</span>
                <span class="material-symbols-outlined text-sm">person_add</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-outline-variant/60 text-center text-xs text-on-surface-variant">
            Sudah memiliki akun?
            <a href="{{ route('masuk') }}" class="text-primary font-bold hover:underline ml-1">Masuk</a>
        </div>
    </div>
</div>
@endsection

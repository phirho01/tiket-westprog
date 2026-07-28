@extends('layouts.app')

@section('judul', 'Masuk Akun - Westprog Ticket')

@section('konten')
<div class="relative min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 overflow-hidden">
    <!-- Scenery Background Layer with Soft Opacity -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="w-full h-full bg-cover bg-center opacity-30 filter blur-[1px] transform scale-105" 
             style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA2lQPWyRzonCSC_qaBvVoorIevcyCp-bTIpcSUmv39_vsaKqfTA-zrnm4lrcJHHygEPuyHCSETABrhzPNoz6SYWDOqeBG5SRukw9dIzbq7C7SscEIqqnujoGZIjllcDBtoiGb65VDgbJpWXIV1avwccwU8y_v-ZVtti1068pWTfxtHhwR-VVEpsYmyVKrS3AK9qCUJbg7Rli5vly8iz2zjSW9W_qJoMq3Cu84m1TONy-Z6qrxgiM6RUHrGEX7RMu1yhc79VkEb29Sy')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-950/40 via-surface-container-lowest/60 to-emerald-900/30 backdrop-blur-[2px]"></div>
    </div>

    <!-- Card Form Masuk -->
    <div class="relative z-10 w-full max-w-md bg-surface-container-lowest/95 rounded-3xl p-8 border border-outline-variant/80 shadow-2xl space-y-6 backdrop-blur-md">
        <div class="text-center">
            <img src="{{ asset('images/logo_westprog.png') }}" 
                 alt="Logo Resmi Westprog Ticket" 
                 class="h-16 w-auto mx-auto mb-4 object-contain">

            <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Masuk Akun</h1>
            <p class="font-body-md text-xs text-on-surface-variant mt-1">Silakan masukkan alamat surel dan kata sandi Anda</p>
        </div>

        <!-- Banner Notifikasi Konfirmasi Email Terkirim (Keamanan Tinggi: Tanpa Menampilkan Kata Sandi di Layar) -->
        @if(session('sukses_lupa'))
            <div class="bg-primary-container text-on-primary-container border border-primary/30 p-5 rounded-2xl text-xs space-y-2 shadow-md">
                <div class="flex items-center gap-2 font-bold text-sm">
                    <span class="material-symbols-outlined text-base">mark_email_read</span>
                    <span>Instruksi Pemulihan Dikirim!</span>
                </div>
                <p class="leading-relaxed">
                    Tautan & instruksi pemulihan kata sandi telah berhasil dikirimkan ke surel <strong class="underline font-bold">{{ session('sukses_lupa') }}</strong>.
                </p>
                <p class="text-[11px] opacity-90 leading-relaxed pt-1 border-t border-primary-fixed/20">
                    Silakan periksa kotak masuk (*inbox*) atau folder *spam* email Anda untuk mengakses kata sandi pemulihan.
                </p>
            </div>
        @endif

        <!-- Notifikasi Error Validasi Server -->
        @if($errors->any())
            <div class="bg-error-container text-on-error-container border border-error/20 px-4 py-3 rounded-2xl text-xs space-y-1.5">
                <p class="font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">warning</span>
                    <span>Gagal Masuk Ke Akun:</span>
                </p>
                @foreach($errors->all() as $error)
                    <p class="pl-5 text-on-error-container">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('masuk.proses') }}" method="POST" class="space-y-5" onsubmit="handleFormSubmit(this)">
            @csrf

            <!-- Field Alamat Surel -->
            <div>
                <label for="email" class="block text-xs font-bold text-on-surface mb-1.5 uppercase tracking-wider">
                    Alamat Surel
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    placeholder="nama@domain.com"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary text-sm transition outline-none bg-surface-container-low focus:bg-surface text-on-surface">
            </div>

            <!-- Field Kata Sandi + Tautan Lupa Kata Sandi -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold text-on-surface uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <a href="{{ route('lupa_password') }}" class="text-xs text-primary font-bold hover:underline">
                        Lupa Kata Sandi?
                    </a>
                </div>
                <input type="password" name="password" id="password" required
                    placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary text-sm transition outline-none bg-surface-container-low focus:bg-surface text-on-surface">
            </div>

            <!-- Tombol Submit Utama -->
            <button type="submit"
                class="w-full bg-primary hover:bg-primary-container text-on-primary font-bold py-3.5 rounded-xl transition duration-200 shadow-md text-sm cursor-pointer flex items-center justify-center gap-2">
                <span>Masuk</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </form>

        <div class="pt-6 border-t border-outline-variant/60 text-center text-xs text-on-surface-variant">
            Belum memiliki akun?
            <a href="{{ route('daftar') }}" class="text-primary font-bold hover:underline ml-1">Daftar Akun Baru</a>
        </div>
    </div>
</div>
@endsection

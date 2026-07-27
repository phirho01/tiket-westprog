@extends('layouts.app')

@section('judul', $wisata->nama_wisata . ' - Informasi Detail & Ulasan')

@section('konten')
<div class="max-w-container-max mx-auto px-margin-desktop py-10 space-y-10">
    
    <!-- Breadcrumb & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('beranda') }}" class="text-xs font-semibold text-on-surface-variant hover:text-primary inline-flex items-center gap-1.5 transition">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Beranda Katalog</span>
        </a>
    </div>

    <!-- Main Detail Card Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Gambar Sampul & Deskripsi Lengkap -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-3xl overflow-hidden shadow-xs">
                <!-- Cover Image -->
                <div class="h-80 sm:h-96 w-full bg-slate-900 relative overflow-hidden">
                    @if($wisata->gambar && str_contains($wisata->gambar, 'http'))
                        <img src="{{ $wisata->gambar }}" alt="{{ $wisata->nama_wisata }}" class="w-full h-full object-cover">
                    @elseif($wisata->gambar && file_exists(public_path('uploads/wisata/' . $wisata->gambar)))
                        <img src="{{ asset('uploads/wisata/' . $wisata->gambar) }}" alt="{{ $wisata->nama_wisata }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primary/20 text-primary">
                            <span class="material-symbols-outlined text-6xl">landscape</span>
                        </div>
                    @endif
                </div>

                <!-- Detail Text Header -->
                <div class="p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-outline-variant/60 pb-6">
                        <div>
                            <span class="text-xs font-bold text-primary uppercase tracking-widest block mb-1">Destinasi Wisata Kulon Progo</span>
                            <h1 class="font-headline-lg text-3xl sm:text-4xl font-bold text-on-surface">{{ $wisata->nama_wisata }}</h1>
                            <div class="flex flex-wrap items-center gap-4 mt-2 text-xs">
                                <span class="text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                    <span>{{ $wisata->lokasi }}</span>
                                </span>
                                <span class="text-emerald-800 font-bold flex items-center gap-1 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                                    <span class="material-symbols-outlined text-sm text-emerald-600">schedule</span>
                                    <span>Jam Operasional: {{ date('H:i', strtotime($wisata->jam_buka ?? '07:00')) }} - {{ date('H:i', strtotime($wisata->jam_tutup ?? '17:00')) }} WIB</span>
                                </span>
                            </div>
                        </div>
                        <div class="text-left sm:text-right shrink-0">
                            <span class="block text-xs text-on-surface-variant font-medium">Harga Tiket Masuk</span>
                            <span class="font-headline-lg text-2xl font-bold text-primary">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Deskripsi Lengkap -->
                    <div>
                        <h2 class="font-headline-sm text-lg font-bold text-on-surface mb-3">Gambaran Informasi Wisata</h2>
                        <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">
                            {{ $wisata->deskripsi ?? 'Destinasi wisata alam ini menawarkan panorama pemandangan menakjubkan di kawasan pegunungan Menoreh Kulon Progo. Sangat cocok untuk tujuan rekreasi keluarga, fotografi alam, dan udara segar.' }}
                        </p>
                    </div>

                    <!-- Peta Google Maps & Tombol Pesan -->
                    <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-outline-variant/60">
                        @if($wisata->link_gmaps)
                            <a href="{{ $wisata->link_gmaps }}" target="_blank" rel="noopener noreferrer" 
                               class="px-5 py-3 rounded-xl bg-surface-container-high text-on-surface-variant font-label-md text-xs font-semibold hover:bg-surface-container-highest transition-colors inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-primary">map</span>
                                <span>Buka Lokasi di Google Maps</span>
                            </a>
                        @endif

                        <a href="{{ route('user.pesan', $wisata->id_wisata) }}" class="px-8 py-3.5 bg-primary text-on-primary rounded-xl font-label-md text-xs font-bold hover:bg-primary-container transition-colors shadow-md inline-flex items-center gap-2 ml-auto">
                            <span>Pesan Tiket Sekarang</span>
                            <span class="material-symbols-outlined text-sm">confirmation_number</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ulasan & Rating Pengunjung Section -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-3xl p-8 space-y-8 shadow-xs">
                <div class="flex items-center justify-between border-b border-outline-variant/60 pb-5">
                    <div>
                        <h2 class="font-headline-sm text-xl font-bold text-on-surface">Penilaian & Ulasan Pengunjung</h2>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">Ulasan jujur dari wisatawan yang telah berkunjung</p>
                    </div>

                    <!-- Summary Score Badge -->
                    @if($totalUlasan > 0)
                        <div class="flex items-center gap-3 bg-primary/10 px-4 py-2 rounded-2xl border border-primary/20">
                            <span class="material-symbols-outlined text-amber-500 text-2xl" style="font-variation-settings: 'FILL' 1;">star</span>
                            <div>
                                <span class="font-headline-sm text-lg font-bold text-primary block leading-none">{{ $rataRating }} / 5.0</span>
                                <span class="text-[10px] text-on-surface-variant font-semibold block mt-0.5">{{ $totalUlasan }} Ulasan</span>
                            </div>
                        </div>
                    @else
                        <div class="px-4 py-2 rounded-2xl bg-surface-container-low border border-outline-variant text-xs font-semibold text-on-surface-variant italic">
                            Belum Ada Ulasan
                        </div>
                    @endif
                </div>

                <!-- Form Kirim Ulasan Baru -->
                @auth
                    <div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant/80 space-y-4">
                        <h3 class="font-headline-sm text-sm font-bold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-base">rate_review</span>
                            <span>Tulis Ulasan & Penilaian Anda</span>
                        </h3>

                        <form action="{{ route('ulasan.store') }}" method="POST" class="space-y-4" onsubmit="handleFormSubmit(this)">
                            @csrf
                            <input type="hidden" name="id_wisata" value="{{ $wisata->id_wisata }}">

                            <div>
                                <label for="rating" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                                    Beri Rating Bintang <span class="text-error">*</span>
                                </label>
                                <select name="rating" id="rating" required class="w-full sm:w-72 px-4 py-3 rounded-xl border border-outline-variant text-xs font-bold focus:ring-2 focus:ring-primary outline-none bg-surface text-on-surface">
                                    <option value="5">⭐⭐⭐⭐⭐ 5 - Sangat Puas</option>
                                    <option value="4">⭐⭐⭐⭐ 4 - Bagus</option>
                                    <option value="3">⭐⭐⭐ 3 - Cukup</option>
                                    <option value="2">⭐⭐ 2 - Kurang</option>
                                    <option value="1">⭐ 1 - Buruk</option>
                                </select>
                            </div>

                            <div>
                                <label for="komentar" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1">
                                    Komentar / Pengalaman Wisata <span class="text-error">*</span>
                                </label>
                                <textarea name="komentar" id="komentar" rows="3" placeholder="Bagikan kesan dan pengalaman Anda berkunjung ke lokasi ini..." required class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary text-xs outline-none bg-surface text-on-surface"></textarea>
                            </div>

                            <button type="submit" class="px-5 py-2.5 bg-primary text-on-primary text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-xs">
                                Kirim Ulasan Saya
                            </button>
                        </form>
                    </div>
                @endauth

                <!-- Daftar Ulasan Masuk -->
                <div class="space-y-4">
                    @forelse($wisata->ulasan as $u)
                        <div class="p-5 rounded-2xl bg-surface-container-low/60 border border-outline-variant/60 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($u->user->nama ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-xs text-on-surface block">{{ $u->user->nama ?? 'Wisatawan' }}</span>
                                        <span class="text-[10px] text-on-surface-variant font-medium block">{{ $u->tanggal_ulasan }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 text-amber-500 bg-amber-50 px-2.5 py-1 rounded-lg text-xs font-bold border border-amber-200">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span>{{ $u->rating }} / 5</span>
                                </div>
                            </div>

                            <p class="font-body-sm text-xs text-on-surface-variant pl-10 leading-relaxed">
                                {{ $u->komentar ?? 'Pengunjung memberikan rating tanpa komentar tertulis.' }}
                            </p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-on-surface-variant text-xs italic bg-surface-container-low rounded-2xl">
                            Belum ada ulasan untuk destinasi wisata ini. Jadilah yang pertama memberikan ulasan!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Card Ringkasan Pemesanan Cepat -->
        <div class="space-y-6">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-3xl p-6 shadow-xs space-y-6 sticky top-24">
                <div class="border-b border-outline-variant/60 pb-4">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-primary block mb-1">Tiket Masuk Resmi</span>
                    <h3 class="font-headline-sm text-xl font-bold text-on-surface">{{ $wisata->nama_wisata }}</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-2 border-b border-outline-variant/40">
                        <span class="text-on-surface-variant">Harga per Tiket</span>
                        <span class="font-bold text-on-surface">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-outline-variant/40">
                        <span class="text-on-surface-variant">Jam Operasional</span>
                        <span class="font-bold text-emerald-800">{{ date('H:i', strtotime($wisata->jam_buka ?? '07:00')) }} - {{ date('H:i', strtotime($wisata->jam_tutup ?? '17:00')) }} WIB</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-outline-variant/40">
                        <span class="text-on-surface-variant">Penilaian Pengunjung</span>
                        <span class="font-bold text-amber-600">
                            @if($totalUlasan > 0)
                                ⭐ {{ $rataRating }} / 5.0
                            @else
                                <span class="text-slate-400 font-normal italic">Belum dinilai</span>
                            @endif
                        </span>
                    </div>
                </div>

                <a href="{{ route('user.pesan', $wisata->id_wisata) }}" class="w-full py-3.5 bg-primary text-on-primary font-bold rounded-xl text-xs hover:bg-primary-container transition shadow-md flex items-center justify-center gap-2">
                    <span>Pesan Tiket Sekarang</span>
                    <span class="material-symbols-outlined text-sm">confirmation_number</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('judul', 'Westprog Ticket - Jelajah Keindahan Kulon Progo')

@section('konten')
    <style>
        /* Animation Keyframes for Falling & Swaying Leaves */
        @keyframes leafFlyRight {
            0% {
                transform: translate3d(-10vw, -10vh, 0) rotate(0deg) scale(0.7);
                opacity: 0;
            }
            15% {
                opacity: 0.85;
            }
            85% {
                opacity: 0.85;
            }
            100% {
                transform: translate3d(105vw, 85vh, 0) rotate(720deg) scale(1.2);
                opacity: 0;
            }
        }

        @keyframes leafFlyLeft {
            0% {
                transform: translate3d(105vw, -5vh, 0) rotate(0deg) scale(1.1);
                opacity: 0;
            }
            15% {
                opacity: 0.9;
            }
            85% {
                opacity: 0.9;
            }
            100% {
                transform: translate3d(-10vw, 90vh, 0) rotate(-600deg) scale(0.6);
                opacity: 0;
            }
        }

        @keyframes leafGentleSway {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(8deg); }
        }

        /* Floating Vector Adventurer Mascot Animation */
        @keyframes mascotFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
            50% { transform: translateY(-18px) rotate(2deg) scale(1.03); }
        }

        .anim-leaf-1 { animation: leafFlyRight 14s linear infinite; }
        .anim-leaf-2 { animation: leafFlyRight 19s linear infinite 3s; }
        .anim-leaf-3 { animation: leafFlyLeft 16s linear infinite 1.5s; }
        .anim-leaf-4 { animation: leafFlyLeft 22s linear infinite 6s; }
        .anim-leaf-5 { animation: leafFlyRight 25s linear infinite 9s; }
        .anim-leaf-6 { animation: leafFlyLeft 18s linear infinite 12s; }
        .anim-leaf-sway { animation: leafGentleSway 3.5s ease-in-out infinite; }
        .anim-mascot { animation: mascotFloat 4.5s ease-in-out infinite; }
    </style>

    <!-- Hero Section with Falling Floating Leaves Animation -->
    <section class="relative min-h-[580px] flex items-center overflow-hidden bg-primary">
        
        <!-- Animated Flying Leaves Layer (Pointer Events None) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
            <!-- Leaf 1 -->
            <div class="absolute top-[2%] left-[-5%] anim-leaf-1 text-emerald-300/60">
                <svg class="w-8 h-8 filter drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,8C8,10 5,16 5,22C11.5,22 17.5,18 19,10C19.5,7 19,4 17,8M7.5,19C8.3,16 11,13.8 14.5,12.5C12.5,15.5 10,17.8 7.5,19Z"/>
                </svg>
            </div>
            <!-- Leaf 2 -->
            <div class="absolute top-[10%] left-[-8%] anim-leaf-2 text-emerald-200/70">
                <svg class="w-11 h-11 filter drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,8C8,10 5,16 5,22C11.5,22 17.5,18 19,10C19.5,7 19,4 17,8M7.5,19C8.3,16 11,13.8 14.5,12.5C12.5,15.5 10,17.8 7.5,19Z"/>
                </svg>
            </div>
            <!-- Leaf 3 -->
            <div class="absolute top-[5%] right-[-5%] anim-leaf-3 text-emerald-300/65">
                <svg class="w-9 h-9 filter drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,8C8,10 5,16 5,22C11.5,22 17.5,18 19,10C19.5,7 19,4 17,8M7.5,19C8.3,16 11,13.8 14.5,12.5C12.5,15.5 10,17.8 7.5,19Z"/>
                </svg>
            </div>
            <!-- Leaf 4 -->
            <div class="absolute top-[1%] right-[-10%] anim-leaf-4 text-emerald-100/75">
                <svg class="w-12 h-12 filter drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,8C8,10 5,16 5,22C11.5,22 17.5,18 19,10C19.5,7 19,4 17,8M7.5,19C8.3,16 11,13.8 14.5,12.5C12.5,15.5 10,17.8 7.5,19Z"/>
                </svg>
            </div>
            <!-- Leaf 5 -->
            <div class="absolute top-[20%] left-[-12%] anim-leaf-5 text-emerald-400/50">
                <svg class="w-7 h-7 filter drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,8C8,10 5,16 5,22C11.5,22 17.5,18 19,10C19.5,7 19,4 17,8M7.5,19C8.3,16 11,13.8 14.5,12.5C12.5,15.5 10,17.8 7.5,19Z"/>
                </svg>
            </div>
            <!-- Leaf 6 -->
            <div class="absolute top-[15%] right-[-8%] anim-leaf-6 text-emerald-200/60">
                <svg class="w-10 h-10 filter drop-shadow-md" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17,8C8,10 5,16 5,22C11.5,22 17.5,18 19,10C19.5,7 19,4 17,8M7.5,19C8.3,16 11,13.8 14.5,12.5C12.5,15.5 10,17.8 7.5,19Z"/>
                </svg>
            </div>
        </div>

        <div class="absolute inset-0 opacity-40">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA2lQPWyRzonCSC_qaBvVoorIevcyCp-bTIpcSUmv39_vsaKqfTA-zrnm4lrcJHHygEPuyHCSETABrhzPNoz6SYWDOqeBG5SRukw9dIzbq7C7SscEIqqnujoGZIjllcDBtoiGb65VDgbJpWXIV1avwccwU8y_v-ZVtti1068pWTfxtHhwR-VVEpsYmyVKrS3AK9qCUJbg7Rli5vly8iz2zjSW9W_qJoMq3Cu84m1TONy-Z6qrxgiM6RUHrGEX7RMu1yhc79VkEb29Sy')"></div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary/85 to-transparent"></div>
        
        <div class="relative z-20 w-full max-w-container-max mx-auto px-margin-desktop py-stack-lg">
            <div class="max-w-2xl text-on-primary">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-950 mb-4 uppercase tracking-wider shadow-sm anim-leaf-sway">
                    <span class="material-symbols-outlined text-sm text-emerald-800">verified</span>
                    Pariwisata Resmi Kabupaten Kulon Progo
                </span>
                <h1 class="font-display-lg text-4xl sm:text-5xl lg:text-6xl font-bold mb-4 leading-tight text-white drop-shadow-xs">
                    Jelajah Keindahan Kulon Progo
                </h1>
                <p class="font-body-lg text-lg text-emerald-100/90 mb-8 leading-relaxed">
                    Nikmati pesona alam pegunungan Menoreh yang asri, keindahan waduk yang tenang, hingga spot foto di ketinggian yang memukau. Pesan tiket Anda sekarang untuk pengalaman wisata yang tak terlupakan.
                </p>

                <!-- Unified Minimalist Floating Search Bar (Enter Keyboard & GUI Icon Clickable) -->
                <form action="{{ route('beranda') }}" method="GET" class="max-w-xl">
                    <div class="bg-white/95 backdrop-blur-md rounded-2xl p-1.5 shadow-2xl border border-white/30 flex items-center gap-2 transition-all hover:shadow-emerald-900/20">
                        <div class="relative flex-grow flex items-center pl-2">
                            <button type="submit" class="p-2 text-slate-400 hover:text-primary transition cursor-pointer flex items-center justify-center" title="Klik atau tekan Enter untuk mencari">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </button>
                            <input type="text" name="cari" value="{{ request('cari') }}" 
                                   placeholder="Cari destinasi wisata atau lokasi (mis. Glagah, Sermo)..." 
                                   class="w-full py-2.5 bg-transparent text-slate-900 placeholder:text-slate-400 font-medium text-xs border-0 outline-none focus:ring-0">
                            @if(request('cari'))
                                <a href="{{ route('beranda') }}" class="p-1 text-slate-400 hover:text-rose-600 transition flex items-center justify-center mr-1" title="Hapus pencarian">
                                    <span class="material-symbols-outlined text-base">close</span>
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="px-6 py-3 bg-primary hover:bg-emerald-700 text-white rounded-xl font-label-md text-xs font-bold transition-colors inline-flex items-center gap-2 shadow-md shrink-0 cursor-pointer" title="Klik atau tekan Enter untuk mencari">
                            <span class="material-symbols-outlined text-base">search</span>
                            <span>Cari Tiket</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Stats / Feature Bar -->
    <div class="relative z-20 -mt-10 max-w-container-max mx-auto px-margin-desktop">
        <div class="bg-white shadow-lg border border-slate-200 rounded-2xl grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100 overflow-hidden">
            <div class="p-5 flex items-center gap-4">
                <div class="bg-primary/10 p-3 rounded-full shrink-0">
                    <span class="material-symbols-outlined text-primary">qr_code_2</span>
                </div>
                <div>
                    <p class="font-bold text-sm text-slate-900">E-Tiket Digital</p>
                    <p class="text-xs text-slate-500">Aktif setelah verifikasi pembayaran pengelola</p>
                </div>
            </div>
            <div class="p-5 flex items-center gap-4">
                <div class="bg-primary/10 p-3 rounded-full shrink-0">
                    <span class="material-symbols-outlined text-primary">verified</span>
                </div>
                <div>
                    <p class="font-bold text-sm text-slate-900">Resmi & Aman</p>
                    <p class="text-xs text-slate-500">Dikelola oleh Dinas Pariwisata Kulon Progo</p>
                </div>
            </div>
            <div class="p-5 flex items-center gap-4">
                <div class="bg-primary/10 p-3 rounded-full shrink-0">
                    <span class="material-symbols-outlined text-primary">support_agent</span>
                </div>
                <div>
                    <p class="font-bold text-sm text-slate-900">Bantuan Informasi</p>
                    <p class="text-xs text-slate-500">Pusat layanan informasi pengunjung</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tourism Grid Section -->
    <section id="katalog" class="max-w-container-max mx-auto px-margin-desktop py-16">
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4 border-b border-slate-200 pb-5">
            <div>
                <h2 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 mb-1">Destinasi Populer</h2>
                <p class="text-xs text-slate-600">
                    @if(request('cari'))
                        Menampilkan hasil pencarian untuk kata kunci "<strong class="text-primary">{{ request('cari') }}</strong>" ({{ $daftarWisata->count() }} Wisata ditemukan).
                        <a href="{{ route('beranda') }}" class="text-xs text-rose-600 font-bold hover:underline ml-2">Reset Pencarian</a>
                    @else
                        Pilih destinasi favorit Anda di Kulon Progo dan amankan kuota kunjungan Anda hari ini.
                    @endif
                </p>
            </div>
        </div>

        @if($daftarWisata->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 max-w-lg mx-auto shadow-xs">
                <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">search_off</span>
                <h3 class="font-heading text-lg font-bold text-slate-900">Destinasi Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1">Wisata dengan kata kunci "{{ request('cari') }}" tidak ditemukan. Silakan gunakan kata kunci lain.</p>
                <a href="{{ route('beranda') }}" class="inline-block mt-4 px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold shadow-md">Lihat Semua Wisata</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($daftarWisata as $w)
                <div class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                    <!-- Thumbnail Container -->
                    <div class="relative aspect-video overflow-hidden bg-slate-900">
                        @if($w->gambar && str_contains($w->gambar, 'http'))
                            <img src="{{ $w->gambar }}" alt="{{ $w->nama_wisata }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @elseif($w->gambar && file_exists(public_path('uploads/wisata/' . $w->gambar)))
                            <img src="{{ asset('uploads/wisata/' . $w->gambar) }}" alt="{{ $w->nama_wisata }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primary/20 text-primary">
                                <span class="material-symbols-outlined text-4xl">landscape</span>
                            </div>
                        @endif
                        
                        <!-- Rating Badge Indicator -->
                        <div class="absolute bottom-3 left-3 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-bold shadow-sm">
                            @if($w->total_ulasan > 0)
                                <span class="text-amber-600 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span>{{ $w->rata_rating }} ({{ $w->total_ulasan }} Ulasan)</span>
                                </span>
                            @else
                                <span class="text-slate-500 font-medium italic">Belum ada ulasan</span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex flex-col flex-grow justify-between space-y-4">
                        <div>
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <h3 class="font-heading text-lg font-bold text-slate-900 group-hover:text-primary transition-colors">
                                    {{ $w->nama_wisata }}
                                </h3>
                                <p class="font-heading text-lg font-extrabold text-primary shrink-0">
                                    Rp {{ number_format($w->harga_tiket, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            @if($w->deskripsi)
                                <p class="text-xs text-slate-600 line-clamp-2 mb-3 leading-relaxed">
                                    {{ $w->deskripsi }}
                                </p>
                            @endif

                            <div class="space-y-1 text-xs">
                                <div class="flex items-center gap-1 text-slate-600 font-medium">
                                    <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                    <span>{{ $w->lokasi }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-emerald-800 font-semibold">
                                    <span class="material-symbols-outlined text-sm text-emerald-600">schedule</span>
                                    <span>Jam Buka: {{ date('H:i', strtotime($w->jam_buka ?? '07:00')) }} - {{ date('H:i', strtotime($w->jam_tutup ?? '17:00')) }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-3">
                            <a href="{{ route('wisata.detail', $w->id_wisata) }}" 
                               class="flex items-center justify-center gap-1 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors">
                                <span class="material-symbols-outlined text-sm text-primary">visibility</span>
                                <span>Detail & Ulasan</span>
                            </a>

                            <a href="{{ route('user.pesan', $w->id_wisata) }}" class="flex items-center justify-center gap-1 py-2.5 rounded-xl bg-primary text-white font-bold text-xs hover:bg-primary-container transition-colors shadow-md">
                                <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                <span>Pesan</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- CTA Section with Pure Vector Character Only (No Card Frame) -->
    <section class="max-w-container-max mx-auto px-margin-desktop mb-16">
        <div class="bg-gradient-to-r from-emerald-950 via-primary to-emerald-900 text-white rounded-[2.5rem] p-8 sm:p-12 md:p-14 relative overflow-hidden shadow-2xl border border-emerald-800/40">
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-96 h-96 bg-emerald-400/20 blur-[100px] rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Text Content -->
                <div class="lg:col-span-7 space-y-4 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-extrabold bg-emerald-400/20 text-emerald-200 border border-emerald-400/30 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-sm">flight_takeoff</span>
                        Mari Liburan Ke Kulon Progo!
                    </span>
                    <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white drop-shadow-xs leading-tight">
                        Siap untuk Berpetualang?
                    </h2>
                    <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed max-w-xl">
                        Ribuan wisatawan sudah menikmati keindahan alam Kulon Progo. Daftarkan diri Anda sekarang untuk kemudahan pemesanan tiket di mana saja, kapan saja.
                    </p>
                    <div class="pt-3 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('daftar') }}" class="px-8 py-4 bg-white hover:bg-emerald-50 text-primary rounded-2xl font-bold text-xs sm:text-sm shadow-xl transition-all hover:scale-105 inline-flex items-center justify-center gap-2.5">
                            <span class="material-symbols-outlined text-base">explore</span>
                            <span>Mulai Jelajah Baru Sekarang</span>
                        </a>
                    </div>
                </div>

                <!-- Right Vector Mascot Container (Pure Vector Character Cutout floating gracefully) -->
                <div class="lg:col-span-5 flex flex-col items-center justify-center relative">
                    <!-- Floating Speech Bubble with Updated Text -->
                    <div class="bg-white/95 backdrop-blur-md text-emerald-950 px-4 py-2 rounded-2xl rounded-bl-none shadow-2xl border border-white/80 text-xs font-extrabold flex items-center gap-2 mb-2 anim-leaf-sway z-20">
                        <span class="material-symbols-outlined text-base text-emerald-600" style="font-variation-settings: 'FILL' 1;">waving_hand</span>
                        <span>Yuk Liburan Bersama Westprog 🌿</span>
                    </div>

                    <!-- Pure Vector Character Floating (No Frame / Card) -->
                    <div class="relative max-w-[200px] sm:max-w-[240px] lg:max-w-[260px] anim-mascot">
                        <img src="{{ asset('images/adventurer.png') }}" 
                             alt="Petualang Wisata Kulon Progo" 
                             class="w-full h-auto object-contain filter drop-shadow-2xl rounded-2xl transform hover:scale-105 transition-transform duration-300">
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

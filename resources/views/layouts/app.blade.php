<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Westprog Ticket - E-Tiket Pariwisata Kulon Progo')</title>
    
    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Alpine.js & Tailwind CSS via CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0b6c47',
                        'primary-container': '#005234',
                        'on-primary': '#ffffff',
                        'primary-fixed': '#a2f5c7',
                        'surface': '#f8fafc',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f1f5f9',
                        'surface-container-high': '#e2e8f0',
                        'on-surface': '#0f172a',
                        'on-surface-variant': '#475569',
                        'outline-variant': '#cbd5e1',
                        'error': '#e11d48',
                        'error-container': '#ffe4e6',
                        'on-error-container': '#9f1239',
                    },
                    fontFamily: {
                        'sans': ['Plus Jakarta Sans', 'sans-serif'],
                        'display': ['Outfit', 'sans-serif'],
                        'heading': ['Outfit', 'sans-serif'],
                    },
                    spacing: {
                        'margin-desktop': '32px',
                        'container-max': '1280px',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col justify-between">
    
    <!-- Top Bar Navigation (Sleek Clean Enterprise Design 100% Matching Admin) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 transition-all shadow-2xs">
        <div class="max-w-container-max mx-auto px-margin-desktop h-20 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo_westprog.png') }}" 
                     alt="Westprog Logo" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform">
                <div class="flex flex-col">
                    <span class="font-bold text-base text-primary leading-tight group-hover:text-primary-container transition block">Westprog Ticket</span>
                    <span class="text-[11px] font-semibold text-slate-500 block -mt-0.5">Tiket Wisata Kulon Progo</span>
                </div>
            </a>

            <!-- Right Desktop Nav Links (Matching Admin Pill & Logout Style) -->
            <div class="hidden md:flex items-center gap-3">
                
                <!-- Katalog Wisata Link -->
                <a href="{{ route('beranda') }}" 
                   class="font-label-md text-xs font-bold transition-all px-3.5 py-2 rounded-xl flex items-center gap-1.5 {{ request()->routeIs('beranda') || request()->routeIs('wisata.detail') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-sm">explore</span>
                    <span>Katalog Wisata</span>
                </a>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dasbor') }}" class="px-4 py-2 rounded-xl bg-primary text-white font-label-md text-xs font-bold hover:bg-primary-container transition-transform inline-flex items-center gap-1.5 shadow-md">
                            <span class="material-symbols-outlined text-sm">dashboard</span>
                            <span>Panel Admin</span>
                        </a>
                    @else
                        <!-- Riwayat Pemesanan Link -->
                        <a href="{{ route('user.riwayat') }}" 
                           class="font-label-md text-xs font-bold transition-all px-3.5 py-2 rounded-xl flex items-center gap-1.5 {{ request()->routeIs('user.riwayat') || request()->routeIs('user.tiket.lihat') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <span class="material-symbols-outlined text-sm">confirmation_number</span>
                            <span>Riwayat Pemesanan</span>
                        </a>
                    @endif

                    <!-- User Profile Pill (100% Matching Admin Badge) -->
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 transition border border-slate-200/80 cursor-pointer shadow-2xs {{ request()->routeIs('profile.edit') ? 'ring-2 ring-primary/40' : '' }}" title="Pengaturan Profil Saya">
                        <div class="w-7 h-7 rounded-full bg-primary text-white font-bold flex items-center justify-center text-xs shadow-2xs">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="font-bold text-xs text-slate-900 leading-none">{{ Auth::user()->nama }}</span>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-primary mt-0.5">
                                {{ Auth::user()->role === 'admin' ? 'PENGELOLA SISTEM' : 'WISATAWAN' }}
                            </span>
                        </div>
                    </a>

                    <!-- Single Direct Logout Button (100% Matching Admin Red Button) -->
                    <form action="{{ route('keluar') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl font-label-md text-xs font-bold transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Keluar dari akun Anda">
                            <span class="material-symbols-outlined text-sm">logout</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('masuk') }}" class="px-4 py-2 text-xs font-bold text-primary hover:text-primary-container transition">Masuk</a>
                        <a href="{{ route('daftar') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-label-md text-xs font-bold hover:bg-primary-container transition shadow-md">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Processing Overlay -->
    <div id="processing-overlay" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex flex-col items-center justify-center hidden">
        <div class="bg-white p-6 rounded-3xl shadow-2xl flex flex-col items-center gap-4 text-center max-w-xs">
            <span class="material-symbols-outlined text-primary text-4xl animate-spin">progress_activity</span>
            <div>
                <h4 class="font-bold text-sm text-slate-900">Memproses Permintaan...</h4>
                <p class="text-xs text-slate-500 mt-1">Mohon tunggu sebentar, sistem sedang mengolah data Anda.</p>
            </div>
        </div>
    </div>

    <!-- Main Content Body -->
    <main class="flex-grow">
        @yield('konten')
    </main>

    <!-- Footer (Clean Pure White Enterprise Theme) -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-container-max mx-auto px-margin-desktop py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-3 md:col-span-2">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo_westprog.png') }}" alt="Logo" class="h-8 w-auto">
                        <span class="font-heading font-bold text-primary text-base">Westprog Ticket</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                        Sistem Informasi E-Tiket Pariwisata Resmi Kabupaten Kulon Progo. Memudahkan wisatawan menjelajahi keindahan Menoreh dan tempat wisata unggulan.
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Tautan Cepat</p>
                    <ul class="space-y-2 text-xs font-medium text-slate-600">
                        <li><a href="{{ route('beranda') }}" class="hover:text-primary transition">Katalog Wisata</a></li>
                        <li><a href="{{ route('user.riwayat') }}" class="hover:text-primary transition">Riwayat Pemesanan</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Pemerintah Daerah</p>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Dinas Pariwisata Kabupaten Kulon Progo<br>
                        DI Yogyakarta, Indonesia
                    </p>
                </div>
            </div>
            <div class="border-t border-slate-100 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 gap-4">
                <p>&copy; {{ date('Y') }} Westprog Ticket - Dinas Pariwisata Kulon Progo. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function handleFormSubmit(form) {
            const overlay = document.getElementById('processing-overlay');
            if (overlay) overlay.classList.remove('hidden');
        }
    </script>

    @auth
    <script>
        // Automatic Inactivity Auto-Logout Sesi 5 Menit
        (function() {
            const INACTIVITY_TIMEOUT = 5 * 60 * 1000; // 5 Menit (300.000 ms)
            let inactivityTimer;

            function resetInactivityTimer() {
                clearTimeout(inactivityTimer);
                inactivityTimer = setTimeout(function() {
                    alert('Sesi Anda telah berakhir karena tidak ada aktivitas selama 5 menit. Silakan masuk kembali.');
                    const logoutForm = document.querySelector('form[action="{{ route("keluar") }}"]');
                    if (logoutForm) {
                        logoutForm.submit();
                    } else {
                        window.location.href = "{{ route('masuk') }}";
                    }
                }, INACTIVITY_TIMEOUT);
            }

            const activityEvents = ['mousemove', 'keydown', 'click', 'touchstart', 'scroll'];
            activityEvents.forEach(function(eventName) {
                window.addEventListener(eventName, resetInactivityTimer, true);
            });

            resetInactivityTimer();
        })();
    </script>
    @endauth
</body>
</html>

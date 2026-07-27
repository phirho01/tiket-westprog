<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Panel Admin - Westprog Ticket')</title>

    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Alpine.js & Tailwind CSS -->
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
                        'surface': '#f6fbf4',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f0f5ee',
                        'on-surface': '#181d19',
                        'on-surface-variant': '#414942',
                        'outline-variant': '#c1c9c0',
                    },
                    fontFamily: {
                        'sans': ['Plus Jakarta Sans', 'sans-serif'],
                        'heading': ['Outfit', 'sans-serif'],
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
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col md:flex-row overflow-x-hidden">
    
    <!-- Sidebar Left Navigation (Sleek Stationary Sidebar) -->
    <aside class="w-full md:w-64 bg-white border-r border-slate-200 p-6 flex flex-col justify-between shrink-0 shadow-2xs md:h-screen md:sticky md:top-0 z-30">
        <div class="space-y-8">
            
            <!-- Brand Logo Admin -->
            <a href="{{ route('admin.dasbor') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo_westprog.png') }}" 
                     alt="Westprog Logo" class="h-10 w-auto object-contain">
                <div class="flex flex-col">
                    <span class="font-bold text-base text-primary leading-tight block">Westprog Ticket</span>
                    <span class="text-[11px] font-semibold text-slate-500 block -mt-0.5">Tiket Wisata Kulon Progo</span>
                </div>
            </a>

            <!-- Navigation Links (Hanya Modul Pengelolaan) -->
            <nav class="space-y-1.5 text-xs font-semibold">
                <a href="{{ route('admin.dasbor') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.dasbor') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-sm">dashboard</span>
                    <span>Ringkasan Dasbor</span>
                </a>

                <a href="{{ route('admin.wisata.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.wisata.*') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-sm">landscape</span>
                    <span>Kelola Objek Wisata</span>
                </a>

                <a href="{{ route('admin.pemesanan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.pemesanan.*') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-sm">confirmation_number</span>
                    <span>Pemesanan & Tiket</span>
                </a>

                <a href="{{ route('admin.ulasan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.ulasan.*') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-sm">rate_review</span>
                    <span>Moderasi Ulasan</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-md font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-sm">group</span>
                    <span>Kelola Pengguna</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Badge -->
        <div class="pt-6 border-t border-slate-100">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block text-center">
                © {{ date('Y') }} Westprog Ticket Admin
            </span>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 min-w-0 p-6 md:p-10 max-w-7xl mx-auto w-full overflow-x-hidden space-y-6">
        
        <!-- Top Navbar Header (Satu Tempat Tunggal Tombol Keluar) -->
        <div class="flex items-center justify-between bg-white rounded-2xl px-6 py-3.5 border border-slate-200 shadow-xs">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                <span class="material-symbols-outlined text-sm text-primary">admin_panel_settings</span>
                <span>Panel Pengelola Sistem</span>
            </div>

            <!-- Profile Badge & Single Unique Logout Button -->
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 transition border border-slate-200/80 cursor-pointer" title="Edit Profil Pengelola">
                    <div class="w-7 h-7 rounded-full bg-primary text-white font-bold flex items-center justify-center text-xs shadow-xs">
                        {{ strtoupper(substr(Auth::user()->nama ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-xs text-slate-900 leading-none">{{ Auth::user()->nama ?? 'Admin' }}</span>
                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-primary mt-0.5">PENGELOLA SISTEM</span>
                    </div>
                </a>

                <!-- SATU-SATUNYA TOMBOL KELUAR RESMI (SINGLE LOGOUT BUTTON) -->
                <form action="{{ route('keluar') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Keluar dari sistem">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Global Feedback Toast / Alert Banner Berhasil -->
        @if(session('sukses') || session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs transition animate-fade-in">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-emerald-600 text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div>
                        <span class="font-bold text-slate-900 block text-xs">BERHASIL!</span>
                        <span class="text-emerald-700 text-xs">{{ session('sukses') ?? session('success') }}</span>
                    </div>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        @endif

        <!-- Global Feedback Toast / Alert Banner Gagal -->
        @if(session('gagal') || session('error'))
            <div class="p-4 bg-rose-50 border border-rose-300 text-rose-800 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs transition animate-fade-in">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-rose-600 text-xl">warning</span>
                    <div>
                        <span class="font-bold text-slate-900 block text-xs">GAGAL / PENOLAKAN:</span>
                        <span class="text-rose-700 text-xs">{{ session('gagal') ?? session('error') }}</span>
                    </div>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
        @endif

        @yield('konten')
    </main>

    <!-- Modal Pop-up Interactive Konfirmasi -->
    <div x-data="{ open: false, type: 'save', title: '', message: '', targetForm: null, deleteUrl: '' }"
         @konfirmasi-aksi.window="open = true; type = $event.detail.type; title = $event.detail.title; message = $event.detail.message; targetForm = $event.detail.targetForm; deleteUrl = $event.detail.deleteUrl"
         x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="open = false" class="bg-white rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-8 max-w-sm w-full space-y-6 text-center">
            
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto"
                 :class="type === 'delete' ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-700'">
                <span class="material-symbols-outlined text-3xl" x-text="type === 'delete' ? 'delete_forever' : 'task_alt'"></span>
            </div>
            
            <div>
                <h3 class="font-heading text-lg font-extrabold text-slate-900" x-text="title"></h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed" x-text="message"></p>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" @click="open = false" class="py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </button>
                
                <template x-if="type === 'save'">
                    <button type="button" @click="open = false; if(targetForm) targetForm.submit();" class="py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold transition shadow-md">
                        Ya, Simpan
                    </button>
                </template>

                <template x-if="type === 'delete'">
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md">
                            Ya, Hapus Data
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>

    <script>
        function konfirmasiHapus(url, label) {
            window.dispatchEvent(new CustomEvent('konfirmasi-aksi', { 
                detail: { 
                    type: 'delete', 
                    title: 'Konfirmasi Hapus Data', 
                    message: 'Apakah Anda yakin ingin menghapus data ' + label + '? Tindakan ini tidak dapat dibatalkan.', 
                    deleteUrl: url 
                } 
            }));
        }

        function konfirmasiSimpanForm(event, message = 'Apakah Anda yakin ingin menyimpan perubahan data ini?') {
            event.preventDefault();
            const form = event.target;
            window.dispatchEvent(new CustomEvent('konfirmasi-aksi', { 
                detail: { 
                    type: 'save', 
                    title: 'Konfirmasi Simpan Data', 
                    message: message, 
                    targetForm: form 
                } 
            }));
        }

        function handleFormSubmit(form) {
            // Placeholder for form submit progress
        }
    </script>

    @auth
    <script>
        // Automatic Inactivity Auto-Logout Sesi 5 Menit untuk Admin
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

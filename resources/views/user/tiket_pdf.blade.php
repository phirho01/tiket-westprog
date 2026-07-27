<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>E-Tiket Resmi #WT-{{ $pemesanan->id_pemesanan }} - Westprog Ticket</title>
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#006948',
                        'primary-container': '#00855d',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }

        @media print {
            .no-print { display: none !important; }
            body { background-color: #ffffff !important; padding: 0 !important; }
            .print-card { border: none !important; shadow: none !important; box-shadow: none !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="p-4 sm:p-8 min-h-screen flex flex-col items-center justify-center text-slate-800">

    <!-- Action Bar (Hidden in Print / PDF Mode) -->
    <div class="no-print w-full max-w-2xl mb-6 flex items-center justify-between">
        <a href="{{ route('user.riwayat') }}" class="text-xs font-bold text-slate-600 hover:text-primary inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Riwayat Tiket</span>
        </a>

        <button onclick="window.print()" class="px-6 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2 cursor-pointer">
            <span class="material-symbols-outlined text-base">print</span>
            <span>Unduh / Cetak Tiket PDF</span>
        </button>
    </div>

    <!-- Official PDF E-Ticket Card -->
    <div class="print-card bg-white w-full max-w-2xl rounded-3xl border border-slate-200 shadow-2xl p-8 sm:p-10 space-y-8 relative overflow-hidden">
        
        <!-- Watermark Background -->
        <div class="absolute -right-16 -bottom-16 opacity-5 pointer-events-none">
            <img src="{{ asset('images/logo_westprog.png') }}" alt="Watermark" class="w-96 h-96 object-contain">
        </div>

        <!-- Ticket Header -->
        <div class="flex items-center justify-between border-b-2 border-primary/20 pb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_westprog.png') }}" alt="Logo Westprog" class="h-12 w-auto object-contain">
                <div>
                    <h1 class="font-heading text-xl font-extrabold text-slate-900 leading-tight">E-TIKET RESMI MASUK WISATA</h1>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-primary">Dinas Pariwisata Kabupaten Kulon Progo</span>
                </div>
            </div>
            <div class="text-right">
                <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400">KODE TIKET</span>
                <span class="font-heading text-xl font-extrabold text-primary">#WT-{{ $pemesanan->id_pemesanan }}</span>
            </div>
        </div>

        <!-- Visual Barcode & QR Code Section -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="text-center sm:text-left">
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Status Keabsahan Tiket</span>
                @if($pemesanan->status === 'berhasil')
                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 uppercase">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <span>VALID / AKTIF</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 uppercase">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        <span>PROSES VERIFIKASI</span>
                    </span>
                @endif
                <p class="text-[11px] text-slate-400 mt-2">Tunjukkan barcode ini kepada petugas loket di pintu masuk wisata.</p>
            </div>

            <!-- Barcode Pattern Visual -->
            <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center">
                <div class="w-44 h-12 flex items-center justify-between space-x-1 overflow-hidden px-2">
                    <div class="w-1.5 h-full bg-slate-900"></div>
                    <div class="w-1 h-full bg-slate-900"></div>
                    <div class="w-2.5 h-full bg-slate-900"></div>
                    <div class="w-1 h-full bg-slate-900"></div>
                    <div class="w-2 h-full bg-slate-900"></div>
                    <div class="w-3 h-full bg-slate-900"></div>
                    <div class="w-1 h-full bg-slate-900"></div>
                    <div class="w-2 h-full bg-slate-900"></div>
                    <div class="w-1.5 h-full bg-slate-900"></div>
                    <div class="w-2.5 h-full bg-slate-900"></div>
                </div>
                <span class="font-mono text-[10px] font-bold text-slate-600 mt-1 tracking-widest">WT-{{ sprintf('%08d', $pemesanan->id_pemesanan) }}</span>
            </div>
        </div>

        <!-- Detail Rincian Tiket Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs border-b border-slate-200 pb-6">
            <!-- Wisatawan Data -->
            <div class="space-y-3">
                <h3 class="font-heading text-sm font-bold text-slate-900 border-b border-slate-100 pb-1">Data Pengunjung</h3>
                <div>
                    <span class="text-slate-400 block">Nama Lengkap:</span>
                    <span class="font-bold text-slate-900 text-sm block">{{ $pemesanan->userAccount->nama ?? 'Wisatawan' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Alamat Surel (Email):</span>
                    <span class="font-semibold text-slate-700 block">{{ $pemesanan->userAccount->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Nomor HP / Seluler:</span>
                    <span class="font-semibold text-slate-700 block">{{ $pemesanan->userAccount->no_hp ?? '-' }}</span>
                </div>
            </div>

            <!-- Destinasi & Kunjungan Data -->
            <div class="space-y-3">
                <h3 class="font-heading text-sm font-bold text-slate-900 border-b border-slate-100 pb-1">Detail Kunjungan</h3>
                <div>
                    <span class="text-slate-400 block">Destinasi Wisata:</span>
                    @foreach($pemesanan->detailPemesanan as $dp)
                        <span class="font-bold text-primary text-sm block">{{ $dp->wisata->nama_wisata ?? 'Wisata' }}</span>
                    @endforeach
                </div>
                <div>
                    <span class="text-slate-400 block">Tanggal Kunjungan:</span>
                    <span class="font-bold text-slate-900 block">{{ date('d F Y', strtotime($pemesanan->tanggal_kunjungan)) }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Jumlah Tiket Masuk:</span>
                    @foreach($pemesanan->detailPemesanan as $dp)
                        <span class="font-bold text-slate-900 block">{{ $dp->jumlah_tiket }} Tiket Pengunjung</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Rincian Pembayaran Footer -->
        <div class="flex items-center justify-between text-xs">
            <div>
                <span class="text-slate-400 block">Metode Pembayaran:</span>
                <span class="font-bold text-slate-800">{{ $pemesanan->pembayaran->metode_pembayaran ?? 'Transfer Bank' }}</span>
                <span class="inline-block ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px]">
                    {{ strtoupper($pemesanan->pembayaran->status_bayar ?? 'LUNAS') }}
                </span>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block">Total Biaya Pembayaran:</span>
                <span class="font-heading text-xl font-extrabold text-primary">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Ketentuan Penggunaan Tiket -->
        <div class="bg-slate-50 p-4 rounded-xl text-[11px] text-slate-500 space-y-1 leading-relaxed">
            <p class="font-bold text-slate-700">Syarat & Ketentuan Masuk Lokasi Wisata:</p>
            <p>1. Tunjukkan E-Tiket ini (versi cetak atau tangkapan layar di ponsel) kepada petugas loket masuk.</p>
            <p>2. E-Tiket ini hanya berlaku sesuai tanggal kunjungan yang tertera di atas.</p>
            <p>3. Dilarang menggandakan atau menyebarluaskan kode unik tiket kepada pihak yang tidak berhak.</p>
        </div>
    </div>

</body>
</html>

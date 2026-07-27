@extends('layouts.app')

@section('judul', 'Pemesanan Tiket - Westprog Ticket')

@section('konten')
<div class="max-w-2xl mx-auto py-12 px-4">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('beranda') }}" class="text-xs font-semibold text-on-surface-variant hover:text-primary inline-flex items-center gap-1 transition">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali ke Beranda Katalog</span>
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl p-8 space-y-6">
        <div class="border-b border-outline-variant/60 pb-5">
            <span class="text-xs font-bold text-primary uppercase tracking-widest block mb-1">Form Pemesanan Tiket Masuk</span>
            <h1 class="font-headline-lg text-2xl font-bold text-on-surface">{{ $wisata->nama_wisata }}</h1>
            <p class="text-xs text-on-surface-variant mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                <span>{{ $wisata->lokasi }}</span>
            </p>
        </div>

        @if($errors->any())
            <div class="bg-error-container text-on-error-container border border-error/20 p-4 rounded-xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('user.pesan.proses') }}" method="POST" class="space-y-6" onsubmit="handleFormSubmit(this)">
            @csrf
            <input type="hidden" name="id_wisata" value="{{ $wisata->id_wisata }}">
            <input type="hidden" id="harga_tiket" value="{{ $wisata->harga_tiket }}">

            <!-- Detail Harga Tiket & Jam Operasional Wisata -->
            <div class="bg-primary/10 border border-primary/20 rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="block text-on-surface-variant font-medium">Harga Resmi per Tiket</span>
                    <span class="font-headline-sm text-lg font-bold text-primary">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="block text-on-surface-variant font-medium">Jam Operasional Wisata</span>
                    <span class="font-bold text-emerald-800 text-sm flex items-center gap-1 mt-0.5">
                        <span class="material-symbols-outlined text-sm text-emerald-600">schedule</span>
                        <span>{{ date('H:i', strtotime($wisata->jam_buka ?? '07:00')) }} - {{ date('H:i', strtotime($wisata->jam_tutup ?? '17:00')) }} WIB</span>
                    </span>
                </div>
            </div>

            <!-- Tanggal Kunjungan (Bisa Diisi Manual dengan Ketik & Bisa Pilih Kalender) -->
            <div>
                <label for="tanggal_kunjungan" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                    Jadwal Tanggal Kunjungan <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" 
                        value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" 
                        min="{{ date('Y-m-d') }}" 
                        max="{{ $maxTanggal }}" required
                        onchange="updateSisaKuotaPerTanggal(this.value)"
                        onclick="try { this.showPicker(); } catch(e) {}"
                        class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm transition bg-surface-container-low focus:bg-surface font-semibold text-on-surface cursor-pointer">
                </div>
                
                <p class="text-[11px] text-on-surface-variant/80 mt-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-primary">calendar_clock</span>
                    <span>Dapat diketik manual atau dipilih dari kalender (maksimal <strong>7 hari ke depan</strong>). Tiket akan <strong>kadaluarsa (hangus)</strong> jika tidak digunakan pada tanggal tersebut.</span>
                </p>

                <!-- Box Informasi Sisa Kuota Dinamis -->
                <div id="box-sisa-kuota" class="mt-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-900 flex items-center gap-3 transition">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                    </div>
                    <div>
                        <span class="block text-[11px] text-emerald-700 uppercase font-bold tracking-wider">Ketersediaan Kuota Tiket:</span>
                        <span class="text-xs">Sisa kuota untuk <strong id="tgl-display" class="underline">--</strong> adalah <strong id="sisa-kuota-val" class="font-extrabold text-sm text-primary">--</strong> tiket.</span>
                    </div>
                </div>
            </div>

            <!-- Jumlah Tiket -->
            <div>
                <label for="jumlah_tiket" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                    Jumlah Tiket Dipesan <span class="text-error">*</span>
                </label>
                <input type="number" name="jumlah_tiket" id="jumlah_tiket" 
                    value="{{ old('jumlah_tiket', 1) }}" min="1" max="{{ $wisata->kuota_harian }}" required
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm transition bg-surface-container-low focus:bg-surface text-on-surface font-bold">
            </div>

            <!-- Bank Tujuan Pembayaran (Virtual Account Only) -->
            <div>
                <label for="bank_tujuan" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                    Metode Pembayaran (Virtual Account Bank) <span class="text-error">*</span>
                </label>
                <select name="bank_tujuan" id="bank_tujuan" required
                    class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary text-sm transition bg-surface-container-low focus:bg-surface text-on-surface font-semibold">
                    <option value="BCA">Bank Central Asia (BCA Virtual Account)</option>
                    <option value="Mandiri">Bank Mandiri (Mandiri Virtual Account)</option>
                    <option value="BNI">Bank Negara Indonesia (BNI Virtual Account)</option>
                    <option value="BRI">Bank Rakyat Indonesia (BRI Virtual Account)</option>
                    <option value="BSI">Bank Syariah Indonesia (BSI Virtual Account)</option>
                    <option value="Bank DIY">Bank DIY (Bank DIY Virtual Account)</option>
                    <option value="CIMB Niaga">Bank CIMB Niaga (CIMB Virtual Account)</option>
                    <option value="Permata">Bank Permata (Permata Virtual Account)</option>
                </select>
                <p class="text-[11px] text-on-surface-variant mt-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-primary">account_balance</span>
                    <span>Kode Virtual Account akan diterbitkan otomatis. Tenggat waktu pembayaran adalah <strong>8 Jam</strong> dari waktu pemesanan.</span>
                </p>
            </div>

            <!-- Informasi Kebijakan Pembatalan & Pengembalian Dana (Refund Policy Notice Box) -->
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs space-y-2 text-amber-900">
                <div class="flex items-center gap-2 font-bold uppercase tracking-wider text-amber-800 text-[11px]">
                    <span class="material-symbols-outlined text-base">info</span>
                    <span>Ketentuan Pembayaran & Pengembalian Dana (Refund):</span>
                </div>
                <ul class="list-disc pl-5 space-y-1 text-[11px] leading-relaxed text-amber-900 font-medium">
                    <li>Setelah memesan, Anda diberikan waktu <strong>8 Jam</strong> untuk transfer ke kode Virtual Account dan mengunggah bukti pembayaran. Jika lewat 8 jam, tiket otomatis dibatalkan sistem.</li>
                    <li>Pengajuan pembatalan <strong>hanya dapat dilakukan maksimal 30 menit</strong> setelah Anda mengonfirmasi pembayaran (meskipun sudah diverifikasi pengelola).</li>
                    <li>Transaksi dengan total pembayaran <strong>di bawah Rp 50.000 ( &lt; Rp 50.000 )</strong>: Dana <strong>TIDAK DAPAT DIKEMBALIKAN (0% Refund)</strong>.</li>
                    <li>Transaksi dengan total pembayaran <strong>Rp 50.000 atau lebih ( &ge; Rp 50.000 )</strong>: Dana dikembalikan sebesar <strong>80% (Dipotong 20% biaya adm pembatalan)</strong>.</li>
                </ul>
            </div>

            <!-- Total Pembayaran Real-time -->
            <div class="pt-4 border-t border-outline-variant/60 flex items-center justify-between">
                <div>
                    <span class="block text-xs text-on-surface-variant font-medium">Total Pembayaran</span>
                    <span id="total_bayar_text" class="font-headline-lg text-2xl font-bold text-primary">
                        Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                    </span>
                </div>

                <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary-container text-on-primary font-bold rounded-xl text-xs transition shadow-md inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">confirmation_number</span>
                    <span>Dapatkan Kode Virtual Account</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const mapKuotaPerTanggal = @json($kuotaPerTanggal);

    function updateSisaKuotaPerTanggal(tglStr) {
        const tglDisplay = document.getElementById('tgl-display');
        const sisaKuotaVal = document.getElementById('sisa-kuota-val');
        const boxSisaKuota = document.getElementById('box-sisa-kuota');
        const inputJumlahTiket = document.getElementById('jumlah_tiket');

        if (tglStr && mapKuotaPerTanggal.hasOwnProperty(tglStr)) {
            const sisa = mapKuotaPerTanggal[tglStr];
            
            const parts = tglStr.split('-');
            const formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;

            if (tglDisplay) tglDisplay.textContent = formattedDate;
            if (sisaKuotaVal) sisaKuotaVal.textContent = sisa;
            if (inputJumlahTiket) inputJumlahTiket.max = sisa;

            if (sisa <= 0) {
                boxSisaKuota.className = 'mt-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-900 flex items-center gap-3 transition';
            } else {
                boxSisaKuota.className = 'mt-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-900 flex items-center gap-3 transition';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputTgl = document.getElementById('tanggal_kunjungan');
        if (inputTgl && inputTgl.value) {
            updateSisaKuotaPerTanggal(inputTgl.value);
        }
    });

    document.getElementById('jumlah_tiket').addEventListener('input', function() {
        const jumlah = parseInt(this.value) || 0;
        const harga = parseInt(document.getElementById('harga_tiket').value) || 0;
        const total = jumlah * harga;
        document.getElementById('total_bayar_text').textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
@endsection

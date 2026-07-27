@extends('layouts.app')

@section('judul', 'Riwayat Pemesanan Tiket - Westprog Ticket')

@section('konten')
<div class="max-w-container-max mx-auto px-margin-desktop py-10 space-y-8"
     x-data="{ 
        modalBatal: false, 
        modalUpload: false,
        actionUploadUrl: '',
        actionUrl: '', 
        kodeTiket: '', 
        nomorVA: '',
        totalHarga: 0, 
        jumlahRefund: 0, 
        isRefundable: false 
     }">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Riwayat Pemesanan Tiket</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola, lihat Virtual Account, konfirmasi bukti pembayaran, lihat E-Tiket, dan pantau status transaksi Anda.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('beranda') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-label-md text-xs font-bold hover:bg-primary-container transition-colors shadow-md inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">add</span>
                <span>Pesan Tiket Baru</span>
            </a>
        </div>
    </div>

    @if(session('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            <span>{{ session('sukses') ?? session('success') }}</span>
        </div>
    @endif

    @if(session('gagal'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-2xs">
            <span class="material-symbols-outlined text-sm">warning</span>
            <span>{{ session('gagal') }}</span>
        </div>
    @endif

    <!-- Tabel Riwayat Pemesanan (Clean Pure White Enterprise Theme 100% Matching Admin) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-heading text-base font-bold text-slate-900">Daftar Pemesanan Saya</h2>
            <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold shadow-2xs">
                Total: {{ $riwayatPemesanan->count() }} Transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">KODE TIKET & VA</th>
                        <th class="px-6 py-4">DESTINASI WISATA</th>
                        <th class="px-6 py-4">TGL KUNJUNGAN</th>
                        <th class="px-6 py-4">JUMLAH TIKET</th>
                        <th class="px-6 py-4">TOTAL BAYAR</th>
                        <th class="px-6 py-4">STATUS TRANSAKSI</th>
                        <th class="px-6 py-4 text-right">AKSI, BAYAR, & E-TIKET</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatPemesanan as $r)
                        @php
                            $today = now()->toDateString();
                            $isExpiredByDate = ($r->tanggal_kunjungan < $today) && ($r->status !== 'berhasil');
                            
                            // 1. Hitung tenggat waktu pembayaran (8 jam = 28.800 detik dari waktu pemesanan)
                            $waktuBooking = $r->waktu_pemesanan ? strtotime($r->waktu_pemesanan) : null;
                            $selisihDetikBooking = $waktuBooking ? (now()->timestamp - $waktuBooking) : 999999;
                            $isPaymentExpired = ($r->status === 'menunggu_pembayaran') && ($selisihDetikBooking > 28800);

                            // 2. Hitung batas pengajuan pembatalan (30 menit = 1.800 detik SETELAH konfirmasi pembayaran)
                            $waktuKonfirmasi = $r->waktu_konfirmasi_pembayaran ? strtotime($r->waktu_konfirmasi_pembayaran) : null;
                            $selisihDetikKonfirmasi = $waktuKonfirmasi ? (now()->timestamp - $waktuKonfirmasi) : 999999;
                            
                            $statusBayar = $r->pembayaran->status_bayar ?? 'pending';

                            // Pembatalan HANYA MUNCUL JIKA WISATAWAN SUDAH KONFIRMASI BAYAR ($waktuKonfirmasi IS NOT NULL) & <= 30 menit
                            $bisaBatal = ($waktuKonfirmasi !== null) && ($selisihDetikKonfirmasi >= 0) && ($selisihDetikKonfirmasi <= 1800) && ($r->status !== 'dibatalkan') && ($r->status !== 'kadaluarsa') && ($r->status !== 'menunggu_pembatalan') && !$isExpiredByDate && !$isPaymentExpired;
                            
                            // Akses E-Tiket PDF jika status 'berhasil' atau pembayaran 'lunas' & tidak dibatalkan/kadaluarsa
                            $isCancelledOrPendingCancel = ($r->status === 'dibatalkan') || ($r->status === 'menunggu_pembatalan');
                            $bisaLihatTiket = (($r->status === 'berhasil') || ($statusBayar === 'lunas')) && !$isExpiredByDate && !$isCancelledOrPendingCancel && !$isPaymentExpired;
                            
                            $totalVal = (float)$r->total_harga;
                            $isRefundableVal = ($totalVal >= 50000);
                            $refundVal = $isRefundableVal ? ($totalVal * 0.80) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="font-bold text-primary text-sm">#WT-{{ $r->id_pemesanan }}</div>
                                @if($r->nomor_va)
                                    <div class="text-[11px] font-mono text-slate-600 bg-slate-100 border border-slate-300 px-2 py-0.5 rounded mt-1 inline-block">
                                        VA: {{ $r->nomor_va }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-5 font-bold text-slate-900 whitespace-normal break-words max-w-xs">
                                @foreach($r->detailPemesanan as $dp)
                                    <div>{{ $dp->wisata->nama_wisata ?? 'Wisata' }}</div>
                                @endforeach
                            </td>

                            <td class="px-6 py-5 text-slate-600 font-medium whitespace-nowrap">{{ $r->tanggal_kunjungan }}</td>
                            
                            <td class="px-6 py-5 text-slate-700 font-medium whitespace-nowrap">
                                @foreach($r->detailPemesanan as $dp)
                                    <div>{{ $dp->jumlah_tiket }} Tiket</div>
                                @endforeach
                            </td>

                            <td class="px-6 py-5 font-bold text-slate-900 whitespace-nowrap">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                            
                            <!-- STATUS TRANSAKSI & KEABSAHAN TIKET (Matching Admin Status Badges) -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($r->status === 'berhasil' || $statusBayar === 'lunas')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-full text-[11px] border border-emerald-200 uppercase">VALID (LUNAS)</span>
                                @elseif($r->status === 'menunggu_verifikasi' || $statusBayar === 'menunggu_verifikasi')
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-800 font-bold rounded-full text-[11px] border border-indigo-300 uppercase">MENUNGGU VERIFIKASI ADMIN</span>
                                @elseif($r->status === 'menunggu_pembatalan')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-900 font-bold rounded-full text-[11px] border border-amber-300 uppercase">MENUNGGU BATAL</span>
                                @elseif($r->status === 'menunggu_pembayaran' && !$isPaymentExpired && !$isExpiredByDate)
                                    <span class="px-3 py-1 bg-amber-50 text-amber-900 font-bold rounded-full text-[11px] border border-amber-300 uppercase">MENUNGGU PEMBAYARAN</span>
                                @elseif($r->status === 'kadaluarsa' || $isExpiredByDate || $isPaymentExpired)
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold rounded-full text-[11px] border border-slate-300 uppercase">KADALUARSA</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 text-rose-700 font-bold rounded-full text-[11px] border border-rose-200 uppercase">DIBATALKAN</span>
                                @endif

                                @if($r->status === 'menunggu_pembayaran' && !$isPaymentExpired && !$isExpiredByDate)
                                    <div class="text-[10px] text-amber-800 font-medium mt-1">
                                        Tenggat Bayar: <span id="timer-bayar-{{ $r->id_pemesanan }}" class="font-bold font-mono">Hitung...</span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    
                                    <!-- Tombol Konfirmasi Pembayaran (Upload Bukti Bayar) -->
                                    @if($r->status === 'menunggu_pembayaran' && !$isPaymentExpired && !$isExpiredByDate)
                                        <button type="button"
                                                id="btn-upload-{{ $r->id_pemesanan }}"
                                                data-waktu-booking="{{ $waktuBooking }}"
                                                @click="modalUpload = true; actionUploadUrl = '{{ route('user.pesan.konfirmasi_bayar', $r->id_pemesanan) }}'; kodeTiket = '#WT-{{ $r->id_pemesanan }}'; nomorVA = '{{ $r->nomor_va }}'; totalHarga = {{ $totalVal }}"
                                                class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-1 shadow-2xs cursor-pointer">
                                            <span class="material-symbols-outlined text-sm">upload_file</span>
                                            <span>Konfirmasi Bayar</span>
                                        </button>
                                    @endif

                                    <!-- Conditional E-Tiket PDF Access -->
                                    @if($bisaLihatTiket)
                                        <a href="{{ route('user.tiket.lihat', $r->id_pemesanan) }}" target="_blank" 
                                           class="px-3.5 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-container transition-colors shadow-2xs inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                            <span>Lihat E-Tiket & PDF</span>
                                        </a>
                                    @elseif($r->status === 'dibatalkan')
                                        <span class="text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-xl inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">cancel</span>
                                            <span>E-Tiket Nonaktif (Dibatalkan)</span>
                                        </span>
                                    @elseif($r->status === 'menunggu_pembatalan')
                                        <span class="text-[11px] font-bold text-amber-800 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">pending</span>
                                            <span>E-Tiket Nonaktif (Proses Batal)</span>
                                        </span>
                                    @elseif($r->status === 'menunggu_verifikasi' || $statusBayar === 'menunggu_verifikasi')
                                        <span class="text-[11px] font-bold text-indigo-800 bg-indigo-50 border border-indigo-200 px-3 py-1.5 rounded-xl inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">hourglass_top</span>
                                            <span>Verifikasi Bukti Bayar Admin</span>
                                        </span>
                                    @elseif($r->status === 'kadaluarsa' || $isExpiredByDate || $isPaymentExpired)
                                        <span class="text-[11px] font-bold text-slate-500 bg-slate-100 border border-slate-300 px-3 py-1.5 rounded-xl inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">running_with_errors</span>
                                            <span>E-Tiket Nonaktif (Kadaluarsa)</span>
                                        </span>
                                    @endif

                                    <!-- Restricted Ticket Cancellation (HANYA BISA MUNCUL SETELAH KONFIRMASI BAYAR & MAKSIMAL 30 MENIT) -->
                                    @if($bisaBatal)
                                        <button type="button"
                                                id="btn-batal-{{ $r->id_pemesanan }}"
                                                data-waktu-acuan="{{ $waktuKonfirmasi }}"
                                                @click="modalBatal = true; actionUrl = '{{ route('user.pesan.batal', $r->id_pemesanan) }}'; kodeTiket = '#WT-{{ $r->id_pemesanan }}'; totalHarga = {{ $totalVal }}; jumlahRefund = {{ $refundVal }}; isRefundable = {{ $isRefundableVal ? 'true' : 'false' }}"
                                                class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition inline-flex items-center gap-1 shadow-2xs cursor-pointer" 
                                                title="Batas waktu pembatalan 30 menit setelah konfirmasi bayar">
                                            <span class="material-symbols-outlined text-sm">cancel</span>
                                            <span>Ajukan Batal (<span id="timer-batal-{{ $r->id_pemesanan }}" class="font-mono">Hitung...</span>)</span>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs italic">
                                Anda belum memiliki riwayat pemesanan tiket wisata.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Upload Bukti Pembayaran (Screenshot) -->
    <div x-show="modalUpload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="modalUpload = false" class="bg-white rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-8 max-w-md w-full space-y-6 text-left">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2 text-emerald-700">
                    <span class="material-symbols-outlined text-2xl">upload_file</span>
                    <h3 class="font-heading text-lg font-bold text-slate-900">Konfirmasi Pembayaran</h3>
                </div>
                <button type="button" @click="modalUpload = false" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-2 text-emerald-900">
                <div class="flex justify-between font-bold text-slate-900">
                    <span>Kode Transaksi:</span>
                    <span x-text="kodeTiket"></span>
                </div>
                <div class="flex justify-between font-bold text-primary">
                    <span>Kode Virtual Account:</span>
                    <span class="font-mono text-sm" x-text="nomorVA"></span>
                </div>
                <div class="flex justify-between font-bold text-slate-900">
                    <span>Total Pembayaran:</span>
                    <span x-text="'Rp ' + totalHarga.toLocaleString('id-ID')"></span>
                </div>
            </div>

            <form :action="actionUploadUrl" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="handleFormSubmit(this)">
                @csrf
                
                <div>
                    <label for="bukti_pembayaran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Unggah Screenshot / Bukti Transfer <span class="text-rose-600">*</span>
                    </label>

                    <div x-data="{ fileName: '' }" class="space-y-2">
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-2xl p-6 cursor-pointer bg-slate-50 hover:bg-slate-100/80 transition">
                            <span class="material-symbols-outlined text-3xl text-emerald-600 mb-1">add_photo_alternate</span>
                            <span class="text-xs font-bold text-slate-700">Pilih Berkas Foto Bukti Transfer</span>
                            <span class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 4 MB)</span>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" required
                                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                   class="hidden">
                        </label>
                        <template x-if="fileName">
                            <p class="text-xs font-semibold text-emerald-700 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                <span>Terpilih: <strong x-text="fileName"></strong></span>
                            </p>
                        </template>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="modalUpload = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-md inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">send</span>
                        <span>Kirim Bukti Pembayaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form Pengajuan Pembatalan & Rekening Refund -->
    <div x-show="modalBatal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="modalBatal = false" class="bg-white rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-8 max-w-md w-full space-y-6 text-left">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2 text-rose-700">
                    <span class="material-symbols-outlined text-2xl">cancel</span>
                    <h3 class="font-heading text-lg font-bold text-slate-900">Form Pengajuan Pembatalan</h3>
                </div>
                <button type="button" @click="modalBatal = false" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <!-- Calculation Notice Box -->
            <div class="p-4 rounded-2xl border text-xs space-y-1.5"
                 :class="isRefundable ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900'">
                <div class="flex justify-between font-bold text-slate-900">
                    <span>Kode Transaksi:</span>
                    <span x-text="kodeTiket"></span>
                </div>
                <div class="flex justify-between">
                    <span>Total Pembayaran:</span>
                    <span class="font-bold" x-text="'Rp ' + totalHarga.toLocaleString('id-ID')"></span>
                </div>
                
                <template x-if="isRefundable">
                    <div class="pt-2 border-t border-emerald-200 space-y-1">
                        <div class="flex justify-between font-extrabold text-emerald-800 text-sm">
                            <span>Pengembalian Dana (80%):</span>
                            <span x-text="'Rp ' + jumlahRefund.toLocaleString('id-ID')"></span>
                        </div>
                        <p class="text-[10px] text-emerald-700 font-medium">Sesuai ketentuan, pengajuan pembatalan untuk transaksi Rp 50.000 ke atas dikembalikan 80% (Dipotong 20% biaya adm).</p>
                    </div>
                </template>

                <template x-if="!isRefundable">
                    <div class="pt-2 border-t border-rose-200 space-y-1">
                        <div class="flex justify-between font-extrabold text-rose-800 text-sm">
                            <span>Pengembalian Dana (0%):</span>
                            <span>Rp 0</span>
                        </div>
                        <p class="text-[10px] text-rose-700 font-medium">Sesuai ketentuan, pembatalan untuk transaksi di bawah Rp 50.000 dana tidak dapat dikembalikan.</p>
                    </div>
                </template>
            </div>

            <!-- Form Action (Direct submit jika < 50rb, Tampilkan Isian Bank jika >= 50rb) -->
            <form :action="actionUrl" method="POST" class="space-y-4" onsubmit="handleFormSubmit(this)">
                @csrf
                
                <template x-if="isRefundable">
                    <div class="space-y-4 border-t border-slate-100 pt-3">
                        <div>
                            <label for="nama_bank" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Bank Tujuan Pengembalian <span class="text-rose-600">*</span>
                            </label>
                            <select name="nama_bank" id="nama_bank" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold bg-slate-50 focus:bg-white outline-none focus:ring-2 focus:ring-primary text-slate-900">
                                <option value="BCA">Bank Central Asia (BCA)</option>
                                <option value="Mandiri">Bank Mandiri</option>
                                <option value="BNI">Bank Negara Indonesia (BNI)</option>
                                <option value="BRI">Bank Rakyat Indonesia (BRI)</option>
                                <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                <option value="Bank DIY">Bank DIY</option>
                                <option value="CIMB Niaga">Bank CIMB Niaga</option>
                                <option value="Permata">Bank Permata</option>
                            </select>
                        </div>

                        <div>
                            <label for="nomor_rekening" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nomor Rekening Bank <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" placeholder="mis. 1234567890" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold bg-slate-50 focus:bg-white outline-none focus:ring-2 focus:ring-primary text-slate-900">
                        </div>

                        <div>
                            <label for="nama_rekening" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Pemilik Rekening (Sesuai Buku Tabungan) <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="nama_rekening" id="nama_rekening" placeholder="mis. Budi Santoso" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold bg-slate-50 focus:bg-white outline-none focus:ring-2 focus:ring-primary text-slate-900">
                        </div>
                    </div>
                </template>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="modalBatal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-md inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">send</span>
                        <span>Kirim Pengajuan Pembatalan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Real-time Live Countdown Script (8 Jam Tenggat Bayar & 30 Menit Batas Batal) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function perbaruiHitungMundur() {
            const sekarang = Math.floor(Date.now() / 1000);
            
            // 1. Timer Tenggat Bayar (8 Jam = 28.800 detik)
            document.querySelectorAll('[id^="timer-bayar-"]').forEach(timerSpan => {
                const id = timerSpan.id.replace('timer-bayar-', '');
                const buttonUpload = document.getElementById('btn-upload-' + id);
                if (!buttonUpload) return;
                
                const waktuBooking = parseInt(buttonUpload.getAttribute('data-waktu-booking'));
                if (!waktuBooking || isNaN(waktuBooking)) return;

                const sisaDetikBayar = (waktuBooking + 28800) - sekarang;
                if (sisaDetikBayar > 0) {
                    const jam = Math.floor(sisaDetikBayar / 3600);
                    const menit = Math.floor((sisaDetikBayar % 3600) / 60);
                    const detik = sisaDetikBayar % 60;
                    
                    const jamStr = jam < 10 ? '0' + jam : jam;
                    const menitStr = menit < 10 ? '0' + menit : menit;
                    const detikStr = detik < 10 ? '0' + detik : detik;

                    timerSpan.textContent = `${jamStr}:${menitStr}:${detikStr}`;
                } else {
                    timerSpan.textContent = 'Lewat 8 Jam (Kadaluarsa)';
                    if (buttonUpload) buttonUpload.classList.add('hidden');
                }
            });

            // 2. Timer Batas Pembatalan (30 Menit = 1.800 detik SETELAH konfirmasi bayar)
            document.querySelectorAll('[id^="btn-batal-"]').forEach(button => {
                const id = button.id.replace('btn-batal-', '');
                const waktuAcuan = parseInt(button.getAttribute('data-waktu-acuan'));
                const timerSpan = document.getElementById('timer-batal-' + id);

                if (!waktuAcuan || isNaN(waktuAcuan) || !timerSpan) return;

                const sisaDetikBatal = (waktuAcuan + 1800) - sekarang;

                if (sisaDetikBatal > 0 && sisaDetikBatal <= 1800) {
                    const menit = Math.floor(sisaDetikBatal / 60);
                    const detik = sisaDetikBatal % 60;
                    const menitStr = menit < 10 ? '0' + menit : menit;
                    const detikStr = detik < 10 ? '0' + detik : detik;

                    timerSpan.textContent = `Sisa ${menitStr}m ${detikStr}s`;
                } else {
                    button.classList.add('hidden');
                }
            });
        }

        perbaruiHitungMundur();
        setInterval(perbaruiHitungMundur, 1000);
    });
</script>
@endsection

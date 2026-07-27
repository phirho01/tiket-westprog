@extends('layouts.admin')

@section('judul', 'Kelola Pemesanan Tiket')

@section('konten')
<div class="space-y-8" x-data="{ openModal: false, openPreviewBukti: false, modalBuktiUrl: '', modalAction: '', modalData: {} }">
    
    <!-- Admin Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-primary border border-emerald-200 mb-2">
                <span class="material-symbols-outlined text-sm">confirmation_number</span>
                <span>Manajemen Transaksi Tiket</span>
            </span>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Kelola Pemesanan Tiket</h1>
            <p class="text-xs text-slate-500 mt-1">Verifikasi pembayaran wisatawan, periksa bukti transfer screenshot, setujui pengajuan pembatalan, atau kelola keabsahan status tiket.</p>
        </div>

        <div>
            <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold shadow-2xs">
                Total: {{ $pemesananList->total() }} Pemesanan
            </span>
        </div>
    </div>

    @if(session('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            <span>{{ session('sukses') ?? session('success') }}</span>
        </div>
    @endif

    <!-- Tabel Kelola Pemesanan (Clean Pure White Enterprise Theme) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Header Tabel & Search Bar GUI -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900">Daftar Transaksi Tiket Masuk</h2>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan {{ $pemesananList->firstItem() ?? 0 }} - {{ $pemesananList->lastItem() ?? 0 }} dari total {{ $pemesananList->total() }} data</p>
            </div>

            <!-- Server Search Form GUI -->
            <form action="{{ route('admin.pemesanan.index') }}" method="GET" class="relative w-full sm:w-84">
                <div class="relative flex items-center">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari ID tiket, nama wisatawan, wisata..."
                        class="w-full pl-10 pr-20 py-2.5 rounded-2xl bg-slate-50 border border-slate-300 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition shadow-2xs">
                    
                    <button type="submit" class="absolute left-1.5 top-1/2 -translate-y-1/2 p-1.5 rounded-xl text-slate-400 hover:text-primary hover:bg-slate-200/60 transition cursor-pointer flex items-center justify-center" title="Klik atau tekan Enter untuk mencari">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </button>

                    <div class="absolute right-1.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        @if(request('cari'))
                            <a href="{{ route('admin.pemesanan.index') }}" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center" title="Hapus pencarian">
                                <span class="material-symbols-outlined text-base">close</span>
                            </a>
                        @endif
                        <button type="submit" class="px-2.5 py-1 bg-primary text-white rounded-xl text-[11px] font-bold hover:bg-primary-container transition shadow-2xs flex items-center gap-1 cursor-pointer" title="Klik atau tekan Enter untuk mencari">
                            <span>Cari</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                    <tr>
                        <th class="py-4 px-6">KODE TIKET & VA</th>
                        <th class="py-4 px-6">PEMESAN (WISATAWAN)</th>
                        <th class="py-4 px-6">DESTINASI WISATA</th>
                        <th class="py-4 px-6">TOTAL BAYAR</th>
                        <th class="py-4 px-6">BUKTI TRANSFER</th>
                        <th class="py-4 px-6">STATUS PEMBAYARAN</th>
                        <th class="py-4 px-6">STATUS TIKET</th>
                        <th class="py-4 px-6 text-center">AKSI VERIFIKASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pemesananList as $p)
                        @php
                            $statusBayar = $p->pembayaran->status_bayar ?? 'pending';
                            $buktiBayar = $p->pembayaran->bukti_pembayaran ?? null;
                            $pembatalan = $p->pembatalan;
                            $namaBank = $pembatalan->nama_bank ?? $p->nama_bank;
                            $nomorRekening = $pembatalan->nomor_rekening ?? $p->nomor_rekening;
                            $namaRekening = $pembatalan->nama_rekening ?? $p->nama_rekening;
                            $jumlahRefund = $pembatalan->jumlah_refund ?? $p->jumlah_refund;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-5 px-6 whitespace-nowrap">
                                <div class="font-bold text-primary text-sm">#WT-{{ $p->id_pemesanan }}</div>
                                @if($p->nomor_va)
                                    <div class="text-[11px] font-mono text-slate-600 bg-slate-100 border border-slate-300 px-2 py-0.5 rounded mt-1 inline-block">
                                        VA: {{ $p->nomor_va }}
                                    </div>
                                @endif
                            </td>
                            
                            <td class="py-5 px-6 whitespace-normal break-words">
                                <div class="font-bold text-slate-900 text-sm">{{ $p->userAccount->nama ?? 'Wisatawan' }}</div>
                                <div class="text-[11px] text-slate-500 font-medium">{{ $p->userAccount->email ?? '-' }}</div>
                                
                                <!-- Information Box Refund Transfer (Jika Ada Pengajuan Pembatalan) -->
                                @if($p->status === 'menunggu_pembatalan' || $p->status === 'dibatalkan' || $namaBank)
                                    <div class="mt-2 p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-900 space-y-0.5">
                                        <div class="font-bold uppercase tracking-wider text-amber-800 text-[10px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs">account_balance</span>
                                            <span>Info Refund Rekening User:</span>
                                        </div>
                                        @if($namaBank)
                                            <div>Bank: <strong>{{ $namaBank }}</strong></div>
                                            <div>No. Rek: <strong class="font-mono">{{ $nomorRekening }}</strong></div>
                                            <div>a.n: <strong>{{ $namaRekening }}</strong></div>
                                            <div class="text-rose-700 font-bold mt-1">
                                                Refund 80%: 
                                                @if($jumlahRefund > 0)
                                                    Rp {{ number_format($jumlahRefund, 0, ',', '.') }}
                                                @else
                                                    Rp 0 (Total &lt; 50rb)
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-rose-700 font-bold mt-0.5">
                                                Refund 0%: Rp 0 (Transaksi &lt; Rp 50.000)
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="py-5 px-6 font-semibold text-slate-800 whitespace-normal break-words">
                                @foreach($p->detailPemesanan as $dp)
                                    <div>{{ $dp->wisata->nama_wisata ?? 'Wisata' }} ({{ $dp->jumlah_tiket }} Tiket)</div>
                                @endforeach
                                <div class="text-[11px] text-slate-500 font-normal mt-0.5">Tgl: {{ $p->tanggal_kunjungan }}</div>
                            </td>

                            <td class="py-5 px-6 font-bold text-slate-900 whitespace-nowrap">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>

                            <!-- BUKTI TRANSFER SCREENSHOT -->
                            <td class="py-5 px-6 whitespace-nowrap">
                                @if($buktiBayar)
                                    <button type="button" 
                                            @click="openPreviewBukti = true; modalBuktiUrl = '{{ asset('storage/' . $buktiBayar) }}'"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-[11px] font-bold transition shadow-2xs">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        <span>Lihat Bukti Foto</span>
                                    </button>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Belum diunggah</span>
                                @endif
                            </td>

                            <!-- STATUS PEMBAYARAN -->
                            <td class="py-5 px-6 whitespace-nowrap">
                                @if($statusBayar === 'lunas')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-full text-[11px] border border-emerald-200 uppercase">LUNAS</span>
                                @elseif($statusBayar === 'menunggu_verifikasi')
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-800 font-bold rounded-full text-[11px] border border-indigo-300 uppercase">MENUNGGU VERIFIKASI</span>
                                @elseif($statusBayar === 'pending')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-900 font-bold rounded-full text-[11px] border border-amber-300 uppercase">MENUNGGU BAYAR</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 text-rose-700 font-bold rounded-full text-[11px] border border-rose-200 uppercase">GAGAL</span>
                                @endif
                            </td>

                            <!-- STATUS TIKET -->
                            <td class="py-5 px-6 whitespace-nowrap">
                                @if($p->status === 'berhasil')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-full text-[11px] border border-emerald-200 uppercase">VALID</span>
                                @elseif($p->status === 'menunggu_pembatalan')
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-bold rounded-full text-[11px] border border-rose-300 uppercase">PENGAJUAN BATAL</span>
                                @elseif($p->status === 'menunggu_verifikasi')
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-800 font-bold rounded-full text-[11px] border border-indigo-300 uppercase">VERIFIKASI BUKTI</span>
                                @elseif($p->status === 'menunggu_pembayaran')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-900 font-bold rounded-full text-[11px] border border-amber-300 uppercase">MENUNGGU BAYAR</span>
                                @elseif($p->status === 'kadaluarsa')
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold rounded-full text-[11px] border border-slate-300 uppercase">KADALUARSA</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 text-rose-700 font-bold rounded-full text-[11px] border border-rose-200 uppercase">DIBATALKAN</span>
                                @endif
                            </td>

                            <td class="py-5 px-6 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-2">
                                    
                                    @if($p->status === 'menunggu_verifikasi' || $p->status === 'menunggu_pembayaran' || $p->status === 'menunggu')
                                        <!-- Tombol Setujui Lunas -->
                                        <form action="{{ route('admin.pemesanan.setujui', $p->id_pemesanan) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center gap-1 shadow-xs">
                                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                                <span>Verifikasi & Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Batalkan -->
                                        <form action="{{ route('admin.pemesanan.batalkan', $p->id_pemesanan) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan transaksi tiket ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">cancel</span>
                                                <span>Tolak</span>
                                            </button>
                                        </form>

                                    @elseif($p->status === 'menunggu_pembatalan')
                                        <!-- Disetujui Batal oleh Admin -->
                                        <form action="{{ route('admin.pemesanan.batalkan', $p->id_pemesanan) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center gap-1 shadow-xs">
                                                <span class="material-symbols-outlined text-sm">check</span>
                                                <span>Setujui Batal & Transfer Refund</span>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Ubah Status Custom (Modal Interactive) -->
                                        <button type="button" 
                                                @click="openModal = true; modalData = { id: '{{ $p->id_pemesanan }}', status: '{{ $p->status }}', status_bayar: '{{ $statusBayar }}', user: '{{ addslashes($p->userAccount->nama ?? '') }}' }"
                                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">tune</span>
                                            <span>Kelola Status</span>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-xs italic">
                                Belum ada data transaksi pemesanan tiket.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-5 border-t border-slate-100 flex items-center justify-between">
            {{ $pemesananList->links() }}
        </div>
    </div>

    <!-- Modal Lightbox Preview Bukti Transfer Photo -->
    <div x-show="openPreviewBukti" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs">
        <div @click.away="openPreviewBukti = false" class="bg-white rounded-3xl border border-slate-200 shadow-2xl p-6 max-w-lg w-full space-y-4 text-center">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-heading text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">receipt_long</span>
                    <span>Bukti Transfer Screenshot</span>
                </h3>
                <button type="button" @click="openPreviewBukti = false" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-center min-h-[250px]">
                <img :src="modalBuktiUrl" alt="Bukti Transfer" class="max-h-[70vh] w-auto object-contain rounded-xl shadow-xs">
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" @click="openPreviewBukti = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs">
                    Tutup Preview
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine.js Modal for Status Management -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="openModal = false" class="bg-white rounded-3xl border border-slate-200 shadow-2xl p-6 sm:p-8 max-w-md w-full space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="text-xs font-bold text-primary uppercase tracking-widest block mb-1">Modal Moderasi Status</span>
                <h3 class="font-heading text-xl font-bold text-slate-900">Ubah Status Pemesanan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kode Tiket: <strong class="text-primary">#WT-<span x-text="modalData.id"></span></strong> (<span x-text="modalData.user"></span>)</p>
            </div>

            <form :action="'{{ url('admin/pemesanan') }}/' + modalData.id + '/status'" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Keabsahan Tiket
                    </label>
                    <select name="status" x-model="modalData.status" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold focus:ring-2 focus:ring-primary outline-none bg-slate-50">
                        <option value="menunggu_pembayaran">MENUNGGU PEMBAYARAN</option>
                        <option value="menunggu_verifikasi">MENUNGGU VERIFIKASI</option>
                        <option value="berhasil">VALID (Disetujui)</option>
                        <option value="kadaluarsa">KADALUARSA (Hangus)</option>
                        <option value="dibatalkan">DIBATALKAN (Ditolak)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Pembayaran
                    </label>
                    <select name="status_bayar" x-model="modalData.status_bayar" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold focus:ring-2 focus:ring-primary outline-none bg-slate-50">
                        <option value="pending">MENUNGGU PEMBAYARAN</option>
                        <option value="menunggu_verifikasi">MENUNGGU VERIFIKASI</option>
                        <option value="lunas">LUNAS</option>
                        <option value="gagal">GAGAL / REJECTED</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="openModal = false" class="px-5 py-2.5 rounded-xl text-slate-600 bg-slate-100 text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md">
                        Simpan Perubahan Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use App\Models\Pembatalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PemesananUserController extends Controller
{
    public function tampilkanPesan($id_wisata)
    {
        $wisata = Wisata::findOrFail($id_wisata);
        $maxTanggal = date('Y-m-d', strtotime('+7 days'));

        $kuotaPerTanggal = [];
        for ($i = 0; $i <= 7; $i++) {
            $tgl = date('Y-m-d', strtotime("+$i days"));
            $terjual = DB::table('detail_pemesanan')
                ->join('pemesanan', 'detail_pemesanan.id_pemesanan', '=', 'pemesanan.id_pemesanan')
                ->where('detail_pemesanan.id_wisata', $wisata->id_wisata)
                ->where('pemesanan.tanggal_kunjungan', $tgl)
                ->whereNotIn('pemesanan.status', ['dibatalkan', 'kadaluarsa'])
                ->sum('detail_pemesanan.jumlah_tiket');
            
            $kuotaPerTanggal[$tgl] = max(0, $wisata->kuota_harian - $terjual);
        }

        return view('user.pesan', compact('wisata', 'maxTanggal', 'kuotaPerTanggal'));
    }

    public function prosesPesan(Request $request)
    {
        $maxTanggal = date('Y-m-d', strtotime('+7 days'));

        $request->validate([
            'id_wisata' => 'required|exists:wisata,id_wisata',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today|before_or_equal:' . $maxTanggal,
            'jumlah_tiket' => 'required|integer|min:1',
            'bank_tujuan' => 'required|string',
        ], [
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh di masa lalu.',
            'tanggal_kunjungan.before_or_equal' => 'Pemesanan tiket hanya dapat dilakukan maksimal 7 hari ke depan.',
            'jumlah_tiket.required' => 'Jumlah tiket wajib diisi.',
            'jumlah_tiket.min' => 'Jumlah tiket minimal 1.',
            'bank_tujuan.required' => 'Pilihan bank tujuan pembayaran wajib dipilih.',
        ]);

        $wisata = Wisata::findOrFail($request->id_wisata);

        $tiketTerjual = DB::table('detail_pemesanan')
            ->join('pemesanan', 'detail_pemesanan.id_pemesanan', '=', 'pemesanan.id_pemesanan')
            ->where('detail_pemesanan.id_wisata', $wisata->id_wisata)
            ->where('pemesanan.tanggal_kunjungan', $request->tanggal_kunjungan)
            ->whereNotIn('pemesanan.status', ['dibatalkan', 'kadaluarsa'])
            ->sum('detail_pemesanan.jumlah_tiket');

        $sisaKuota = $wisata->kuota_harian - $tiketTerjual;

        if ($request->jumlah_tiket > $sisaKuota) {
            return back()->withErrors([
                'jumlah_tiket' => 'Kuota tidak mencukupi untuk tanggal tersebut. Sisa kuota pada ' . date('d-m-Y', strtotime($request->tanggal_kunjungan)) . ': ' . max(0, $sisaKuota) . ' tiket.'
            ])->withInput();
        }

        $subtotal = $request->jumlah_tiket * $wisata->harga_tiket;

        // Generate Kode Virtual Account (VA) Unik
        $bankPrefix = [
            'BCA' => '88012',
            'Mandiri' => '89012',
            'BNI' => '88013',
            'BRI' => '88014',
            'BSI' => '88015',
            'Bank DIY' => '88016',
            'CIMB Niaga' => '88017',
            'Permata' => '88018',
        ];
        
        $cleanBank = trim(str_replace(['Transfer Bank', '(', ')'], '', $request->bank_tujuan));
        $prefix = $bankPrefix[$cleanBank] ?? '88000';
        $nomorVA = $prefix . rand(10000000, 99999999);

        DB::transaction(function () use ($request, $wisata, $subtotal, $nomorVA) {
            $pemesanan = Pemesanan::create([
                'id_user' => Auth::id(),
                'tanggal_pemesanan' => now()->toDateString(),
                'waktu_pemesanan' => now(),
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'total_harga' => $subtotal,
                'status' => 'menunggu_pembayaran',
                'nomor_va' => $nomorVA,
            ]);

            DetailPemesanan::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'id_wisata' => $wisata->id_wisata,
                'jumlah_tiket' => $request->jumlah_tiket,
                'subtotal' => $subtotal,
            ]);

            Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'metode_pembayaran' => 'Virtual Account (' . $request->bank_tujuan . ')',
                'tanggal_bayar' => now()->toDateString(),
                'status_bayar' => 'pending',
            ]);
        });

        return redirect()->route('user.riwayat')->with('sukses', 'Pemesanan tiket berhasil dibuat! Silakan bayar ke Virtual Account ' . $nomorVA . ' dalam waktu 8 jam.');
    }

    public function riwayat()
    {
        $today = now()->toDateString();

        // 1. Tandai kadaluarsa jika lewat tanggal kunjungan
        Pemesanan::where('id_user', Auth::id())
            ->where('tanggal_kunjungan', '<', $today)
            ->whereIn('status', ['menunggu', 'menunggu_pembayaran', 'menunggu_verifikasi', 'menunggu_pembatalan'])
            ->update(['status' => 'kadaluarsa']);

        // 2. Otomatis batalkan (kadaluarsa) jika status 'menunggu_pembayaran' dan sudah > 8 jam (28800 detik) dari waktu pemesanan
        $unpaidBookings = Pemesanan::where('id_user', Auth::id())
            ->where('status', 'menunggu_pembayaran')
            ->get();

        foreach ($unpaidBookings as $b) {
            $waktuBooking = $b->waktu_pemesanan ? strtotime($b->waktu_pemesanan) : null;
            if ($waktuBooking && (now()->timestamp - $waktuBooking) > 28800) {
                $b->update(['status' => 'kadaluarsa']);
                if ($b->pembayaran) {
                    $b->pembayaran->update(['status_bayar' => 'gagal']);
                }
            }
        }

        $riwayatPemesanan = Pemesanan::with(['detailPemesanan.wisata', 'pembayaran', 'pembatalan'])
            ->where('id_user', Auth::id())
            ->orderBy('id_pemesanan', 'desc')
            ->get();

        return view('user.riwayat', compact('riwayatPemesanan'));
    }

    public function konfirmasiBayar(Request $request, $id)
    {
        $pemesanan = Pemesanan::with(['pembayaran'])
            ->where('id_pemesanan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($pemesanan->status !== 'menunggu_pembayaran') {
            return redirect()->route('user.riwayat')->with('gagal', 'Transaksi ini tidak dalam status menunggu pembayaran.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'bukti_pembayaran.required' => 'Berkas bukti pembayaran (screenshot) wajib diunggah.',
            'bukti_pembayaran.image' => 'Berkas harus berupa gambar foto/screenshot.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 4 MB.',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_' . $pemesanan->id_pemesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/bukti_pembayaran', $filename);
            $dbPath = 'bukti_pembayaran/' . $filename;

            $pemesanan->update([
                'status' => 'menunggu_verifikasi',
                'waktu_konfirmasi_pembayaran' => now(),
            ]);

            if ($pemesanan->pembayaran) {
                $pemesanan->pembayaran->update([
                    'status_bayar' => 'menunggu_verifikasi',
                    'bukti_pembayaran' => $dbPath,
                ]);
            }
        }

        return redirect()->route('user.riwayat')->with('sukses', 'Konfirmasi pembayaran berhasil diunggah! Pembayaran Anda kini dalam status Menunggu Verifikasi Pengelola.');
    }

    public function lihatTiket($id)
    {
        $today = now()->toDateString();
        
        $pemesanan = Pemesanan::with(['userAccount', 'detailPemesanan.wisata', 'pembayaran'])
            ->where('id_pemesanan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($pemesanan->tanggal_kunjungan < $today && $pemesanan->status !== 'berhasil') {
            $pemesanan->update(['status' => 'kadaluarsa']);
            return redirect()->route('user.riwayat')->with('gagal', 'Tiket ini telah kadaluarsa (hangus) karena telah melewati tanggal kunjungan yang dijadwalkan.');
        }

        $statusBayar = $pemesanan->pembayaran->status_bayar ?? 'pending';
        if ($pemesanan->status !== 'berhasil' && $statusBayar !== 'lunas') {
            return redirect()->route('user.riwayat')->with('gagal', 'Akses E-Tiket dibatasi: Detail & E-Tiket Digital hanya dapat diakses setelah pembayaran Anda diverifikasi (LUNAS).');
        }

        return view('user.tiket_pdf', compact('pemesanan'));
    }

    public function ajukanPembatalan(Request $request, $id)
    {
        $pemesanan = Pemesanan::with(['pembayaran'])
            ->where('id_pemesanan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($pemesanan->status === 'dibatalkan' || $pemesanan->status === 'kadaluarsa') {
            return redirect()->route('user.riwayat')->with('gagal', 'Pemesanan ini sudah tidak dapat dibatalkan (Status: ' . strtoupper($pemesanan->status) . ').');
        }

        // Pengajuan pembatalan HANYA bisa dilakukan jika wisatawan SUDAH mengonfirmasi & mengunggah bukti bayar
        if (!$pemesanan->waktu_konfirmasi_pembayaran) {
            return redirect()->route('user.riwayat')->with('gagal', 'Pengajuan pembatalan hanya dapat dilakukan setelah Anda mengonfirmasi dan mengunggah bukti pembayaran.');
        }

        // Pengajuan pembatalan berlaku maksimal 30 MENIT setelah konfirmasi pembayaran
        $waktuKonfirmasi = strtotime($pemesanan->waktu_konfirmasi_pembayaran);
        $selisihMenit = ($waktuKonfirmasi) ? ((now()->timestamp - $waktuKonfirmasi) / 60) : 999;

        if ($selisihMenit > 30) {
            return redirect()->route('user.riwayat')->with('gagal', 'Batas waktu pengajuan pembatalan (30 menit setelah konfirmasi pembayaran) telah lewat.');
        }

        $totalHarga = (float) $pemesanan->total_harga;
        $namaBank = null;
        $nomorRekening = null;
        $namaRekening = null;
        $jumlahRefund = 0;

        if ($totalHarga >= 50000) {
            $request->validate([
                'nama_bank' => 'required|string|max:100',
                'nomor_rekening' => 'required|string|max:100',
                'nama_rekening' => 'required|string|max:255',
            ], [
                'nama_bank.required' => 'Bank tujuan pengembalian dana wajib dipilih.',
                'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
                'nama_rekening.required' => 'Nama pemilik rekening wajib diisi.',
            ]);

            $namaBank = $request->nama_bank;
            $nomorRekening = $request->nomor_rekening;
            $namaRekening = $request->nama_rekening;
            $jumlahRefund = $totalHarga * 0.80;
        }

        DB::transaction(function () use ($pemesanan, $namaBank, $nomorRekening, $namaRekening, $jumlahRefund) {
            $pemesanan->update(['status' => 'menunggu_pembatalan']);

            Pembatalan::updateOrCreate(
                ['id_pemesanan' => $pemesanan->id_pemesanan],
                [
                    'nama_bank' => $namaBank,
                    'nomor_rekening' => $nomorRekening,
                    'nama_rekening' => $namaRekening,
                    'jumlah_refund' => $jumlahRefund,
                    'tanggal_pengajuan' => now(),
                ]
            );
        });

        $pesanInformasi = ($totalHarga >= 50000)
            ? 'Pengajuan pembatalan tiket berhasil dikirim! Pengembalian dana 80% (Rp ' . number_format($jumlahRefund, 0, ',', '.') . ') akan ditransfer ke rekening ' . $namaBank . ' ' . $nomorRekening . ' setelah diverifikasi pengelola.'
            : 'Pengajuan pembatalan tiket berhasil dikirim! Sesuai ketentuan, transaksi di bawah Rp 50.000 dana tidak dapat dikembalikan.';

        return redirect()->route('user.riwayat')->with('sukses', $pesanInformasi);
    }
}

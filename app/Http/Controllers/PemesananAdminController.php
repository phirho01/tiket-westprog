<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PemesananAdminController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $cari = $request->get('cari');

        // Otomatis tandai tiket yang lewat tanggal kunjungan sebagai 'kadaluarsa'
        Pemesanan::where('tanggal_kunjungan', '<', $today)
            ->whereIn('status', ['menunggu', 'menunggu_pembatalan'])
            ->update(['status' => 'kadaluarsa']);

        $query = Pemesanan::with(['userAccount', 'detailPemesanan.wisata', 'pembayaran', 'pembatalan']);

        if ($cari) {
            $query->where(function($q) use ($cari) {
                $q->whereCast('id_pemesanan', 'text', 'LIKE', "%{$cari}%")
                  ->orWhereHas('userAccount', function($u) use ($cari) {
                      $u->where('nama', 'ILIKE', "%{$cari}%")
                        ->orWhere('email', 'ILIKE', "%{$cari}%");
                  })
                  ->orWhereHas('detailPemesanan.wisata', function($w) use ($cari) {
                      $w->where('nama_wisata', 'ILIKE', "%{$cari}%");
                  })
                  ->orWhere('status', 'ILIKE', "%{$cari}%");
            });
        }

        $pemesananList = $query->orderBy('id_pemesanan', 'desc')->paginate(10)->withQueryString();
        $daftarPemesanan = $pemesananList;

        return view('admin.pemesanan.index', compact('pemesananList', 'daftarPemesanan', 'cari'));
    }

    public function setujui($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update(['status' => 'berhasil']);

        if ($pemesanan->pembayaran) {
            $pemesanan->pembayaran->update(['status_bayar' => 'lunas']);
        } else {
            Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'metode_pembayaran' => 'Transfer Bank',
                'tanggal_bayar' => now()->toDateString(),
                'status_bayar' => 'lunas'
            ]);
        }

        return redirect()->back()->with('sukses', 'Pemesanan dan pembayaran berhasil disetujui. Tiket kini berstatus LUNAS & VALID.');
    }

    public function batalkan($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update(['status' => 'dibatalkan']);

        if ($pemesanan->pembayaran) {
            $pemesanan->pembayaran->update(['status_bayar' => 'gagal']);
        }

        return redirect()->back()->with('sukses', 'Pemesanan tiket berhasil dibatalkan.');
    }

    public function perbaruiStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,berhasil,dibatalkan,menunggu_pembatalan,kadaluarsa',
            'status_bayar' => 'required|in:pending,lunas,gagal',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update(['status' => $request->status]);

        if ($pemesanan->pembayaran) {
            $pemesanan->pembayaran->update(['status_bayar' => $request->status_bayar]);
        } else {
            Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'metode_pembayaran' => 'Transfer Bank',
                'tanggal_bayar' => now()->toDateString(),
                'status_bayar' => $request->status_bayar
            ]);
        }

        return redirect()->back()->with('sukses', 'Status transaksi & keabsahan tiket berhasil diperbarui (Status Tiket: ' . strtoupper($request->status) . ').');
    }
}

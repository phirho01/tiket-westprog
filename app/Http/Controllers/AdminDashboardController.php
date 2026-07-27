<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Pemesanan;
use App\Models\UserAccount;
use App\Models\Ulasan;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function indeks()
    {
        $totalWisata = Wisata::count();
        $totalPemesanan = Pemesanan::count();
        $totalPendapatan = Pemesanan::where('status', 'berhasil')->sum('total_harga');
        $totalUser = UserAccount::where('role', 'user')->count();
        $totalUlasan = Ulasan::count();

        // 5 Transaksi Terbaru
        $transaksiTerbaru = Pemesanan::with(['userAccount', 'detailPemesanan.wisata', 'pembayaran'])
            ->orderBy('id_pemesanan', 'desc')
            ->limit(5)
            ->get();

        // Data Grafik Per Bulan (12 Bulan Jan - Des Tahun Berjalan)
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $statistikBulan = [];

        $tahunIni = date('Y');

        for ($m = 1; $m <= 12; $m++) {
            $label = $namaBulan[$m - 1];
            $sum = Pemesanan::where('status', 'berhasil')
                ->whereYear('tanggal_pemesanan', $tahunIni)
                ->whereMonth('tanggal_pemesanan', $m)
                ->sum('total_harga');

            $statistikBulan[$label] = (float)$sum;
        }

        return view('admin.dasbor', compact(
            'totalWisata',
            'totalPemesanan',
            'totalPendapatan',
            'totalUser',
            'totalUlasan',
            'transaksiTerbaru',
            'statistikBulan'
        ));
    }
}

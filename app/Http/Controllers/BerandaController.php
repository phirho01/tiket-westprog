<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function indeks(Request $request)
    {
        $cari = $request->get('cari');

        $query = Wisata::with('ulasan');

        if ($cari) {
            $query->where(function($q) use ($cari) {
                $q->where('nama_wisata', 'ILIKE', "%{$cari}%")
                  ->orWhere('lokasi', 'ILIKE', "%{$cari}%")
                  ->orWhere('deskripsi', 'ILIKE', "%{$cari}%");
            });
        }

        $daftarWisata = $query->orderBy('id_wisata', 'desc')->get();

        foreach ($daftarWisata as $w) {
            $w->total_ulasan = $w->ulasan->count();
            $w->rata_rating = $w->total_ulasan > 0 ? round($w->ulasan->avg('rating'), 1) : null;
        }

        return view('user.beranda', compact('daftarWisata', 'cari'));
    }

    public function detailWisata($id)
    {
        $wisata = Wisata::with(['ulasan.user'])->findOrFail($id);
        $totalUlasan = $wisata->ulasan->count();
        $rataRating = $totalUlasan > 0 ? round($wisata->ulasan->avg('rating'), 1) : null;

        return view('user.wisata_detail', compact('wisata', 'rataRating', 'totalUlasan'));
    }

    public function dasborUser()
    {
        return redirect()->route('beranda');
    }
}

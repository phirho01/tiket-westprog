<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_wisata' => 'required|exists:wisata,id_wisata',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string',
        ], [
            'rating.required' => 'Rating bintang wajib dipilih.',
            'komentar.required' => 'Ulasan komentar wajib diisi.',
        ]);

        Ulasan::create([
            'id_user' => Auth::id(),
            'id_wisata' => $request->id_wisata,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'tanggal_ulasan' => now()->toDateString(),
        ]);

        return redirect()->back()->with('sukses', 'Ulasan dan penilaian Anda berhasil dikirim! Terima kasih atas partisipasi Anda.');
    }

    public function adminIndex(Request $request)
    {
        $cari = $request->get('cari');

        $query = Ulasan::with(['user', 'wisata']);

        if ($cari) {
            $query->where(function($q) use ($cari) {
                $q->where('komentar', 'ILIKE', "%{$cari}%")
                  ->orWhereHas('user', function($u) use ($cari) {
                      $u->where('nama', 'ILIKE', "%{$cari}%");
                  })
                  ->orWhereHas('wisata', function($w) use ($cari) {
                      $w->where('nama_wisata', 'ILIKE', "%{$cari}%");
                  });
            });
        }

        $ulasanList = $query->orderBy('id_ulasan', 'desc')->paginate(10)->withQueryString();
        $ulasans = $ulasanList;

        return view('admin.ulasan.index', compact('ulasanList', 'ulasans', 'cari'));
    }

    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();

        return redirect()->back()->with('sukses', 'Ulasan pengunjung berhasil dihapus dari sistem.');
    }
}

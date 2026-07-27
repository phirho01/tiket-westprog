<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    public function indeksAdmin(Request $request)
    {
        $cari = $request->get('cari');

        $query = Wisata::query();

        if ($cari) {
            $query->where(function($q) use ($cari) {
                $q->where('nama_wisata', 'ILIKE', "%{$cari}%")
                  ->orWhere('lokasi', 'ILIKE', "%{$cari}%");
            });
        }

        $daftarWisata = $query->orderBy('id_wisata', 'desc')->paginate(10)->withQueryString();

        return view('admin.wisata.index', compact('daftarWisata', 'cari'));
    }

    public function indeks()
    {
        return redirect()->route('admin.wisata.index');
    }

    public function tambah()
    {
        return view('admin.wisata.tambah');
    }

    public function simpan(Request $request)
    {
        if ($request->has('harga_tiket')) {
            $request->merge([
                'harga_tiket' => preg_replace('/[^0-9]/', '', $request->harga_tiket)
            ]);
        }

        $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'harga_tiket' => 'required|numeric|min:0',
            'kuota_harian' => 'required|integer|min:0',
            'jam_buka' => 'required|string',
            'jam_tutup' => 'required|string',
            'link_gmaps' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'nama_wisata.required' => 'Nama wisata wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'harga_tiket.required' => 'Harga tiket wajib diisi.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka valid.',
            'kuota_harian.required' => 'Kuota harian wajib diisi.',
            'jam_buka.required' => 'Jam buka operasional wajib diisi.',
            'jam_tutup.required' => 'Jam tutup operasional wajib diisi.',
            'gambar.image' => 'Berkas gambar harus berupa file foto valid (jpg, png, webp).',
            'gambar.max' => 'Ukuran berkas gambar maksimal 5 MB.',
        ]);

        $namaGambar = 'default.jpg';

        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $file = $request->file('gambar');
            $namaGambar = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            
            $tujuanPath = public_path('uploads/wisata');
            if (!file_exists($tujuanPath)) {
                mkdir($tujuanPath, 0777, true);
            }
            $file->move($tujuanPath, $namaGambar);
        }

        Wisata::create([
            'nama_wisata' => $request->nama_wisata,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'harga_tiket' => $request->harga_tiket,
            'kuota_harian' => $request->kuota_harian,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'link_gmaps' => $request->link_gmaps,
            'gambar' => $namaGambar,
        ]);

        return redirect()->route('admin.wisata.index')->with('sukses', 'Data destinasi wisata berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $wisata = Wisata::findOrFail($id);
        return view('admin.wisata.edit', compact('wisata'));
    }

    public function perbarui(Request $request, $id)
    {
        $wisata = Wisata::findOrFail($id);

        if ($request->has('harga_tiket')) {
            $request->merge([
                'harga_tiket' => preg_replace('/[^0-9]/', '', $request->harga_tiket)
            ]);
        }

        $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'harga_tiket' => 'required|numeric|min:0',
            'kuota_harian' => 'required|integer|min:0',
            'jam_buka' => 'required|string',
            'jam_tutup' => 'required|string',
            'link_gmaps' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'nama_wisata.required' => 'Nama wisata wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'harga_tiket.required' => 'Harga tiket wajib diisi.',
            'harga_tiket.numeric' => 'Harga tiket harus berupa angka valid.',
            'kuota_harian.required' => 'Kuota harian wajib diisi.',
            'jam_buka.required' => 'Jam buka operasional wajib diisi.',
            'jam_tutup.required' => 'Jam tutup operasional wajib diisi.',
            'gambar.image' => 'Berkas gambar harus berupa file foto valid (jpg, png, webp).',
            'gambar.max' => 'Ukuran berkas gambar maksimal 5 MB.',
        ]);

        $namaGambar = $wisata->gambar;

        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $file = $request->file('gambar');
            $namaGambar = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            
            $tujuanPath = public_path('uploads/wisata');
            if (!file_exists($tujuanPath)) {
                mkdir($tujuanPath, 0777, true);
            }
            $file->move($tujuanPath, $namaGambar);
        }

        $wisata->update([
            'nama_wisata' => $request->nama_wisata,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'harga_tiket' => $request->harga_tiket,
            'kuota_harian' => $request->kuota_harian,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'link_gmaps' => $request->link_gmaps,
            'gambar' => $namaGambar,
        ]);

        return redirect()->route('admin.wisata.index')->with('sukses', 'Data destinasi wisata berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $wisata = Wisata::findOrFail($id);
        $wisata->delete();

        return redirect()->route('admin.wisata.index')->with('sukses', 'Data destinasi wisata berhasil dihapus.');
    }
}

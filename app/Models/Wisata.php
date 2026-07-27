<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $table = 'wisata';
    protected $primaryKey = 'id_wisata';
    public $timestamps = false;

    protected $fillable = [
        'nama_wisata',
        'deskripsi',
        'lokasi',
        'harga_tiket',
        'kuota_harian',
        'gambar',
        'link_gmaps',
        'jam_buka',
        'jam_tutup'
    ];

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_wisata', 'id_wisata');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_wisata', 'id_wisata');
    }
}

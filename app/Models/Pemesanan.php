<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'tanggal_pemesanan',
        'waktu_pemesanan',
        'tanggal_kunjungan',
        'total_harga',
        'status',
        'nama_bank',
        'nomor_rekening',
        'nama_rekening',
        'jumlah_refund',
        'nomor_va',
        'waktu_konfirmasi_pembayaran'
    ];

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class, 'id_user', 'id_user');
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pembatalan()
    {
        return $this->hasOne(Pembatalan::class, 'id_pemesanan', 'id_pemesanan');
    }
}

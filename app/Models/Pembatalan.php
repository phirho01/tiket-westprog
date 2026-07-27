<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembatalan extends Model
{
    protected $table = 'pembatalan';
    protected $primaryKey = 'id_pembatalan';
    public $timestamps = false;

    protected $fillable = [
        'id_pemesanan',
        'nama_bank',
        'nomor_rekening',
        'nama_rekening',
        'jumlah_refund',
        'tanggal_pengajuan',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}

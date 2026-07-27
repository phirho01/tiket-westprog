<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_wisata',
        'rating',
        'komentar',
        'tanggal_ulasan'
    ];

    public function user()
    {
        return $this->belongsTo(UserAccount::class, 'id_user', 'id_user');
    }

    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'id_wisata', 'id_wisata');
    }
}

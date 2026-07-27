<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_account';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_user', 'id_user');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_user', 'id_user');
    }
}

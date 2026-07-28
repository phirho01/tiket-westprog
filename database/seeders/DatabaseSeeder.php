<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed user_account (Admin & Wisatawan)
        $users = [
            [
                'id_user' => 1,
                'nama' => 'Pengelola Kulon Progo',
                'email' => 'admin@westprog.com',
                'password' => Hash::make('admin123'),
                'no_hp' => '081234567890',
                'role' => 'admin',
            ],
            [
                'id_user' => 2,
                'nama' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '089876543210',
                'role' => 'wisatawan',
            ],
            [
                'id_user' => 3,
                'nama' => 'Siti Aminah',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081399887766',
                'role' => 'wisatawan',
            ],
            [
                'id_user' => 4,
                'nama' => 'Andi Pratama',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '085712345678',
                'role' => 'wisatawan',
            ],
        ];

        foreach ($users as $u) {
            DB::table('user_account')->updateOrInsert(
                ['email' => $u['email']],
                $u
            );
        }

        // 2. Seed Wisata (Exact Kulon Progo Destinations)
        $wisataItems = [
            [
                'id_wisata' => 1,
                'nama_wisata' => 'Kalibiru',
                'deskripsi' => 'Wisata alam perbukitan dengan gardu pandang ikonik berlatar Waduk Sermo yang memesona.',
                'lokasi' => 'Hargowilis, Kokap',
                'harga_tiket' => 20000,
                'kuota_harian' => 150,
                'gambar' => '1785171079_kalibiru.jpg',
                'link_gmaps' => 'https://www.google.com/maps/search/?api=1&query=Wisata+Alam+Kalibiru+Kulon+Progo',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
            ],
            [
                'id_wisata' => 2,
                'nama_wisata' => 'Waduk Sermo',
                'deskripsi' => 'Waduk buatan megah dikelilingi perbukitan asri, cocok untuk area piknik dan kano.',
                'lokasi' => 'Hargowilis, Kokap',
                'harga_tiket' => 15000,
                'kuota_harian' => 200,
                'gambar' => '1785171055_embung_banjaroya.jpg',
                'link_gmaps' => 'https://www.google.com/maps/search/?api=1&query=Waduk+Sermo+Kulon+Progo',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
            ],
            [
                'id_wisata' => 3,
                'nama_wisata' => 'Air Terjun Kedung Pedut',
                'deskripsi' => 'Air terjun bertingkat dengan kolam pemandian alami berwarna hijau jernih.',
                'lokasi' => 'Jatimulyo, Girimulyo',
                'harga_tiket' => 10000,
                'kuota_harian' => 120,
                'gambar' => '1785171027_air_terjun_kembang_soka.jpg',
                'link_gmaps' => 'https://www.google.com/maps/search/?api=1&query=Air+Terjun+Kedung+Pedut+Kulon+Progo',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
            ],
            [
                'id_wisata' => 4,
                'nama_wisata' => 'Kebun Teh Nglinggo',
                'deskripsi' => 'Kebun teh hijau di puncak bukit Menoreh dengan udara sejuk dan panorama luas.',
                'lokasi' => 'Pagerharjo, Samigaluh',
                'harga_tiket' => 10000,
                'kuota_harian' => 150,
                'gambar' => '1785170988_kebun_teh_nglinggo.jpg',
                'link_gmaps' => 'https://www.google.com/maps/search/?api=1&query=Kebun+Teh+Nglinggo+Kulon+Progo',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
            ],
            [
                'id_wisata' => 5,
                'nama_wisata' => 'Pantai Glagah',
                'deskripsi' => 'Pantai pesisir selatan terkenal dengan tetrapod pemecah ombak dan laguna indah.',
                'lokasi' => 'Glagah, Temon',
                'harga_tiket' => 10000,
                'kuota_harian' => 300,
                'gambar' => '1785170928_pantai_glagah.jpg',
                'link_gmaps' => 'https://www.google.com/maps/search/?api=1&query=Pantai+Glagah+Kulon+Progo',
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
            ],
        ];

        foreach ($wisataItems as $w) {
            DB::table('wisata')->updateOrInsert(
                ['id_wisata' => $w['id_wisata']],
                $w
            );
        }

        // 3. Seed Ulasan
        $budi = DB::table('user_account')->where('email', 'budi@gmail.com')->value('id_user');
        $siti = DB::table('user_account')->where('email', 'siti@gmail.com')->value('id_user');

        if ($budi) {
            DB::table('ulasan')->updateOrInsert(
                ['id_user' => $budi, 'id_wisata' => 1],
                [
                    'rating' => 5,
                    'komentar' => 'Pemandangannya sangat memukau dan spot fotonya keren banget! Sangat direkomendasikan.',
                    'tanggal_ulasan' => now(),
                ]
            );
        }

        if ($siti) {
            DB::table('ulasan')->updateOrInsert(
                ['id_user' => $siti, 'id_wisata' => 2],
                [
                    'rating' => 5,
                    'komentar' => 'Suasana dan udaranya sejuk sekali, cocok untuk piknik keluarga di akhir pekan.',
                    'tanggal_ulasan' => now(),
                ]
            );
        }
    }
}

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
        if (DB::table('user_account')->where('email', 'admin@westprog.com')->count() == 0) {
            DB::table('user_account')->insert([
                [
                    'nama' => 'Pengelola Kulon Progo',
                    'email' => 'admin@westprog.com',
                    'password' => Hash::make('admin123'),
                    'no_hp' => '081234567890',
                    'role' => 'admin',
                ],
                [
                    'nama' => 'Budi Santoso',
                    'email' => 'budi@gmail.com',
                    'password' => Hash::make('password123'),
                    'no_hp' => '089876543210',
                    'role' => 'wisatawan',
                ],
                [
                    'nama' => 'Siti Aminah',
                    'email' => 'siti@gmail.com',
                    'password' => Hash::make('password123'),
                    'no_hp' => '081399887766',
                    'role' => 'wisatawan',
                ],
                [
                    'nama' => 'Andi Pratama',
                    'email' => 'andi@gmail.com',
                    'password' => Hash::make('password123'),
                    'no_hp' => '085712345678',
                    'role' => 'wisatawan',
                ],
            ]);
        }

        // 2. Seed Wisata (Destinasi Kulon Progo)
        if (DB::table('wisata')->count() == 0) {
            DB::table('wisata')->insert([
                [
                    'nama_wisata' => 'Wisata Alam Kalibiru',
                    'deskripsi' => 'Spot foto populer di atas pohon dengan latar belakang pemadangan Waduk Sermo dan pegunungan Menoreh yang memukau.',
                    'lokasi' => 'Hargowilis, Kokap, Kulon Progo',
                    'harga_tiket' => 20000,
                    'kuota_harian' => 150,
                    'gambar' => 'kalibiru.jpg',
                    'link_gmaps' => 'https://maps.google.com/?q=Kalibiru+Kulon+Progo',
                    'jam_buka' => '07:00:00',
                    'jam_tutup' => '17:00:00',
                ],
                [
                    'nama_wisata' => 'Waduk Sermo',
                    'deskripsi' => 'Danau buatan yang tenang cocok untuk camping, keliling perahu, santai keluarga, dan menikmati sunrise.',
                    'lokasi' => 'Hargowilis, Kokap, Kulon Progo',
                    'harga_tiket' => 15000,
                    'kuota_harian' => 200,
                    'gambar' => 'waduk_sermo.jpg',
                    'link_gmaps' => 'https://maps.google.com/?q=Waduk+Sermo+Kulon+Progo',
                    'jam_buka' => '06:00:00',
                    'jam_tutup' => '18:00:00',
                ],
                [
                    'nama_wisata' => 'Pantai Glagah Indah',
                    'deskripsi' => 'Pantai ikonik dengan pemecah gelombang (tetrapod) besar, laguna indah, serta wahana motor ATV.',
                    'lokasi' => 'Temon, Kulon Progo',
                    'harga_tiket' => 10000,
                    'kuota_harian' => 300,
                    'gambar' => 'pantai_glagah.jpg',
                    'link_gmaps' => 'https://maps.google.com/?q=Pantai+Glagah+Kulon+Progo',
                    'jam_buka' => '05:00:00',
                    'jam_tutup' => '19:00:00',
                ],
                [
                    'nama_wisata' => 'Air Terjun Kedung Pedut',
                    'deskripsi' => 'Kedung alami dua warna air (biru toska dan jernih) di tengah rimbunnya hutan pegunungan Menoreh.',
                    'lokasi' => 'Jatimulyo, Girimulyo, Kulon Progo',
                    'harga_tiket' => 20000,
                    'kuota_harian' => 120,
                    'gambar' => 'kedung_pedut.jpg',
                    'link_gmaps' => 'https://maps.google.com/?q=Kedung+Pedut+Kulon+Progo',
                    'jam_buka' => '08:00:00',
                    'jam_tutup' => '16:00:00',
                ],
                [
                    'nama_wisata' => 'Kebun Teh Nglinggo',
                    'deskripsi' => 'Hamparan hijau kebun teh di ketinggian dengan hawa sejuk dan pemandangan sembilan puncak gunung.',
                    'lokasi' => 'Pagerharjo, Samigaluh, Kulon Progo',
                    'harga_tiket' => 15000,
                    'kuota_harian' => 150,
                    'gambar' => 'kebun_teh_nglinggo.jpg',
                    'link_gmaps' => 'https://maps.google.com/?q=Kebun+Teh+Nglinggo+Kulon+Progo',
                    'jam_buka' => '06:30:00',
                    'jam_tutup' => '17:30:00',
                ],
            ]);
        }

        // 3. Seed Ulasan
        if (DB::table('ulasan')->count() == 0) {
            $budi = DB::table('user_account')->where('email', 'budi@gmail.com')->value('id_user');
            $siti = DB::table('user_account')->where('email', 'siti@gmail.com')->value('id_user');
            $kalibiru = DB::table('wisata')->where('nama_wisata', 'Wisata Alam Kalibiru')->value('id_wisata');
            $sermo = DB::table('wisata')->where('nama_wisata', 'Waduk Sermo')->value('id_wisata');

            if ($budi && $kalibiru) {
                DB::table('ulasan')->insert([
                    'id_user' => $budi,
                    'id_wisata' => $kalibiru,
                    'rating' => 5,
                    'komentar' => 'Pemandangannya sangat memukau dan spot fotonya keren banget! Sangat direkomendasikan.',
                    'tanggal_ulasan' => now(),
                ]);
            }

            if ($siti && $sermo) {
                DB::table('ulasan')->insert([
                    'id_user' => $siti,
                    'id_wisata' => $sermo,
                    'rating' => 5,
                    'komentar' => 'Suasana dan udaranya sejuk sekali, cocok untuk piknik keluarga di akhir pekan.',
                    'tanggal_ulasan' => now(),
                ]);
            }
        }
    }
}

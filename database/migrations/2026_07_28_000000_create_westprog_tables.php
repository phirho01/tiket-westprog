<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_account')) {
            Schema::create('user_account', function (Blueprint $table) {
                $table->increments('id_user');
                $table->string('nama');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('no_hp')->nullable();
                $table->string('role')->default('wisatawan');
            });
        }

        if (!Schema::hasTable('wisata')) {
            Schema::create('wisata', function (Blueprint $table) {
                $table->increments('id_wisata');
                $table->string('nama_wisata');
                $table->text('deskripsi')->nullable();
                $table->string('lokasi')->nullable();
                $table->integer('harga_tiket');
                $table->integer('kuota_harian')->default(100);
                $table->string('gambar')->nullable();
                $table->text('link_gmaps')->nullable();
                $table->time('jam_buka')->nullable()->default('07:00:00');
                $table->time('jam_tutup')->nullable()->default('17:00:00');
            });
        }

        if (!Schema::hasTable('pemesanan')) {
            Schema::create('pemesanan', function (Blueprint $table) {
                $table->increments('id_pemesanan');
                $table->integer('id_user');
                $table->date('tanggal_pemesanan')->useCurrent();
                $table->date('tanggal_kunjungan');
                $table->integer('total_harga');
                $table->string('status')->default('menunggu');
                $table->timestamp('waktu_pemesanan')->nullable()->useCurrent();
                $table->string('nama_bank')->nullable();
                $table->string('nomor_rekening')->nullable();
                $table->string('nama_rekening')->nullable();
                $table->decimal('jumlah_refund', 12, 2)->nullable()->default(0);
                $table->string('nomor_va')->nullable();
                $table->timestamp('waktu_konfirmasi_pembayaran')->nullable();
            });
        }

        if (!Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->increments('id_pembayaran');
                $table->integer('id_pemesanan');
                $table->string('metode_pembayaran');
                $table->date('tanggal_bayar')->nullable()->useCurrent();
                $table->string('status_bayar')->default('pending');
                $table->string('bukti_pembayaran')->nullable();
            });
        }

        if (!Schema::hasTable('ulasan')) {
            Schema::create('ulasan', function (Blueprint $table) {
                $table->increments('id_ulasan');
                $table->integer('id_user');
                $table->integer('id_wisata');
                $table->integer('rating');
                $table->text('komentar')->nullable();
                $table->date('tanggal_ulasan')->nullable()->useCurrent();
            });
        }

        if (!Schema::hasTable('pembatalan')) {
            Schema::create('pembatalan', function (Blueprint $table) {
                $table->increments('id_pembatalan');
                $table->integer('id_pemesanan');
                $table->string('nama_bank')->nullable();
                $table->string('nomor_rekening')->nullable();
                $table->string('nama_rekening')->nullable();
                $table->decimal('jumlah_refund', 12, 2)->nullable()->default(0);
                $table->timestamp('tanggal_pengajuan')->nullable()->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembatalan');
        Schema::dropIfExists('ulasan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('pemesanan');
        Schema::dropIfExists('wisata');
        Schema::dropIfExists('user_account');
    }
};

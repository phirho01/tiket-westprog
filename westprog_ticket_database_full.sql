-- ==============================================================================
-- STRUKTUR BASES DATA & SEED DATA LENGKAP: WESTPROG TICKET KULON PROGO
-- Disusun untuk PostgreSQL & MySQL / Laragon Compatible Database Engine
-- ==============================================================================

-- 1. PEMBUATAN TABEL USER ACCOUNT (PENGELOLA ADMIN & WISATAWAN)
CREATE TABLE IF NOT EXISTS user_account (
    id_user SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(50),
    role VARCHAR(50) DEFAULT 'wisatawan'
);

-- 2. PEMBUATAN TABEL DESTINASI WISATA KULON PROGO
CREATE TABLE IF NOT EXISTS wisata (
    id_wisata SERIAL PRIMARY KEY,
    nama_wisata VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    lokasi VARCHAR(255),
    harga_tiket INTEGER NOT NULL,
    kuota_harian INTEGER DEFAULT 100,
    gambar VARCHAR(255),
    link_gmaps TEXT,
    jam_buka TIME DEFAULT '07:00:00',
    jam_tutup TIME DEFAULT '17:00:00'
);

-- 3. PEMBUATAN TABEL PEMESANAN TIKET
CREATE TABLE IF NOT EXISTS pemesanan (
    id_pemesanan SERIAL PRIMARY KEY,
    id_user INTEGER NOT NULL,
    tanggal_pemesanan DATE DEFAULT CURRENT_DATE,
    tanggal_kunjungan DATE NOT NULL,
    total_harga INTEGER NOT NULL,
    status VARCHAR(50) DEFAULT 'menunggu',
    waktu_pemesanan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nama_bank VARCHAR(100),
    nomor_rekening VARCHAR(100),
    nama_rekening VARCHAR(100),
    jumlah_refund NUMERIC(12,2) DEFAULT 0,
    nomor_va VARCHAR(100),
    waktu_konfirmasi_pembayaran TIMESTAMP,
    CONSTRAINT fk_pemesanan_user FOREIGN KEY (id_user) REFERENCES user_account(id_user) ON DELETE CASCADE
);

-- 4. PEMBUATAN TABEL PEMBAYARAN TIKET
CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran SERIAL PRIMARY KEY,
    id_pemesanan INTEGER NOT NULL,
    metode_pembayaran VARCHAR(100) NOT NULL,
    tanggal_bayar DATE DEFAULT CURRENT_DATE,
    status_bayar VARCHAR(50) DEFAULT 'pending',
    bukti_pembayaran VARCHAR(255),
    CONSTRAINT fk_pembayaran_pemesanan FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan) ON DELETE CASCADE
);

-- 5. PEMBUATAN TABEL ULASAN WISATA
CREATE TABLE IF NOT EXISTS ulasan (
    id_ulasan SERIAL PRIMARY KEY,
    id_user INTEGER NOT NULL,
    id_wisata INTEGER NOT NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    komentar TEXT,
    tanggal_ulasan DATE DEFAULT CURRENT_DATE,
    CONSTRAINT fk_ulasan_user FOREIGN KEY (id_user) REFERENCES user_account(id_user) ON DELETE CASCADE,
    CONSTRAINT fk_ulasan_wisata FOREIGN KEY (id_wisata) REFERENCES wisata(id_wisata) ON DELETE CASCADE
);

-- 6. PEMBUATAN TABEL PEMBATALAN TIKET (ATURAN 10 MENIT REFUND)
CREATE TABLE IF NOT EXISTS pembatalan (
    id_pembatalan SERIAL PRIMARY KEY,
    id_pemesanan INTEGER NOT NULL,
    nama_bank VARCHAR(100),
    nomor_rekening VARCHAR(100),
    nama_rekening VARCHAR(100),
    jumlah_refund NUMERIC(12,2) DEFAULT 0,
    tanggal_pengajuan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pembatalan_pemesanan FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan) ON DELETE CASCADE
);

-- ==============================================================================
-- PENGISIAN DATA AWAL (SEED DATA DARI AWAL SAMPAI AKHIR)
-- ==============================================================================

-- A. PENGISIAN DATA USER ACCOUNT (PASSWORD: admin123 & password123)
INSERT INTO user_account (id_user, nama, email, password, no_hp, role) VALUES
(1, 'Pengelola Kulon Progo', 'admin@westprog.com', '$2y$12$hTEMq2qGi9i4GxJilU11n.8C/At4p9hP5e4dphN1b04fu6s.cuYcG', '081234567890', 'admin'),
(2, 'Budi Santoso', 'budi@gmail.com', '$2y$12$P7w.kJuDewOdEb0qXZc9cOtUg/feQIUNXigpIvZ1Jk8WUkKNXT2xC', '089876543210', 'wisatawan'),
(3, 'Siti Rahma', 'siti@gmail.com', '$2y$12$0vk3YPOZ4VHcnjloHsg3e.B.RJF4utSMnA0JtmMLMv.E7.hP0e4k.', '081399887766', 'wisatawan'),
(4, 'Andi Pratama', 'andi@gmail.com', '$2y$12$uL8adjqwOv3I/kzYSVPhTeZ/2rSNsa/97ULh.syu7MbGKM7xushfG', '085712345678', 'wisatawan')
ON CONFLICT (id_user) DO UPDATE SET email = EXCLUDED.email;

-- B. PENGISIAN DATA DESTINASI WISATA KULON PROGO
INSERT INTO wisata (id_wisata, nama_wisata, deskripsi, lokasi, harga_tiket, kuota_harian, gambar, link_gmaps, jam_buka, jam_tutup) VALUES
(1, 'Kalibiru', 'Wisata alam perbukitan dengan gardu pandang ikonik berlatar Waduk Sermo yang memesona.', 'Hargowilis, Kokap', 20000, 150, '1785171079_kalibiru.jpg', 'https://www.google.com/maps/search/?api=1&query=Wisata+Alam+Kalibiru+Kulon+Progo', '07:00:00', '17:00:00'),
(2, 'Waduk Sermo', 'Waduk buatan megah dikelilingi perbukitan asri, cocok untuk area piknik dan kano.', 'Hargowilis, Kokap', 15000, 200, '1785171055_embung_banjaroya.jpg', 'https://www.google.com/maps/search/?api=1&query=Waduk+Sermo+Kulon+Progo', '07:00:00', '17:00:00'),
(3, 'Air Terjun Kedung Pedut', 'Air terjun bertingkat dengan kolam pemandian alami berwarna hijau jernih.', 'Jatimulyo, Girimulyo', 10000, 120, '1785171027_air_terjun_kembang_soka.jpg', 'https://www.google.com/maps/search/?api=1&query=Air+Terjun+Kedung+Pedut+Kulon+Progo', '07:00:00', '17:00:00'),
(4, 'Kebun Teh Nglinggo', 'Kebun teh hijau di puncak bukit Menoreh dengan udara sejuk dan panorama luas.', 'Pagerharjo, Samigaluh', 10000, 150, '1785170988_kebun_teh_nglinggo.jpg', 'https://www.google.com/maps/search/?api=1&query=Kebun+Teh+Nglinggo+Kulon+Progo', '07:00:00', '17:00:00'),
(5, 'Pantai Glagah', 'Pantai pesisir selatan terkenal dengan tetrapod pemecah ombak dan laguna indah.', 'Glagah, Temon', 10000, 300, '1785170928_pantai_glagah.jpg', 'https://www.google.com/maps/search/?api=1&query=Pantai+Glagah+Kulon+Progo', '07:00:00', '17:00:00')
ON CONFLICT (id_wisata) DO UPDATE SET nama_wisata = EXCLUDED.nama_wisata;

-- C. PENGISIAN DATA ULASAN DESTINASI
INSERT INTO ulasan (id_ulasan, id_user, id_wisata, rating, komentar, tanggal_ulasan) VALUES
(1, 2, 1, 5, 'Pemandangannya sangat memukau dan spot fotonya keren banget! Sangat direkomendasikan.', CURRENT_DATE),
(2, 3, 2, 5, 'Suasana dan udaranya sejuk sekali, cocok untuk piknik keluarga di akhir pekan.', CURRENT_DATE)
ON CONFLICT (id_ulasan) DO NOTHING;

-- D. PENGISIAN SAMPLE DATA PEMESANAN & PEMBAYARAN
INSERT INTO pemesanan (id_pemesanan, id_user, tanggal_pemesanan, tanggal_kunjungan, total_harga, status, nomor_va, waktu_konfirmasi_pembayaran) VALUES
(1, 2, CURRENT_DATE, CURRENT_DATE, 40000, 'lunas', '880091283120', NOW())
ON CONFLICT (id_pemesanan) DO NOTHING;

INSERT INTO pembayaran (id_pembayaran, id_pemesanan, metode_pembayaran, tanggal_bayar, status_bayar) VALUES
(1, 1, 'Virtual Account BCA', CURRENT_DATE, 'berhasil')
ON CONFLICT (id_pembayaran) DO NOTHING;

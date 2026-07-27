# PRODUCT REQUIREMENT DOCUMENT (PRD)
## WestProg Ticket
### Sistem Informasi & Pemesanan Tiket Wisata Alam Kulon Progo

---

**Versi Dokumen:** 2.0 – Final Lengkap  
**Tanggal Dibuat:** Juli 2026  
**Pengembang:** Muhammad Rizki Oktavian  
**Institusi:** (diisi nama kampus/program studi)  
**Mata Kuliah:** Project Database  
**Target Selesai:** (diisi tanggal sesuai jadwal dosen)  
**Status Dokumen:** Final – Siap Implementasi

---

## DAFTAR ISI

1. [Definisi Produk](#1-definisi-produk)
2. [Tujuan & Goals Sistem](#2-tujuan--goals-sistem)
3. [Asumsi & Batasan](#3-asumsi--batasan)
4. [Lingkup Pekerjaan (Scope)](#4-lingkup-pekerjaan-scope)
5. [Daftar Fitur & Kebutuhan Sistem](#5-daftar-fitur--kebutuhan-sistem)
6. [Kriteria Rilis](#6-kriteria-rilis)
7. [Metrik Keberhasilan](#7-metrik-keberhasilan)

---

## 1. DEFINISI PRODUK

### 1.1 Gambaran Umum

**WestProg Ticket** adalah sistem informasi dan pemesanan tiket wisata alam berbasis web yang dirancang khusus untuk Kabupaten Kulon Progo, Daerah Istimewa Yogyakarta. Sistem ini menjadi jembatan digital antara pengelola wisata (admin) dan calon wisatawan (user) dalam proses reservasi tiket, verifikasi pembayaran, dan pengelolaan ulasan.

Sistem dibangun menggunakan framework **Laravel 11** dengan database **PostgreSQL**, berjalan di lingkungan lokal **Laragon**, dan dikembangkan menggunakan **Antigravity IDE**.

### 1.2 Latar Belakang & Permasalahan

Kulon Progo memiliki banyak destinasi wisata alam unggulan seperti Kalibiru, Waduk Sermo, dan Puncak Suroloyo. Namun, proses pemesanan tiket masih bersifat manual: calon pengunjung harus datang langsung atau menghubungi pengelola via WhatsApp. Hal ini menimbulkan masalah:

| No | Masalah | Dampak |
|---|---|---|
| 1 | Tidak ada platform terpusat untuk melihat informasi wisata | Calon pengunjung kesulitan membandingkan destinasi |
| 2 | Pemesanan tiket manual lewat WhatsApp | Tidak ada bukti transaksi formal, rawan konflik |
| 3 | Admin tidak memiliki data transaksi terstruktur | Sulit menganalisis tren kunjungan dan pendapatan |
| 4 | Tidak ada sistem kuota kunjungan harian | Risiko overkapasitas di hari tertentu |
| 5 | Tidak ada kanal ulasan resmi | Sulit membangun kepercayaan calon wisatawan baru |

### 1.3 Persona Pengguna

#### Persona 1: Wisatawan (User)
- **Nama fiktif:** Budi Santoso, 26 tahun
- **Profil:** Karyawan swasta di Yogyakarta, aktif di media sosial, terbiasa melakukan transaksi digital
- **Kebutuhan:** Ingin melihat destinasi wisata, memesan tiket untuk akhir pekan, mendapat konfirmasi digital, dan menulis ulasan setelah berkunjung
- **Pain point:** Tidak tahu kuota wisata masih tersedia atau tidak; bingung cara pembayaran resmi

#### Persona 2: Pengelola Wisata (Admin)
- **Nama fiktif:** Pak Hendra, 40 tahun
- **Profil:** Staf Dinas Pariwisata Kulon Progo, terbiasa menggunakan komputer untuk administrasi
- **Kebutuhan:** Mengelola data wisata, memverifikasi pembayaran masuk, memantau ulasan pengunjung, melihat laporan statistik
- **Pain point:** Data pesanan berserakan di WhatsApp; tidak ada rekap pendapatan harian yang sistematis

### 1.4 Spesifikasi Teknis

| Komponen | Teknologi |
|---|---|
| Framework Web | Laravel 11 (PHP 8.2+) |
| Database | PostgreSQL 15+ |
| Database GUI | pgAdmin 4 |
| ORM | Laravel Eloquent |
| Local Server Environment | Laragon (PostgreSQL port 5432) |
| IDE / Text Editor | Antigravity IDE |
| Penyimpanan Media | Laravel Local Storage (`storage/app/public/wisata`) |
| Authentication | Laravel Session-based Auth |
| Template Engine | Blade (Laravel) |

---

## 2. TUJUAN & GOALS SISTEM

### 2.1 Tujuan Umum

Membangun sistem informasi pemesanan tiket wisata yang terintegrasi antara antarmuka web dan database PostgreSQL, sehingga CRUD data dapat dilakukan dari dua arah: melalui aplikasi web maupun langsung melalui Query Tool pgAdmin.

### 2.2 SMART Goals

| # | Goal | Spesifik | Terukur | Dapat Dicapai | Relevan | Batas Waktu |
|---|---|---|---|---|---|---|
| G1 | Landing page publik aktif | Halaman `/` dapat diakses tanpa login dan menampilkan minimal 3 destinasi wisata | 3 card wisata tampil di browser | Ya, hanya butuh data seed | Memperkenalkan sistem ke calon pengunjung | Tahap 6 implementasi |
| G2 | Sistem autentikasi satu pintu berjalan | Login dengan email+password; sistem redirect otomatis berdasarkan `role` dari DB | 100% redirect benar pada role admin dan user | Ya, standar Laravel middleware | Tidak ada kebingungan peran di halaman login | Tahap 4 implementasi |
| G3 | CRUD wisata berfungsi dua arah | Data yang diubah via web muncul di pgAdmin dan sebaliknya dalam < 1 detik | Verifikasi manual pada 10 skenario CRUD | Ya, karena koneksi langsung ke DB yang sama | Membuktikan integrasi DB-Web | Tahap 9 implementasi |
| G4 | Proses pemesanan tiket end-to-end | User dapat memesan tiket, admin dapat memverifikasi, status tiket berubah | Alur selesai dari pesan → verifikasi admin → status berhasil | Ya, dengan 3 tabel terlibat | Fungsi inti sistem | Tahap 7–8 implementasi |
| G5 | Session auto-logout 5 menit aktif | Sesi otomatis hancur setelah 300 detik tidak aktif | Uji timer di browser: sesi mati pada menit ke-5 | Ya, konfigurasi `.env` + JS timer | Keamanan akun pengguna | Tahap 4 implementasi |

### 2.3 Nilai yang Diciptakan

**Untuk Wisatawan:**
- Kemudahan melihat informasi dan harga tiket tanpa harus datang langsung
- Proses pemesanan yang terstruktur dengan bukti transaksi digital
- Transparansi status pesanan secara real-time

**Untuk Admin/Pengelola:**
- Dashboard statistik kunjungan dan pendapatan terpusat
- Proses verifikasi pembayaran yang tertata dan terdokumentasi
- Manajemen data wisata yang mudah tanpa memerlukan keahlian SQL (cukup via web)

**Untuk Akademik (Mata Kuliah Project Database):**
- Demonstrasi integrasi nyata antara aplikasi web dan database relasional
- Implementasi normalisasi 3NF pada skema database 6 tabel
- Bukti CRUD dua arah antara Laravel Eloquent dan pgAdmin Query Tool

---

## 3. ASUMSI & BATASAN

### 3.1 Asumsi (Hal yang Dianggap Benar Saat Pengembangan)

| # | Asumsi |
|---|---|
| A1 | Laragon sudah terinstall dan PostgreSQL berjalan di port 5432 tanpa konflik |
| A2 | pgAdmin 4 sudah terhubung ke server PostgreSQL lokal milik Laragon |
| A3 | Antigravity IDE mendukung pengembangan PHP/Laravel secara normal |
| A4 | Pengguna (dosen & mahasiswa) mengakses sistem dari jaringan lokal yang sama (localhost) |
| A5 | Data wisata dan pengguna diisi secara manual sebagai data dummy untuk keperluan demo |
| A6 | Tidak ada payment gateway nyata; metode pembayaran hanya dicatat, verifikasi dilakukan admin secara manual |
| A7 | Gambar wisata disimpan di local storage, bukan cloud (AWS S3, dll.) |
| A8 | Satu pengguna hanya memiliki satu peran: admin atau user, tidak bisa keduanya |
| A9 | Semua pengguna mendaftar sendiri dengan role default `user`; akun admin dibuat langsung via SQL di pgAdmin |

### 3.2 Batasan Teknis (Constraints)

| # | Batasan | Implikasi |
|---|---|---|
| C1 | Sistem hanya berjalan di localhost (tidak deploy ke server publik) | Tidak bisa diakses dari luar jaringan lokal |
| C2 | Tidak menggunakan payment gateway (Midtrans, Xendit, dll.) | Verifikasi pembayaran dilakukan manual oleh admin |
| C3 | Upload gambar dibatasi 2 MB dan format jpg/jpeg/png | Gambar resolusi sangat tinggi perlu dikompres terlebih dahulu |
| C4 | Session timeout 5 menit di sisi klien menggunakan JavaScript | Jika JS dinonaktifkan di browser, timer sisi klien tidak berjalan |
| C5 | Tidak ada fitur notifikasi email (SMTP) | Konfirmasi pemesanan hanya bisa dilihat di halaman riwayat |
| C6 | Tidak ada fitur multi-bahasa | Seluruh antarmuka menggunakan Bahasa Indonesia |
| C7 | Tidak ada fitur ekspor laporan (PDF/Excel) pada versi ini | Admin hanya bisa melihat data di dashboard, tidak mengunduh |

### 3.3 Ketergantungan (Dependencies)

- PHP >= 8.2
- Composer (package manager PHP)
- Node.js & NPM (untuk Vite/asset bundling Laravel)
- PostgreSQL 15+ (via Laragon)
- Browser modern (Chrome/Firefox/Edge versi terbaru)

---

## 4. LINGKUP PEKERJAAN (SCOPE)

### 4.1 Yang Termasuk dalam Scope (In-Scope)

| Kategori | Fitur |
|---|---|
| Autentikasi | Login terpadu, Registrasi user, Logout, Session auto-logout 5 menit |
| Halaman Publik | Landing page, Katalog wisata (guest view) |
| Fitur User | Dashboard, Pemesanan tiket, Riwayat pesanan, Tulis ulasan |
| Fitur Admin | Dashboard KPI, CRUD wisata (+ upload gambar), Kelola pemesanan, Moderasi ulasan, Lihat daftar user |
| Integrasi DB | CRUD dua arah: Web ↔ pgAdmin |
| Keamanan | Password hashing Bcrypt, Role-based access control, Session management |
| Database | Desain skema 6 tabel PostgreSQL, normalisasi 3NF, data dummy awal |

### 4.2 Yang TIDAK Termasuk dalam Scope (Out-of-Scope)

| Fitur | Alasan Dikeluarkan |
|---|---|
| Payment gateway (Midtrans/Xendit) | Luar cakupan mata kuliah; butuh API key & server publik |
| Notifikasi email otomatis | Butuh konfigurasi SMTP; tidak diwajibkan dosen |
| Aplikasi mobile (Android/iOS) | Hanya web-based sesuai spesifikasi tugas |
| Deploy ke server publik (VPS/Heroku) | Cukup localhost untuk demo |
| Fitur ekspor laporan PDF/Excel | Tidak masuk dalam kebutuhan awal |
| Multi-bahasa / internasionalisasi | Tidak relevan untuk scope proyek ini |
| Fitur pencarian & filter lanjutan | Dapat ditambahkan sebagai pengembangan lanjutan |
| Integrasi Google Maps | Nice-to-have, bukan keharusan |

---

## 5. DAFTAR FITUR & KEBUTUHAN SISTEM

Bagian ini dibagi menjadi dua sub-bagian utama:
- **FR (Functional Requirements):** Apa yang sistem harus bisa lakukan
- **NFR (Non-Functional Requirements):** Bagaimana sistem harus berperilaku

---

### 5.1 FUNCTIONAL REQUIREMENTS (FR)

---

#### FR-01: Autentikasi & Manajemen Sesi

##### FR-01.1 — Login Terpadu (Unified Login)

**Deskripsi:** Sistem menyediakan satu halaman login untuk semua pengguna. Setelah login berhasil, sistem secara otomatis membaca atribut `role` dari database dan melakukan redirect.

**Logika Bisnis:**
```
FUNCTION handleLogin(email, password):
  user = cari user di tabel user_account WHERE email = input_email
  IF user NOT FOUND:
    tampilkan error "Email tidak terdaftar"
    RETURN
  IF Hash::check(input_password, user.password) == FALSE:
    tampilkan error "Password salah"
    RETURN
  buat sesi login untuk user
  IF user.role == 'admin':
    redirect ke /admin/dashboard
  ELSE IF user.role == 'user':
    redirect ke /user/dashboard
```

**Aturan:**
- Tidak ada elemen UI yang menyebutkan peran (tidak ada radio button "Login sebagai Admin/User")
- Login gagal menampilkan pesan error yang spesifik
- Password diverifikasi menggunakan `Hash::check()` (Bcrypt)

**Route:** `GET /login`, `POST /login`

---

##### FR-01.2 — Registrasi User Baru

**Deskripsi:** Calon wisatawan dapat membuat akun baru. Akun yang dibuat via form registrasi otomatis mendapat `role = 'user'`.

**Logika Bisnis:**
```
FUNCTION handleRegister(nama, email, no_hp, password, password_confirmation):
  IF email sudah ada di database:
    tampilkan error "Email sudah terdaftar"
    RETURN
  IF password != password_confirmation:
    tampilkan error "Konfirmasi password tidak cocok"
    RETURN
  IF panjang password < 8 karakter:
    tampilkan error "Password minimal 8 karakter"
    RETURN
  simpan ke tabel user_account:
    nama = input_nama
    email = input_email
    no_hp = input_no_hp
    password = Hash::make(input_password)  // Bcrypt hash
    role = 'user'  // hardcoded, tidak bisa diubah user
  redirect ke /login dengan pesan sukses "Akun berhasil dibuat"
```

**Route:** `GET /register`, `POST /register`

---

##### FR-01.3 — Logout

**Deskripsi:** Pengguna dapat keluar dari sistem secara manual. Sesi dihancurkan dan pengguna diarahkan ke halaman login.

**Route:** `POST /logout`

---

##### FR-01.4 — Session Auto-Logout (5 Menit Inaktivitas)

**Deskripsi:** Jika pengguna tidak melakukan interaksi apapun selama 5 menit, sistem otomatis menghancurkan sesi dan mengarahkan ke halaman login.

**Logika Implementasi:**
```
// Sisi Server (Laravel)
SESSION_LIFETIME = 5  // menit, dikonfigurasi di .env

// Sisi Klien (JavaScript) - untuk feedback visual
let timer = 300; // 300 detik
setInterval(() => {
  timer--;
  if (timer <= 60) tampilkanPeringatan("Sesi Anda akan berakhir dalam " + timer + " detik");
  if (timer <= 0) window.location.href = '/logout';
}, 1000);

// Reset timer setiap ada aktivitas user (click, scroll, keypress)
document.addEventListener('click', resetTimer);
document.addEventListener('keypress', resetTimer);
```

**Aturan:**
- Peringatan muncul 60 detik sebelum sesi berakhir
- Setiap klik/scroll/keyboard input me-reset timer
- Berlaku untuk role admin dan user

---

#### FR-02: Halaman Publik (Guest)

##### FR-02.1 — Landing Page

**Deskripsi:** Halaman utama yang dapat diakses tanpa login. Menampilkan informasi umum dan katalog wisata.

**Komponen & Konten:**

| Komponen | Deskripsi |
|---|---|
| Navbar | Logo "WestProg Ticket", menu navigasi (Home/Wisata/Tentang/Kontak), tombol "Login" dan "Daftar" |
| Hero Section | Banner visual alam Kulon Progo + tagline + tombol CTA "Jelajahi Wisata" |
| Katalog Wisata | Card grid: foto wisata, nama, lokasi, harga tiket, tombol "Pesan Sekarang" |
| Section Ulasan | Tampilan review terbaik (rating tertinggi) dari pengunjung nyata |
| Footer | Hak cipta, kontak pengelola, ikon media sosial |

**Logika Bisnis:**
```
// Tombol "Pesan Sekarang" pada card wisata
IF pengguna BELUM login:
  redirect ke /login
ELSE IF pengguna sudah login (role = 'user'):
  redirect ke /user/pesan/{id_wisata}
ELSE IF pengguna sudah login (role = 'admin'):
  // Admin tidak bisa memesan tiket
  tampilkan notifikasi "Akun admin tidak dapat memesan tiket"
```

**Route:** `GET /`

---

#### FR-03: Fitur User (Wisatawan)

Semua halaman user wajib dilindungi middleware `auth` + `role:user`. Jika diakses admin, redirect ke `/admin/dashboard`.

##### FR-03.1 — Dashboard User

**Deskripsi:** Halaman utama setelah user login. Menampilkan ringkasan aktivitas wisatawan.

**Data yang Ditampilkan:**
```
// Query agregasi untuk dashboard
tiket_aktif = COUNT pemesanan WHERE id_user = user.id AND status IN ('menunggu', 'berhasil')
total_riwayat = COUNT pemesanan WHERE id_user = user.id
kunjungan_terdekat = SELECT * FROM pemesanan
                     JOIN detail_pemesanan ON ...
                     JOIN wisata ON ...
                     WHERE id_user = user.id
                     AND tanggal_kunjungan >= TODAY
                     AND status = 'berhasil'
                     ORDER BY tanggal_kunjungan ASC
                     LIMIT 1
```

**Route:** `GET /user/dashboard`

---

##### FR-03.2 — Pemesanan Tiket

**Deskripsi:** User dapat memesan tiket wisata dengan memilih tanggal kunjungan dan jumlah tiket.

**Logika Bisnis:**
```
// Validasi sebelum submit form pemesanan
FUNCTION validatePemesanan(id_wisata, tanggal_kunjungan, jumlah_tiket, metode_bayar):
  
  wisata = ambil data wisata WHERE id_wisata = input_id_wisata
  
  IF tanggal_kunjungan < TODAY:
    ERROR "Tanggal kunjungan tidak boleh di masa lalu"
    RETURN
  
  // Cek kuota tersisa pada tanggal yang dipilih
  tiket_terjual = SUM(detail_pemesanan.jumlah_tiket)
                  JOIN pemesanan ON detail_pemesanan.id_pemesanan = pemesanan.id_pemesanan
                  WHERE detail_pemesanan.id_wisata = id_wisata
                  AND pemesanan.tanggal_kunjungan = tanggal_kunjungan
                  AND pemesanan.status != 'dibatalkan'
  
  kuota_tersisa = wisata.kuota_harian - tiket_terjual
  
  IF jumlah_tiket > kuota_tersisa:
    ERROR "Kuota tidak mencukupi. Tersisa: " + kuota_tersisa + " tiket"
    RETURN
  
  // Hitung total harga
  subtotal = jumlah_tiket * wisata.harga_tiket
  total_harga = subtotal  // (untuk satu wisata per pemesanan)

// Proses penyimpanan (dalam database transaction)
FUNCTION savePemesanan(user, id_wisata, tanggal_kunjungan, jumlah_tiket, metode_bayar):
  BEGIN TRANSACTION
    // 1. Simpan ke tabel pemesanan
    id_pemesanan = INSERT INTO pemesanan (id_user, tanggal_kunjungan, total_harga, status)
                   VALUES (user.id, tanggal_kunjungan, total_harga, 'menunggu')
    
    // 2. Simpan ke tabel detail_pemesanan
    INSERT INTO detail_pemesanan (id_pemesanan, id_wisata, jumlah_tiket, subtotal)
    VALUES (id_pemesanan, id_wisata, jumlah_tiket, subtotal)
    
    // 3. Simpan ke tabel pembayaran
    INSERT INTO pembayaran (id_pemesanan, metode_pembayaran, status_bayar)
    VALUES (id_pemesanan, metode_bayar, 'pending')
  COMMIT TRANSACTION
  
  redirect ke /user/riwayat dengan pesan sukses
```

**Kalkulator Subtotal (Real-time di Frontend):**
```javascript
// Update subtotal otomatis setiap jumlah tiket berubah
document.getElementById('jumlah_tiket').addEventListener('input', function() {
  const jumlah = parseInt(this.value) || 0;
  const harga = parseInt(document.getElementById('harga_tiket').value);
  const subtotal = jumlah * harga;
  document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
});
```

**Route:** `GET /user/pesan/{id_wisata}`, `POST /user/pesan`

---

##### FR-03.3 — Riwayat Pemesanan

**Deskripsi:** Menampilkan seluruh riwayat transaksi milik user yang sedang login.

**Logika Bisnis Tombol Aksi:**
```
FOR EACH pemesanan:
  IF pemesanan.status == 'berhasil':
    tampilkan tombol "Cetak E-Tiket"
    IF pemesanan.tanggal_kunjungan < TODAY:
      // Pengguna sudah berkunjung, boleh review
      IF belum ada review untuk wisata ini dari user ini:
        tampilkan tombol "Beri Ulasan"
      ELSE:
        tampilkan label "Sudah Diulas"
  ELSE IF pemesanan.status == 'menunggu':
    tampilkan label "Menunggu Verifikasi Admin"
  ELSE IF pemesanan.status == 'dibatalkan':
    tampilkan label "Dibatalkan"
```

**Route:** `GET /user/riwayat`

---

##### FR-03.4 — E-Ticket (Tiket Digital)

**Deskripsi:** User dapat melihat tiket digital setelah status pemesanan menjadi `berhasil`.

**Konten E-Ticket:**
- Nama wisata & lokasi
- Nama pemesan
- Tanggal kunjungan
- Jumlah tiket
- Total harga
- Kode unik pemesanan (ID Pemesanan)
- Status: VALID

**Route:** `GET /user/tiket/{id_pemesanan}`

---

##### FR-03.5 — Tulis Ulasan

**Deskripsi:** User dapat memberikan rating (1–5 bintang) dan komentar untuk wisata yang sudah dikunjungi.

**Logika Bisnis:**
```
FUNCTION submitReview(user, id_wisata, rating, komentar):
  // Validasi hak akses memberikan ulasan
  pemesanan_valid = SELECT p.* FROM pemesanan p
                    JOIN detail_pemesanan dp ON p.id_pemesanan = dp.id_pemesanan
                    WHERE p.id_user = user.id
                    AND dp.id_wisata = id_wisata
                    AND p.status = 'berhasil'
                    AND p.tanggal_kunjungan < TODAY
                    LIMIT 1
  
  IF pemesanan_valid NOT FOUND:
    ERROR 403 "Anda tidak berhak memberikan ulasan untuk wisata ini"
    RETURN
  
  review_existing = SELECT * FROM review WHERE id_user = user.id AND id_wisata = id_wisata
  IF review_existing EXISTS:
    ERROR "Anda sudah pernah memberikan ulasan untuk wisata ini"
    RETURN
  
  IF rating < 1 OR rating > 5:
    ERROR "Rating harus antara 1 dan 5"
    RETURN
  
  INSERT INTO review (id_user, id_wisata, rating, komentar) VALUES (...)
  redirect ke /user/riwayat dengan pesan sukses "Ulasan berhasil dikirim"
```

**Route:** `GET /user/review/{id_wisata}`, `POST /user/review`

---

#### FR-04: Fitur Admin (Pengelola)

Semua halaman admin wajib dilindungi middleware `auth` + `role:admin`. Jika diakses user biasa, redirect ke `/user/dashboard`.

##### FR-04.1 — Dashboard Admin

**Deskripsi:** Halaman utama admin dengan indikator kinerja utama (KPI).

**Query Agregasi untuk Kartu Statistik:**
```sql
-- Total wisata terdaftar
SELECT COUNT(*) FROM wisata;

-- Total semua pemesanan masuk
SELECT COUNT(*) FROM pemesanan;

-- Total pendapatan dari transaksi berhasil
SELECT COALESCE(SUM(total_harga), 0) AS total_pendapatan
FROM pemesanan WHERE status = 'berhasil';

-- Pesanan menunggu verifikasi
SELECT COUNT(*) FROM pemesanan WHERE status = 'menunggu';

-- 5 transaksi terbaru
SELECT p.id_pemesanan, ua.nama, p.tanggal_kunjungan, p.total_harga, p.status
FROM pemesanan p
JOIN user_account ua ON p.id_user = ua.id_user
ORDER BY p.tanggal_pemesanan DESC
LIMIT 5;
```

**Route:** `GET /admin/dashboard`

---

##### FR-04.2 — Manajemen Data Wisata (CRUD)

**Deskripsi:** Admin dapat menambah, melihat, mengubah, dan menghapus data objek wisata beserta gambarnya.

**Logika Bisnis CRUD Wisata:**
```
// CREATE
FUNCTION createWisata(nama, deskripsi, lokasi, harga, kuota, gambar_file):
  IF gambar_file NOT NULL:
    IF gambar_file.size > 2MB: ERROR "Ukuran gambar maks 2MB"
    IF gambar_file.extension NOT IN [jpg, jpeg, png]: ERROR "Format gambar tidak valid"
    nama_file = store gambar_file ke storage/app/public/wisata/
  INSERT INTO wisata (nama_wisata, deskripsi, lokasi, harga_tiket, kuota_harian, gambar)
  VALUES (nama, deskripsi, lokasi, harga, kuota, nama_file)

// UPDATE
FUNCTION updateWisata(id_wisata, data_baru, gambar_baru):
  wisata_lama = SELECT * FROM wisata WHERE id_wisata = id
  IF gambar_baru NOT NULL:
    hapus file gambar lama dari storage
    simpan gambar baru ke storage
    data_baru.gambar = nama_file_baru
  UPDATE wisata SET ... WHERE id_wisata = id

// DELETE
FUNCTION deleteWisata(id_wisata):
  // Cek apakah wisata memiliki pemesanan aktif
  pemesanan_aktif = COUNT pemesanan JOIN detail_pemesanan
                    WHERE id_wisata = id AND status NOT IN ('dibatalkan')
  IF pemesanan_aktif > 0:
    ERROR "Wisata tidak dapat dihapus karena memiliki pemesanan aktif"
    RETURN
  hapus file gambar dari storage
  DELETE FROM wisata WHERE id_wisata = id
  // ON DELETE CASCADE akan otomatis hapus detail_pemesanan & review terkait
```

**Route CRUD:**
| Aksi | Method | Route |
|---|---|---|
| Lihat semua | GET | `/admin/wisata` |
| Form tambah | GET | `/admin/wisata/create` |
| Simpan baru | POST | `/admin/wisata` |
| Form edit | GET | `/admin/wisata/{id}/edit` |
| Update | PUT/PATCH | `/admin/wisata/{id}` |
| Hapus | DELETE | `/admin/wisata/{id}` |

---

##### FR-04.3 — Kelola Pemesanan & Verifikasi Pembayaran

**Deskripsi:** Admin memverifikasi pembayaran dari wisatawan dan mengubah status transaksi.

**Logika Bisnis Verifikasi:**
```
// Setujui Pembayaran
FUNCTION approvePembayaran(id_pemesanan):
  BEGIN TRANSACTION
    UPDATE pemesanan SET status = 'berhasil' WHERE id_pemesanan = id
    UPDATE pembayaran SET status_bayar = 'lunas' WHERE id_pemesanan = id
  COMMIT TRANSACTION
  tampilkan notifikasi sukses "Pembayaran berhasil disetujui"

// Batalkan Pesanan
FUNCTION cancelPesanan(id_pemesanan):
  BEGIN TRANSACTION
    UPDATE pemesanan SET status = 'dibatalkan' WHERE id_pemesanan = id
    UPDATE pembayaran SET status_bayar = 'gagal' WHERE id_pemesanan = id
  COMMIT TRANSACTION
  tampilkan notifikasi sukses "Pesanan berhasil dibatalkan"
```

**Aturan Tambahan:**
- Tombol aksi hanya muncul jika status saat ini adalah `menunggu`
- Jika status sudah `berhasil` atau `dibatalkan`, tombol aksi tidak ditampilkan

**Route:** `GET /admin/pemesanan`, `PATCH /admin/pemesanan/{id}/approve`, `PATCH /admin/pemesanan/{id}/cancel`

---

##### FR-04.4 — Kelola Ulasan (Moderasi)

**Deskripsi:** Admin dapat melihat semua ulasan dan menghapus konten yang tidak pantas.

**Logika Bisnis:**
```
// Hapus ulasan (moderasi)
FUNCTION deleteReview(id_review):
  alasan_hapus = (dicatat secara internal, tidak ditampilkan ke user)
  DELETE FROM review WHERE id_review = id
  tampilkan notifikasi "Ulasan berhasil dihapus"
```

**Route:** `GET /admin/review`, `DELETE /admin/review/{id}`

---

##### FR-04.5 — Kelola Pengguna

**Deskripsi:** Admin dapat melihat daftar semua akun pengguna yang terdaftar.

**Catatan:** Pada versi ini, admin hanya bisa melihat (view only). Tidak ada fitur tambah/hapus user dari antarmuka web (akun admin dibuat via pgAdmin).

**Route:** `GET /admin/users`

---

#### FR-05: Integrasi Database Dua Arah (CRUD via pgAdmin)

**Deskripsi:** Semua data dapat dimanipulasi langsung via Query Tool pgAdmin dan perubahannya tercermin di web (karena mengakses DB yang sama).

**Contoh Skenario Uji Integrasi:**

| Skenario | Aksi di pgAdmin | Hasil di Web |
|---|---|---|
| Tambah wisata via SQL | `INSERT INTO wisata ...` | Wisata baru muncul di halaman admin & landing page |
| Ubah harga tiket via SQL | `UPDATE wisata SET harga_tiket = 20000 WHERE ...` | Harga tampil baru saat halaman di-refresh |
| Hapus review via Web | Klik "Hapus" di `/admin/review` | Record hilang dari tabel `review` di pgAdmin |
| Approve pembayaran via SQL | `UPDATE pembayaran SET status_bayar = 'lunas'` + `UPDATE pemesanan SET status = 'berhasil'` | Status di halaman riwayat user berubah |

---

### 5.2 NON-FUNCTIONAL REQUIREMENTS (NFR)

#### NFR-01: Keamanan (Security)

| ID | Kebutuhan | Implementasi |
|---|---|---|
| NFR-01.1 | Password tidak boleh tersimpan plain text | Bcrypt via `Hash::make()` (Laravel) / `crypt()` (pgAdmin) |
| NFR-01.2 | Halaman admin tidak bisa diakses user biasa | Middleware `role:admin` wajib di semua route `/admin/*` |
| NFR-01.3 | Halaman user tidak bisa diakses tanpa login | Middleware `auth` wajib di semua route `/user/*` dan `/admin/*` |
| NFR-01.4 | Proteksi CSRF pada semua form POST | Directive `@csrf` di setiap form Blade |
| NFR-01.5 | Input form divalidasi di sisi server | Laravel Form Request Validation |
| NFR-01.6 | Sesi otomatis dihancurkan setelah 5 menit inaktif | `SESSION_LIFETIME=5` di `.env` + JS timer |

#### NFR-02: Kegunaan / Usability — Prinsip Nielsen's 10 Heuristics

Sistem dirancang mengikuti 10 Prinsip Heuristik Nielsen untuk memastikan antarmuka yang intuitif dan mudah digunakan:

| # | Prinsip Nielsen | Implementasi dalam WestProg Ticket |
|---|---|---|
| H1 | **Visibility of System Status** — Pengguna selalu tahu apa yang sedang terjadi | Badge status berwarna pada tiket (Menunggu = kuning, Berhasil = hijau, Dibatalkan = merah). Loading indicator saat form disubmit. Notifikasi sukses/gagal setelah setiap aksi. |
| H2 | **Match Between System & Real World** — Bahasa sistem sesuai dunia nyata pengguna | Semua label menggunakan Bahasa Indonesia sehari-hari. Ikon yang dikenali: 🎫 tiket, 📅 kalender, ⭐ rating. Harga ditampilkan dengan format Rupiah (Rp 15.000). |
| H3 | **User Control & Freedom** — Pengguna bisa membatalkan aksi yang tidak disengaja | Konfirmasi dialog "Apakah Anda yakin?" sebelum aksi hapus/batalkan. Tombol kembali tersedia di setiap halaman form. |
| H4 | **Consistency & Standards** — Elemen yang sama terlihat dan berperilaku sama di seluruh halaman | Warna tombol primer konsisten di semua halaman. Navbar dan footer identik di semua halaman. Pola tabel yang sama di setiap halaman admin. |
| H5 | **Error Prevention** — Cegah kesalahan sebelum terjadi | Validasi form real-time (HTML5 `required`, `min`, `max`). Tanggal kunjungan tidak bisa dipilih di masa lalu (min date = hari ini). Input jumlah tiket dibatasi sesuai kuota tersisa. |
| H6 | **Recognition Rather Than Recall** — Kurangi beban memori pengguna | Nama wisata selalu ditampilkan berdampingan dengan ID-nya. Breadcrumb navigasi di halaman dalam. Opsi metode pembayaran ditampilkan sebagai radio button dengan label jelas. |
| H7 | **Flexibility & Efficiency** — Sistem bisa digunakan oleh pemula maupun mahir | Tombol "Pesan Sekarang" langsung dari landing page tanpa perlu masuk menu. Admin bisa CRUD langsung dari pgAdmin tanpa membuka browser. |
| H8 | **Aesthetic & Minimalist Design** — Hanya tampilkan informasi yang relevan | Setiap halaman hanya menampilkan data yang dibutuhkan. Tidak ada kolom atau informasi teknis (id FK, timestamp internal) yang ditampilkan ke pengguna. |
| H9 | **Help Users Recognize & Recover from Errors** — Pesan error yang jelas dan membantu | Pesan error spesifik: bukan "Terjadi kesalahan" tapi "Kuota untuk tanggal 20 Juli habis, tersisa 0 tiket". Form mempertahankan input yang sudah diisi setelah error (old input). |
| H10 | **Help & Documentation** — Sediakan panduan saat dibutuhkan | Teks placeholder informatif pada setiap field form. Tooltip singkat pada field yang tidak jelas. Halaman "Tentang" yang menjelaskan cara pemesanan. |

#### NFR-03: Performa (Performance)

| ID | Kebutuhan | Target |
|---|---|---|
| NFR-03.1 | Waktu load halaman | < 3 detik pada jaringan lokal |
| NFR-03.2 | Respons aksi CRUD | < 1 detik untuk operasi tulis/baca |
| NFR-03.3 | Ukuran gambar yang diunggah | Maks 2MB per file |

#### NFR-04: Keandalan (Reliability)

| ID | Kebutuhan | Implementasi |
|---|---|---|
| NFR-04.1 | Transaksi pemesanan bersifat atomic | Gunakan `DB::transaction()` Laravel saat menyimpan ke 3 tabel sekaligus |
| NFR-04.2 | Integritas referensial data | Foreign key + `ON DELETE CASCADE` di PostgreSQL |
| NFR-04.3 | Data tidak hilang jika satu langkah gagal | Rollback otomatis jika ada error dalam transaction |

#### NFR-05: Pemeliharaan (Maintainability)

| ID | Kebutuhan | Implementasi |
|---|---|---|
| NFR-05.1 | Kode terstruktur sesuai pola MVC | Model, View, Controller terpisah (standar Laravel) |
| NFR-05.2 | Nama variabel dan tabel konsisten | Snake_case untuk nama kolom DB; camelCase untuk variabel PHP |
| NFR-05.3 | Setiap tabel memiliki primary key auto-increment | `SERIAL PRIMARY KEY` di PostgreSQL |

---

## 6. KRITERIA RILIS

Sistem dinyatakan siap untuk demo/presentasi jika **semua** kriteria berikut terpenuhi:

### 6.1 Kriteria Fungsionalitas

| # | Kriteria | Cara Verifikasi |
|---|---|---|
| KR-01 | Halaman landing page dapat diakses tanpa login dan menampilkan daftar wisata | Buka browser, akses `http://localhost/` tanpa login |
| KR-02 | Login terpadu berjalan: admin redirect ke `/admin/dashboard`, user ke `/user/dashboard` | Coba login dengan 2 akun berbeda role |
| KR-03 | Registrasi user baru berhasil dan tersimpan di DB dengan role = 'user' | Daftar akun baru, cek di pgAdmin |
| KR-04 | Admin dapat melakukan CRUD wisata lengkap (tambah, lihat, edit, hapus + upload gambar) | Lakukan semua aksi CRUD, verifikasi data di pgAdmin |
| KR-05 | Pemesanan tiket berhasil menyimpan data ke 3 tabel sekaligus (pemesanan, detail, pembayaran) | Pesan tiket, cek 3 tabel di pgAdmin |
| KR-06 | Admin dapat setujui/batalkan pesanan; status berubah di tabel pemesanan DAN pembayaran | Approve pesanan, cek kedua tabel di pgAdmin |
| KR-07 | User dapat menulis ulasan setelah status berhasil & tanggal kunjungan lewat | Ubah status dan tanggal via pgAdmin, coba beri ulasan |
| KR-08 | Session auto-logout berjalan setelah 5 menit inaktif | Diamkan browser selama 5 menit, verifikasi sesi berakhir |
| KR-09 | CRUD dua arah terbukti: ubah data via pgAdmin → tercermin di web, dan sebaliknya | Lakukan minimal 4 skenario uji integrasi dari FR-05 |
| KR-10 | Middleware proteksi berjalan: user tidak bisa akses `/admin/*`, guest tidak bisa akses `/user/*` | Coba paksa akses URL terproteksi |

### 6.2 Kriteria Keamanan

| # | Kriteria | Cara Verifikasi |
|---|---|---|
| KR-11 | Password tersimpan dalam bentuk hash Bcrypt di database | Cek kolom `password` di pgAdmin — tidak boleh plain text |
| KR-12 | Form CSRF protection aktif | Coba kirim form tanpa token CSRF → harus gagal 419 |

### 6.3 Kriteria Usability

| # | Kriteria | Cara Verifikasi |
|---|---|---|
| KR-13 | Setiap aksi sukses/gagal menampilkan notifikasi yang informatif | Lakukan setiap aksi, amati pesan feedback |
| KR-14 | Form mempertahankan input setelah validasi gagal | Isi form dengan data salah, submit, cek apakah input lain masih ada |
| KR-15 | Konfirmasi dialog muncul sebelum aksi hapus | Klik tombol hapus, verifikasi dialog konfirmasi |

---

## 7. METRIK KEBERHASILAN

### 7.1 Metrik Teknis

| Metrik | Target | Cara Ukur |
|---|---|---|
| Jumlah tabel DB yang berhasil dibuat | 6/6 tabel | Cek di pgAdmin: `\dt` di Query Tool |
| Jumlah route yang berfungsi tanpa error | 100% route di `routes/web.php` | Uji setiap URL secara manual |
| Keberhasilan skenario uji CRUD dua arah | Minimal 8 dari 8 skenario lolos | Checklist uji integrasi FR-05 |
| Cakupan fitur FR yang terimplementasi | 100% dari FR-01 s.d. FR-05 | Review fungsionalitas sesuai daftar FR |

### 7.2 Metrik Jadwal Implementasi

| Tahap | Pekerjaan | Target Selesai | Status |
|---|---|---|---|
| Tahap 1 | Setup database: DDL 6 tabel + data dummy di pgAdmin | Minggu 1 | ⬜ |
| Tahap 2 | Setup proyek Laravel 11, konfigurasi `.env` PostgreSQL | Minggu 1 | ⬜ |
| Tahap 3 | Buat Model Eloquent + konfigurasi koneksi DB | Minggu 1 | ⬜ |
| Tahap 4 | Sistem Auth: Login, Register, Logout, Session 5 menit | Minggu 2 | ⬜ |
| Tahap 5 | Middleware role-based (admin/user) | Minggu 2 | ⬜ |
| Tahap 6 | Halaman publik: Landing Page + Katalog Guest | Minggu 2 | ⬜ |
| Tahap 7 | Fitur User: Dashboard, Pesan, Riwayat, E-Ticket, Review | Minggu 3 | ⬜ |
| Tahap 8 | Fitur Admin: Dashboard, CRUD Wisata, Pemesanan, Review, Users | Minggu 3–4 | ⬜ |
| Tahap 9 | Uji integrasi CRUD dua arah Web ↔ pgAdmin | Minggu 4 | ⬜ |
| Tahap 10 | Finishing: validasi form, responsivitas, uji semua KR | Minggu 4 | ⬜ |

### 7.3 Kanban Board (Manajemen Tugas)

```
BACKLOG          TO DO              IN PROGRESS        DONE
─────────────    ────────────────   ────────────────   ────────────────
Fitur ekspor  │  Setup Laravel  │                   │  PRD selesai
laporan       │  Setup pgAdmin  │                   │
              │  DDL 6 tabel   │                   │
              │  Model Eloquent│                   │
              │  Auth system   │                   │
              │  Middleware    │                   │
              │  Landing page  │                   │
              │  Fitur user    │                   │
              │  Fitur admin   │                   │
              │  Uji integrasi │                   │
```

---

## LAMPIRAN A: Skema Database Final

### DDL PostgreSQL Lengkap (Siap Dieksekusi di pgAdmin)

```sql
-- ============================================================
-- WestProg Ticket – DDL PostgreSQL
-- Jalankan di: pgAdmin 4 > Query Tool > Database westprog_ticket
-- ============================================================

-- 1. Aktifkan ekstensi hashing password
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- 2. Tabel User Account
CREATE TABLE user_account (
    id_user     SERIAL PRIMARY KEY,
    nama        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) UNIQUE NOT NULL,
    password    VARCHAR(255) NOT NULL,
    no_hp       VARCHAR(15),
    role        VARCHAR(20) NOT NULL DEFAULT 'user'
                CHECK (role IN ('admin', 'user'))
);

-- 3. Tabel Wisata
CREATE TABLE wisata (
    id_wisata       SERIAL PRIMARY KEY,
    nama_wisata     VARCHAR(100) NOT NULL,
    deskripsi       TEXT,
    lokasi          VARCHAR(150),
    harga_tiket     INTEGER NOT NULL CHECK (harga_tiket >= 0),
    kuota_harian    INTEGER NOT NULL DEFAULT 100 CHECK (kuota_harian >= 0),
    gambar          VARCHAR(255) NULL
);

-- 4. Tabel Pemesanan
CREATE TABLE pemesanan (
    id_pemesanan        SERIAL PRIMARY KEY,
    id_user             INTEGER NOT NULL,
    tanggal_pemesanan   DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_kunjungan   DATE NOT NULL,
    total_harga         INTEGER NOT NULL CHECK (total_harga >= 0),
    status              VARCHAR(20) NOT NULL DEFAULT 'menunggu'
                        CHECK (status IN ('menunggu', 'berhasil', 'dibatalkan')),
    CONSTRAINT fk_pemesanan_user
        FOREIGN KEY (id_user) REFERENCES user_account(id_user) ON DELETE CASCADE
);

-- 5. Tabel Detail Pemesanan
CREATE TABLE detail_pemesanan (
    id_detail       SERIAL PRIMARY KEY,
    id_pemesanan    INTEGER NOT NULL,
    id_wisata       INTEGER NOT NULL,
    jumlah_tiket    INTEGER NOT NULL CHECK (jumlah_tiket > 0),
    subtotal        INTEGER NOT NULL CHECK (subtotal >= 0),
    CONSTRAINT fk_detail_pemesanan
        FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan) ON DELETE CASCADE,
    CONSTRAINT fk_detail_wisata
        FOREIGN KEY (id_wisata) REFERENCES wisata(id_wisata) ON DELETE CASCADE
);

-- 6. Tabel Pembayaran
CREATE TABLE pembayaran (
    id_pembayaran       SERIAL PRIMARY KEY,
    id_pemesanan        INTEGER UNIQUE NOT NULL,
    metode_pembayaran   VARCHAR(50) NOT NULL,
    tanggal_bayar       DATE DEFAULT CURRENT_DATE,
    status_bayar        VARCHAR(20) NOT NULL DEFAULT 'pending'
                        CHECK (status_bayar IN ('pending', 'lunas', 'gagal')),
    CONSTRAINT fk_pembayaran_pemesanan
        FOREIGN KEY (id_pemesanan) REFERENCES pemesanan(id_pemesanan) ON DELETE CASCADE
);

-- 7. Tabel Review
CREATE TABLE review (
    id_review       SERIAL PRIMARY KEY,
    id_user         INTEGER NOT NULL,
    id_wisata       INTEGER NOT NULL,
    rating          INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    komentar        TEXT,
    tanggal_review  DATE DEFAULT CURRENT_DATE,
    CONSTRAINT fk_review_user
        FOREIGN KEY (id_user) REFERENCES user_account(id_user) ON DELETE CASCADE,
    CONSTRAINT fk_review_wisata
        FOREIGN KEY (id_wisata) REFERENCES wisata(id_wisata) ON DELETE CASCADE
);
```

### Data Dummy Awal

```sql
-- Insert akun admin (password: admin123)
INSERT INTO user_account (nama, email, password, no_hp, role)
VALUES ('Administrator', 'admin@westprog.id', crypt('admin123', gen_salt('bf')), '08111111111', 'admin');

-- Insert akun user (password: user123)
INSERT INTO user_account (nama, email, password, no_hp, role)
VALUES ('Budi Santoso', 'budi@email.com', crypt('user123', gen_salt('bf')), '08222222222', 'user');

-- Insert data wisata
INSERT INTO wisata (nama_wisata, deskripsi, lokasi, harga_tiket, kuota_harian) VALUES
('Kalibiru', 'Wisata alam dengan pemandangan Waduk Sermo dari ketinggian pohon.', 'Hargowilis, Kokap', 15000, 200),
('Waduk Sermo', 'Danau buatan yang dikelilingi perbukitan hijau, cocok untuk piknik dan kayak.', 'Hargowilis, Kokap', 10000, 300),
('Puncak Suroloyo', 'Titik tertinggi Kulon Progo dengan panorama matahari terbit yang menakjubkan.', 'Gerbosari, Samigaluh', 5000, 150);
```

---

## LAMPIRAN B: Konfigurasi Lingkungan

### File `.env` Laravel (Konfigurasi PostgreSQL + Laragon)

```env
APP_NAME="WestProg Ticket"
APP_ENV=local
APP_KEY=         # Generate dengan: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=westprog_ticket
DB_USERNAME=postgres
DB_PASSWORD=        # Sesuaikan dengan password PostgreSQL Laragon

SESSION_DRIVER=database
SESSION_LIFETIME=5

FILESYSTEM_DISK=public
```

### Struktur Direktori Proyek Laravel

```
westprog-ticket/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── WisataController.php
│   │   │   │   ├── PemesananController.php
│   │   │   │   ├── ReviewController.php
│   │   │   │   └── UserController.php
│   │   │   └── User/
│   │   │       ├── DashboardController.php
│   │   │       ├── PemesananController.php
│   │   │       ├── RiwayatController.php
│   │   │       └── ReviewController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── UserAccount.php
│       ├── Wisata.php
│       ├── Pemesanan.php
│       ├── DetailPemesanan.php
│       ├── Pembayaran.php
│       └── Review.php
├── resources/
│   └── views/
│       ├── public/
│       │   ├── landing.blade.php
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── user/
│       │   ├── dashboard.blade.php
│       │   ├── pesan.blade.php
│       │   ├── riwayat.blade.php
│       │   └── review.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── wisata/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           ├── pemesanan.blade.php
│           ├── review.blade.php
│           └── users.blade.php
├── routes/
│   └── web.php
└── storage/
    └── app/
        └── public/
            └── wisata/       ← folder penyimpanan gambar
```

---

*Dokumen ini adalah panduan utama dan acuan tunggal untuk seluruh proses pengembangan sistem WestProg Ticket. Semua keputusan implementasi harus mengacu pada dokumen ini.*

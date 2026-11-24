# MauLondri - Sistem Informasi Manajemen Laundry Berbasis Web

Aplikasi web manajemen operasional dan keuangan usaha laundry yang dibangun menggunakan **Laravel 11**, **Tailwind CSS**, dan **Alpine.js**. Dibuat untuk memenuhi tugas praktikum ASAS Ganjil 2026/2027.

---

## 🚀 Fitur Utama

1. **Autentikasi Multi-Role**
   - **Admin**: Akses penuh ke seluruh sistem termasuk kelola master paket laundry, pelanggan, transaksi, dan rekapitulasi laporan keuangan.
   - **Kasir**: Akses operasional untuk input pelanggan baru, pencatatan transaksi masuk, update status cucian (*Proses* ➔ *Selesai*), dan update pembayaran.

2. **Master Data Layanan (CRUD)**
   - Layanan **Reguler** (Rp 7.000/kg - durasi 3-4 hari)
   - Layanan **Ekspres** (Rp 12.000/kg - durasi 1-2 hari)
   - Layanan **Superkilat** (Rp 25.000/kg - durasi 5 jam selesai)
   - Fitur tambah, edit, hapus, dan pengurutan tarif per unit (kg/pcs).

3. **Transaksi & Kasir**
   - Penomoran nota/invoice otomatis (`TRX-Ymd-XXXX`).
   - Kalkulasi subtotal otomatis (*Live JavaScript Preview*).
   - Pelacakan status pengerjaan (*Baru*, *Sedang Dicuci / Proses*, *Selesai*, *Diambil*).
   - Pengelolaan status pembayaran (*Lunas* / *Belum Lunas*) dan metode bayar (Tunai, Transfer, QRIS).

4. **Laporan & Rekapitulasi**
   - Filter transaksi berdasarkan rentang tanggal, status pengerjaan, dan status pembayaran.
   - Kartu KPI: Total Omset Lunas, Total Piutang Belum Lunas, Jumlah Nota, dan Total Beban Cucian (kg).
   - Halaman cetak laporan resmi ramah printer (*CSS print stylesheet*).

5. **Tampilan Responsif**
   - Desain modern berbasis *Royal Purple & Crimson Red*.
   - Navigasi atas (top bar) dengan *hamburger mobile drawer* yang responsif di seluruh ukuran layar monitor maupun smartphone (360px+).

---

## 🛠️ Instalasi & Menjalankan Proyek

### 1. Kloning Repositori
```bash
git clone https://github.com/tresokel/maulondri.git
cd maulondri
```

### 2. Instal Dependensi
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment & Database
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maulondri
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder Database
Jalankan migrasi database beserta data dummy & akun awal:
```bash
php artisan migrate --seed
```

### 5. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui browser: [http://localhost:8000](http://localhost:8000)

---

## 🔑 Akun Demo Default

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `123` |
| **Kasir** | `kasir@gmail.com` | `123` |

---

## 🧪 Pengujian (Automated Testing)

Aplikasi dilengkapi dengan 31 automated test case (Unit & Feature Test) menggunakan PHPUnit:
```bash
php artisan test
```
Semua 31 skenario pengujian mencakup:
- Autentikasi dan redirect role Admin & Kasir.
- CRUD Layanan Laundry.
- Siklus hidup transaksi (Masuk ➔ Proses ➔ Selesai).
- Validasi relasi data transaksi dengan pelanggan & jenis paket.
- Integritas kalkulasi laporan keuangan.

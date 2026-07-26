# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# TaniDirect

## Spesifikasi Teknis:
## Tech Stack, Arsitektur Sistem, Struktur Database, dan Use Case

**Lomba Web Development — VeternityBeraksi 2026**  
**Subtema 3: Rural Commerce & Supply Chain**  
**Studi Kasus: Provinsi Jawa Timur**

---

# 1. Ringkasan Produk

TaniDirect adalah platform rural commerce yang menghubungkan petani tanaman pangan (beras dan jagung) di Provinsi Jawa Timur secara langsung dengan pembeli.

Platform ini memiliki dua fitur inti:

1. **Price Transparency Tracker**
   
   Fitur yang memberikan perbandingan harga produsen dan harga konsumen untuk meningkatkan transparansi rantai distribusi pertanian.

2. **Agregasi Pesanan Komunal (Order Pool)**

   Fitur yang memungkinkan beberapa pembeli menggabungkan kebutuhan pembelian agar dapat memenuhi minimum order petani dan memperoleh harga yang lebih kompetitif.


Dokumen ini berfokus pada spesifikasi teknis sistem yang mencakup:

- pemilihan teknologi
- arsitektur sistem
- struktur database
- Entity Relationship Diagram (ERD)
- use case setiap aktor
- pengolahan data historis harga BPS dan Siskaperbapo

---

# 2. Tech Stack


| Komponen | Teknologi | Alasan |
|---|---|---|
| Backend Framework | Laravel 13 (PHP 8.3+) | Ekosistem matang, Eloquent ORM memudahkan pengelolaan relasi kompleks seperti order pool dan price reference, serta menyediakan authentication dan middleware bawaan |
| Frontend | Blade Templating + Livewire 3 + Alpine.js + Tailwind CSS | Mendukung interaktivitas seperti filter produk, progress order pool, dan form dinamis tanpa membutuhkan REST API terpisah |
| Database | MySQL 8 | Mendukung kebutuhan aplikasi dan terintegrasi dengan Laravel Eloquent ORM |
| Authentication | Laravel Breeze / Fortify + Middleware Role Based | Menyediakan register, login, verifikasi email, serta pengaturan role Produsen, Pembeli, dan Administrator |
| File Storage | Laravel Storage | Digunakan untuk penyimpanan gambar produk dan dokumen verifikasi produsen |
| Version Control | Git + GitHub | Digunakan sebagai repository dan pengelolaan kode sumber |
| Development Environment | Laravel Herd + MySQL | Environment lokal untuk pengembangan aplikasi |
| Deployment | Hosting PHP + MySQL | Mendukung aplikasi agar dapat diakses publik selama proses penjurian |


---

# 3. Arsitektur Sistem


TaniDirect menggunakan pola arsitektur:

```
Model - View - Controller (MVC)
```


Pemisahan sistem:

## Presentation Layer

Menggunakan:

- Blade
- Livewire
- Alpine.js


## Business Logic Layer

Menggunakan:

- Controller
- Service


## Data Layer

Menggunakan:

- Eloquent Model
- MySQL Database


---

# Alur Data Sistem


1. Pengguna mengakses aplikasi melalui browser.

2. Request diterima Laravel melalui routing.

3. Request diproses Controller dan divalidasi Middleware.

4. Business logic memproses data menggunakan Eloquent Model.

5. Data disimpan dan diambil melalui MySQL.

6. Data historis harga BPS dan Siskaperbapo sudah diimpor ke tabel `price_references`.

7. Sistem menampilkan response melalui Blade yang diperkaya Livewire/Alpine.js.


---

# 4. Struktur Database


Database dirancang untuk mendukung:

- manajemen pengguna
- produk pertanian
- transaksi
- order pool
- transparansi harga
- review
- tracking pengiriman


## Tabel Database


| Tabel | Fungsi | Relasi |
|---|---|---|
| users | Menyimpan akun pengguna berdasarkan role (produsen, pembeli, administrator) | 1-1 dengan producer_profiles |
| producer_profiles | Data tambahan produsen seperti lokasi, komoditas, status verifikasi | 1-N ke products |
| products | Menyimpan produk pertanian yang dijual produsen | 1-N ke orders dan order_pools |
| price_references | Menyimpan data harga historis BPS dan Siskaperbapo | Berdasarkan kategori komoditas dan wilayah |
| order_pools | Menyimpan sesi agregasi pesanan komunal | 1-N ke order_pool_members dan orders |
| order_pool_members | Menyimpan pembeli yang bergabung dalam order pool | N-1 ke order_pools dan users |
| orders | Menyimpan transaksi pembelian | 1-1 reviews dan 1-N shipment_status_logs |
| shipment_status_logs | Menyimpan riwayat status pengiriman | N-1 ke orders |
| reviews | Menyimpan rating dan ulasan pembeli terhadap produsen | Terhubung dengan orders, users, producer_profiles |


---

# 5. Price Transparency Tracker


## Tujuan

Memberikan transparansi harga melalui perbandingan:

```
Harga Produsen
        |
        |
Harga Konsumen
```


Data berasal dari:

- BPS
- Siskaperbapo


---

# Struktur Data Harga


Tabel:

```
price_references
```


Memiliki kolom:

| Kolom | Fungsi |
|-|-|
| kategori_komoditas | Jenis komoditas |
| kabupaten_kota | Wilayah harga |
| sumber | bps / siskaperbapo |
| tipe_harga | produsen / konsumen |
| periode | Periode harga |
| harga | Nilai harga |


---

# Contoh Data


| Komoditas | Wilayah | Sumber | Tipe Harga | Periode | Harga |
|-|-|-|-|-|-|
| BERAS MEDIUM | Provinsi Jawa Timur | BPS | Produsen | 2025-07 | 9.667 |
| BERAS MEDIUM | Provinsi Jawa Timur | Siskaperbapo | Konsumen | 2025-07 | 12.829 |


---

# Proses Import Data


Data harga dimasukkan melalui:

```
php artisan db:seed
```


Seeder membaca file:

```
harga_pertanian.csv
harga_konsumen_siskaperbapo.csv
```


Kemudian memasukkan data ke tabel:

```
price_references
```


---

# 6. Use Case Sistem


Terdapat tiga aktor utama:


## 1. Produsen


Fitur:

- Registrasi akun
- Melengkapi profil produsen
- Mengelola produk
- Tambah produk
- Edit produk
- Hapus produk
- Melihat detail order pool
- Mendapatkan notifikasi order pool
- Memperbarui status pengiriman


---

## 2. Pembeli


Fitur:

- Registrasi akun
- Login
- Melihat produk
- Mencari produk pertanian
- Melihat Price Transparency Tracker
- Melakukan pemesanan langsung
- Bergabung dengan order pool
- Melihat status pesanan
- Memberikan rating dan ulasan
- Melihat detail order pool


---

## 3. Administrator


Fitur:

- Verifikasi akun produsen
- Mengelola validasi data harga referensi
- Memastikan data BPS dan Siskaperbapo akurat
- Memantau aktivitas transaksi


---

# 7. Pertimbangan Keamanan


## Validasi Input

Menggunakan Laravel Form Request Validation untuk:

- registrasi
- produk
- checkout
- transaksi


## CSRF Protection

Seluruh form menggunakan middleware CSRF Laravel.


## Password Security

Password disimpan menggunakan hashing bcrypt.


## Role Based Access Control

Memastikan:

- Produsen tidak dapat mengakses administrator.
- Pembeli tidak dapat mengakses fitur produsen.


## SQL Injection Prevention

Menggunakan:

- Eloquent ORM
- Query Builder


## Data Authorization

Produsen hanya dapat mengubah produk miliknya sendiri.

Pembeli hanya dapat mengakses transaksi miliknya sendiri.


---

# 8. Roadmap Pengembangan


## Minggu 1 Hari 1-3

Fokus:

Setup environment, database, authentication, role


Output:

- Laravel berjalan
- Migration selesai
- Login/Register tersedia


---

## Minggu 1 Hari 4-7

Fokus:

Fitur inti:

- Manajemen produk
- Price Transparency Tracker
- Import data BPS dan Siskaperbapo


Output:

- Produsen dapat mengelola produk
- Harga transparansi dapat ditampilkan


---

## Minggu 2 Hari 8-11

Fokus:

- Order Pool
- Checkout
- Simulasi transaksi
- Status pengiriman


Output:

Alur pemesanan end-to-end berjalan.


---

## Minggu 2 Hari 12-14

Fokus:

- Rating
- Review
- UI/UX
- Testing
- Deployment


Output:

Aplikasi siap dipresentasikan.


---

# END OF DOCUMENT

# Dokumen Alur Pengujian — TaniDirect

Checklist pengujian end-to-end yang disusun berdasarkan perjalanan pengguna nyata.
Bisa dijalankan secara manual maupun otomatis (Pest).

---

## TAHAP 1 — Autentikasi

### Registrasi Pembeli
- [ ] 1.1 Buka halaman `/buyer/register`, isi semua field (nama, email, password, kabupaten/kota).
- [ ] 1.2 Submit → redirect ke halaman login pembeli dengan pesan sukses.
- [ ] 1.3 Cek tabel `users`: baris baru ada dengan `role = pembeli`, `kabupaten_kota` sesuai input.

### Registrasi Produsen
- [ ] 1.4 Buka halaman `/producer/register`, isi semua field (nama, email, phone, kabupaten/kota, komoditas utama, password).
- [ ] 1.5 Submit → redirect ke halaman login produsen dengan pesan sukses.
- [ ] 1.6 Cek tabel `users`: baris baru ada dengan `role = produsen`, `kabupaten_kota` sesuai input.
- [ ] 1.7 Cek tabel `producer_profiles`: baris baru ada dengan `user_id` sesuai, `status_verifikasi = menunggu`.

### Registrasi via Livewire (halaman `/register`)
- [ ] 1.8 Pilih role "Pembeli", isi semua field → user tersimpan dengan `role = pembeli`.
- [ ] 1.9 Pilih role "Produsen", isi semua field → user tersimpan dengan `role = produsen` DAN `producer_profiles` terbuat dengan `status_verifikasi = menunggu`.

### Validasi Email
- [ ] 1.10 Semua halaman yang meminta email (register, login, forgot password, update profile) menerima email dari domain apa pun (@gmail.com, @yahoo.com, dll) selama format valid.
- [ ] 1.11 Tidak ada validasi yang membatasi domain tertentu (misal @tanidirect.com).

### Login
- [ ] 1.12 Login pembeli dengan kredensial benar → masuk ke `/buyer/dashboard`.
- [ ] 1.13 Login produsen dengan kredensial benar → masuk ke `/producer/dashboard`.
- [ ] 1.14 Login admin via `/login` → masuk ke `/admin/dashboard`.
- [ ] 1.15 Login dengan password salah → ditolak dengan pesan error.

### Logout
- [ ] 1.16 Klik logout → sesi berakhir, redirect ke landing/login.
- [ ] 1.17 Setelah logout, akses halaman dashboard → redirect ke login (bukan bisa masuk).

### Middleware & Akses
- [ ] 1.18 Pembeli akses `/producer/dashboard` → ditolak/redirect ke dashboard pembeli.
- [ ] 1.19 Produsen akses `/buyer/dashboard` → ditolak/redirect ke dashboard produsen.
- [ ] 1.20 Pembeli/Produsen akses `/admin/dashboard` → ditolak/redirect.
- [ ] 1.21 Guest (belum login) akses halaman yang butuh auth → redirect ke login.

---

## TAHAP 2 — Manajemen Produk (Sisi Produsen)

- [ ] 2.1 Produsen buka `/producer/products/create`, isi form (kategori, nama, harga, stok, satuan, deskripsi) → produk tersimpan di tabel `products` dengan `producer_id` yang sesuai.
- [ ] 2.2 Produsen buka `/producer/products/{id}/edit` untuk produk miliknya → data tampil benar, edit dan simpan → data ter-update.
- [ ] 2.3 **Otorisasi**: Produsen A coba akses `/producer/products/{id}/edit` milik Produsen B → ditolak (403).
- [ ] 2.4 **Otorisasi**: Produsen A coba hapus produk milik Produsen B → ditolak (403).
- [ ] 2.5 Produsen hapus produk tanpa pesanan → berhasil terhapus.
- [ ] 2.6 Produsen hapus produk yang sudah punya pesanan → ditolak dengan pesan error (tidak bisa dihapus).
- [ ] 2.7 Produsen toggle status aktif/nonaktif produk → status berubah sesuai.

---

## TAHAP 3 — Sisi Pembeli: Katalog & Price Tracker

- [ ] 3.1 Pembeli buka dashboard → produk aktif dari semua produsen tampil.
- [ ] 3.2 Produk yang `is_active = false` TIDAK tampil di katalog pembeli.
- [ ] 3.3 Buka halaman detail produk (`/buyer/products/{id}`) → data sesuai database.
- [ ] 3.4 Buka Price Tracker (`/price-tracker`) → data harga ditampilkan dari tabel `price_references` (bukan hardcode).
- [ ] 3.5 Filter komoditas di Price Tracker berfungsi benar.
- [ ] 3.6 Filter periode di Price Tracker berfungsi benar.

---

## TAHAP 4 — Transaksi & Order Pool

### Order Langsung
- [ ] 4.1 Pembeli pesan produk → halaman checkout tampil dengan kalkulasi (subtotal, service fee, grand total) yang benar.
- [ ] 4.2 Setelah pembayaran → order tersimpan di tabel `orders` dengan `status_pengiriman = dipesan`.
- [ ] 4.3 Total harga terhitung benar: `jumlah × harga_jual`.
- [ ] 4.4 Stok produk berkurang sesuai jumlah pesanan.
- [ ] 4.5 `shipment_status_logs` tercatat dengan status awal "dipesan".

### Order Pool (Pembeli)
- [ ] 4.6 Pembeli buka `/order-pool` → daftar order pool tampil.
- [ ] 4.6b Pembeli buka `/order-pool/create` → form buat order pool baru tampil dengan daftar produk aktif dari produsen terverifikasi.
- [ ] 4.6c Pembeli submit form → order pool baru tersimpan di tabel `order_pools` dengan `status = open`, `volume_terkumpul = 0`.
- [ ] 4.7 Pembeli gabung ke order pool → `order_pool_members` tercatat, `volume_terkumpul` bertambah.
- [ ] 4.8 Minimal pembelian 5 kg → jika input < 5, ditolak (validasi server-side).
- [ ] 4.9 Tidak bisa gabung melebihi sisa volume.
- [ ] 4.10 Tidak bisa gabung dua kali ke pool yang sama.
- [ ] 4.11 Saat `volume_terkumpul >= target_volume` → status otomatis berubah jadi `fulfilled`.

### Order Pool (Produsen)
- [ ] 4.16 Produsen buka `/producer/order-pools` → hanya order pool dari produk miliknya yang tampil.
- [ ] 4.17 Produsen buka `/producer/order-pools/{id}` → detail pool tampil: info produk, daftar member, daftar pesanan terkait.
- [ ] 4.18 **Otorisasi**: Produsen A tidak bisa lihat detail order pool dari produk milik Produsen B (403).

### Order Pool (Admin)
- [ ] 4.19 Admin buka `/admin/order-pools` → semua order pool dari seluruh produsen tampil.
- [ ] 4.20 Admin buka `/admin/order-pools/{id}` → detail pool tampil: info produk, produsen, member, pesanan terkait.
- [ ] 4.21 Admin bisa lihat order pool terbaru langsung di dashboard admin.

### Status Pengiriman
- [ ] 4.12 **Produsen** bisa ubah: `dipesan → diproses → dikirim` (3 status awal).
- [ ] 4.13 **Produsen TIDAK BISA** ubah status menjadi `diterima`.
- [ ] 4.14 **Pembeli** yang mengonfirmasi status akhir: `dikirim → diterima`.
- [ ] 4.15 Setiap perubahan status tercatat di `shipment_status_logs` dengan `diperbarui_pada`.

---

## TAHAP 5 — Rating & Ulasan

- [ ] 5.1 Pembeli bisa memberi rating (1-5 bintang) setelah status pesanan = `diterima`.
- [ ] 5.2 Rating tersimpan di tabel `reviews` (bukan dummy/hardcode).
- [ ] 5.3 Query `SELECT * FROM reviews` menunjukkan baris baru setelah submit rating.
- [ ] 5.4 Halaman detail produk menampilkan rating agregat (rata-rata) dari data `reviews` yang asli.
- [ ] 5.5 Rating hanya bisa diberikan sekali per order.
- [ ] 5.6 Rating hanya bisa diberikan oleh pembeli pemilik order (bukan orang lain).
- [ ] 5.7 Rating TIDAK bisa diberikan jika status pesanan belum `diterima`.

---

## TAHAP 6 — Panel Administrator

- [ ] 6.1 Login sebagai Admin → dashboard admin bisa diakses.
- [ ] 6.2 Dashboard menampilkan statistik: total pembeli, produsen, pesanan, order pool, omzet transaksi.
- [ ] 6.2b Dashboard menampilkan tabel transaksi terbaru (5 pesanan terakhir).
- [ ] 6.2c Dashboard menampilkan daftar order pool terbaru dengan link ke detail.
- [ ] 6.3 Admin buka daftar produsen → semua produsen dengan status verifikasi tampil.
- [ ] 6.4 Admin verifikasi produsen → `status_verifikasi` berubah menjadi `terverifikasi`.
- [ ] 6.5 Non-admin tidak bisa akses `/admin/*` → redirect ke dashboard sesuai role (bukan 403).

---

## Catatan

- Semua pengujian otomatis menggunakan Pest dan bisa dijalankan dengan: `php artisan test`
- File test: `tests/Feature/TanidirectFullAuditTest.php`

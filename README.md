# TaniDirect

Platform rural commerce yang menghubungkan petani tanaman pangan Jawa Timur langsung dengan pembeli, mengurangi rantai distribusi dan meningkatkan transparansi harga. Dibangun untuk kompetisi **VeternityBeraksi 2026**.

## Tech Stack

- **Backend:** PHP 8.4, Laravel 13, Livewire 3 / Volt
- **Frontend:** Tailwind CSS 3, Alpine.js
- **Database:** MySQL
- **Payment:** Midtrans (Snap)
- **AI Chatbot:** Google Gemini API
- **Testing:** Pest 4

## Fitur Utama

- **Multi-role Authentication** — Registrasi dan login terpisah untuk Pembeli, Produsen (Petani), dan Admin
- **Manajemen Produk** — Produsen bisa CRUD produk pertanian (beras medium/premium, jagung) dengan gambar dan kontrol stok
- **Price Transparency Tracker** — Perbandingan harga produsen vs konsumen berdasarkan data BPS dan Siskaperbapo
- **Order Pool (Patungan)** — Pembelian kolektif untuk mencapai volume target dan mendapatkan harga lebih baik
- **Checkout & Payment** — Integrasi Midtrans Snap dengan kalkulasi service fee otomatis
- **Tracking Pengiriman** — Timeline status pesanan (dipesan, diproses, dikirim, diterima) dengan log riwayat
- **Rating & Ulasan** — Pembeli memberi rating setelah barang diterima; agregat rating tampil di katalog
- **Panel Admin** — Dashboard statistik, verifikasi akun produsen, monitoring transaksi
- **Chatbot Bertani** — Asisten AI berbasis Gemini untuk tanya jawab seputar produk dan platform

## Instalasi

```bash
git clone https://github.com/your-repo/tanidirect.git
cd tanidirect

composer install
npm install

cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu:
php artisan migrate --seed

npm run build
php artisan serve
```

### Environment Variables

Tambahkan di `.env` untuk fitur payment dan chatbot:

```
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

GEMINI_API_KEY=your_gemini_api_key
```

## Testing

```bash
php artisan test
```

## Struktur Role

| Role | Akses |
|------|-------|
| **Pembeli** | Dashboard, katalog produk, order, tracking, review, order pool, price tracker |
| **Produsen** | Dashboard, CRUD produk, manajemen pesanan, update status pengiriman |
| **Admin** | Dashboard statistik, verifikasi produsen, monitoring transaksi |

## Tim

Dikembangkan oleh tim VeternityBeraksi 2026.

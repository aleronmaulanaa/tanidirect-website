# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# Bertani - Intelligent Agricultural Assistant
# TaniDirect


## 1. Feature Overview

## Nama Fitur

Bertani


## Deskripsi

Bertani adalah fitur chatbot berbasis AI yang terintegrasi pada platform TaniDirect.

Bertani berfungsi sebagai asisten digital pertanian yang membantu seluruh pengguna TaniDirect dalam memahami platform, menemukan produk pertanian, mendapatkan informasi harga, memahami sistem Order Pool, serta membantu aktivitas perdagangan antara petani dan pembeli.


Bertani bukan hanya chatbot customer service, tetapi menjadi intelligent assistant yang terhubung dengan ekosistem data TaniDirect.


Positioning:

"Digital Agricultural Assistant untuk menciptakan rantai pasok pertanian yang lebih transparan, mudah, dan efisien."


---

# 2. Tujuan Fitur


## Tujuan Utama

Meningkatkan pengalaman pengguna dengan menyediakan bantuan interaktif yang cepat, mudah dipahami, dan relevan terhadap kebutuhan pengguna pertanian.


## Tujuan Bisnis


1. Membantu pengunjung memahami konsep TaniDirect.

2. Membantu pembeli menemukan produk pertanian yang sesuai.

3. Memberikan informasi transparansi harga hasil pertanian.

4. Membantu pengguna memahami sistem pembelian komunal (Order Pool).

5. Membantu petani menggunakan fitur penjualan pada platform.


---

# 3. Konsep Implementasi


Bertani dibuat dalam bentuk:

## Floating Bubble Chat


Chatbot muncul sebagai tombol melayang pada bagian kanan bawah website.


Contoh:


```
Website


                            🌱
                         Bertani


```


Ketika tombol ditekan, muncul jendela percakapan.


Contoh:


```
--------------------------------

🌱 Bertani

Halo, saya Bertani.

Saya dapat membantu Anda:

[ Cari Produk Pertanian ]

[ Cek Harga Pasar ]

[ Pelajari Order Pool ]

[ Bantuan TaniDirect ]

--------------------------------
```


---

# 4. Lokasi Implementasi


Bertani merupakan reusable component yang dapat digunakan pada berbagai halaman.


Implementasi utama:


## 1. Landing Page

Tujuan:

Membantu visitor memahami TaniDirect.


Kemampuan:


- Menjelaskan apa itu TaniDirect.
- Menjelaskan manfaat platform.
- Menjelaskan cara menjadi pembeli.
- Menjelaskan cara menjadi petani.
- Menjelaskan fitur Order Pool.


Contoh:


User:

"Apa itu TaniDirect?"


Bertani:


"TaniDirect adalah platform yang menghubungkan petani langsung dengan pembeli.

Melalui TaniDirect, petani mendapatkan akses pasar yang lebih luas dan pembeli mendapatkan harga yang lebih transparan."


---


## 2. Buyer Dashboard


Tujuan:

Menjadi asisten belanja bagi pembeli.


Kemampuan:


### Product Recommendation


User:

"Saya ingin membeli beras"


Bertani mencari data:


Database:

products


Response:


"Saya menemukan beberapa produk:

Beras Premium

Harga:
Rp14.000/kg

Petani:
Kelompok Tani Makmur

Lokasi:
Sidoarjo"


---


### Product Search


User:

"Ada jagung?"


Bertani melakukan pencarian:

products.nama_produk


dan menampilkan produk yang sesuai.


---


## 3. Product Detail


Tujuan:

Membantu pembeli mengambil keputusan.


Kemampuan:


### Price Comparison


User:

"Apakah harga ini mahal?"


Bertani membandingkan:


Harga Produk

VS

Price Reference


Menggunakan:


products

dan

price_references



Response:


"Harga produk ini masih dalam kategori wajar berdasarkan referensi harga pasar."


---


## 4. Order Pool


Tujuan:

Memberikan edukasi mengenai sistem patungan.


User:


"Apa itu Order Pool?"


Response:


"Order Pool adalah sistem pembelian bersama dimana beberapa pembeli menggabungkan kebutuhan agar mencapai minimum order petani."


Contoh:


Pembeli A:
10kg


Pembeli B:
10kg


Pembeli C:
10kg


Total:
30kg


Pesanan dapat diproses.


---


## 5. Producer Dashboard


Tujuan:

Membantu petani menggunakan platform.


Kemampuan:


- Cara menambah produk.
- Cara mengubah stok.
- Cara melihat pesanan.
- Cara menggunakan Order Pool.
- Panduan penggunaan dashboard.


---

# 5. Integrasi Data


Bertani menggunakan data asli dari database TaniDirect.


Tidak menggunakan dummy data.


## Product Data


Menggunakan:


products


Data:


- nama_produk
- kategori
- harga_jual
- stok
- deskripsi
- gambar


Relasi:


Product

belongsTo

ProducerProfile



---


## Producer Data


Menggunakan:


producer_profiles


Data:


- lokasi_desa
- kabupaten_kota
- komoditas_utama


---


## Price Transparency Data


Menggunakan:


price_references


Data:


- kategori_komoditas
- kabupaten_kota
- tipe_harga
- periode
- harga


Digunakan untuk:


- perbandingan harga produsen
- informasi harga pasar


---


## Order Pool Data


Menggunakan:


order_pools


Data:


- target_volume
- volume_terkumpul
- status
- batas_waktu



---

# 6. Arsitektur Sistem


```
User

 |

Bubble Chat Interface

 |

Bertani Controller

 |

Intent Detection

 |

Business Logic

 |

Database TaniDirect

 |

Response

```


---

# 7. Model Chatbot


Bertani menggunakan konsep Hybrid AI Assistant.


## 1. Knowledge Base


Digunakan untuk:

- FAQ
- informasi TaniDirect
- panduan penggunaan


Contoh:


"Bagaimana cara menjadi petani?"

"Bagaimana cara membeli produk?"

"Apa itu Order Pool?"



---


## 2. Database Intelligence


Digunakan untuk:


- mencari produk
- mencari harga
- membaca informasi petani


---


## 3. AI Response Generation


Digunakan untuk:


- memahami pertanyaan pengguna
- memberikan jawaban natural
- membuat interaksi lebih manusiawi



---

# 8. User Interface Requirement


Design mengikuti identitas TaniDirect:


Style:

- modern
- clean
- agriculture theme
- friendly


Komponen:


## Floating Button


Bentuk:

rounded circle


Isi:

```
🌱
```


Label:

```
Bertani
```


---


## Chat Window


Memiliki:


- Header chatbot
- Area percakapan
- Input pertanyaan
- Quick action button


Quick Action:


```
Cari Produk

Cek Harga

Order Pool

Bantuan
```



---

# 9. Development Structure


Component:


```
resources/views/components/chatbot/

    bertani.blade.php

```


Controller:


```
app/Http/Controllers/

    BertaniController.php

```


Route:


```
/chatbot/message
```


---

# 10. Development Priority


## Phase 1

Membuat bubble chat UI.


Fitur:

- floating button
- chat window
- input message
- response system sederhana



---


## Phase 2

Integrasi FAQ TaniDirect.


---


## Phase 3

Integrasi database:


- products
- price_references
- order_pools


---


## Phase 4

AI Enhancement:


- natural language processing
- recommendation
- intelligent response



---

# 11. Success Indicator


Bertani dianggap berhasil apabila:


1. User dapat memahami TaniDirect dengan lebih cepat.

2. Buyer dapat menemukan produk melalui chatbot.

3. Buyer dapat memahami harga produk dibandingkan harga pasar.

4. Buyer memahami konsep Order Pool.

5. Producer mendapatkan bantuan penggunaan platform.


---

# Final Statement


Bertani merupakan inovasi AI assistant pada TaniDirect yang membantu membangun ekosistem perdagangan pertanian digital yang lebih transparan, mudah digunakan, dan berkelanjutan.


Bertani bukan hanya chatbot, tetapi penghubung antara teknologi, petani, dan pembeli dalam menciptakan rantai pasok pertanian yang lebih adil.
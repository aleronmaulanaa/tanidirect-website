<?php

use App\Models\Order;
use App\Models\OrderPool;
use App\Models\OrderPoolMember;
use App\Models\PriceReference;
use App\Models\ProducerProfile;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

// ═══════════════════════════════════════════════════════════════
// TAHAP 1 — AUTENTIKASI
// ═══════════════════════════════════════════════════════════════

test('1.1 registrasi pembeli menyimpan data lengkap ke tabel users', function () {
    $response = $this->post(route('buyer.register.process'), [
        'name' => 'Budi Pembeli',
        'email' => 'budi@gmail.com',
        'password' => 'rahasia123',
        'phone' => '081234567890',
        'kabupaten_kota' => 'Kabupaten Malang',
    ]);

    $response->assertRedirect(route('buyer.login'));

    $this->assertDatabaseHas('users', [
        'email' => 'budi@gmail.com',
        'role' => 'pembeli',
        'kabupaten_kota' => 'Kabupaten Malang',
        'phone' => '081234567890',
    ]);
});

test('1.4 registrasi produsen menyimpan user dan producer_profiles dengan status menunggu', function () {
    $response = $this->post(route('producer.register.process'), [
        'name' => 'Pak Tani',
        'email' => 'paktani@yahoo.com',
        'phone' => '081234567891',
        'kabupaten_kota' => 'Kabupaten Jombang',
        'komoditas_utama' => 'beras',
        'password' => 'rahasia123',
    ]);

    $response->assertRedirect(route('producer.login'));

    $this->assertDatabaseHas('users', [
        'email' => 'paktani@yahoo.com',
        'role' => 'produsen',
        'kabupaten_kota' => 'Kabupaten Jombang',
    ]);

    $user = User::where('email', 'paktani@yahoo.com')->first();

    $this->assertDatabaseHas('producer_profiles', [
        'user_id' => $user->id,
        'status_verifikasi' => 'menunggu',
    ]);
});

test('1.10 login menerima email dari domain apapun', function () {
    $user = User::factory()->create([
        'role' => 'pembeli',
        'email' => 'test@customdomain.co.id',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('buyer.login.process'), [
        'email' => 'test@customdomain.co.id',
        'password' => 'password',
    ])->assertRedirect(route('buyer.dashboard'));
});

test('1.11 registrasi pembeli menerima email gmail, yahoo, dll', function () {
    foreach (['user@gmail.com', 'user2@yahoo.co.id', 'user3@hotmail.com'] as $email) {
        $this->post(route('buyer.register.process'), [
            'name' => 'Test',
            'email' => $email,
            'password' => 'rahasia123',
            'phone' => '08123456',
            'kabupaten_kota' => 'Surabaya',
        ])->assertRedirect(route('buyer.login'));

        $this->assertDatabaseHas('users', ['email' => $email]);
    }
});

test('1.12 login pembeli berhasil masuk ke dashboard pembeli', function () {
    $user = User::factory()->create([
        'role' => 'pembeli',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('buyer.login.process'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('buyer.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('1.13 login produsen berhasil masuk ke dashboard produsen', function () {
    $user = User::factory()->create([
        'role' => 'produsen',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('producer.login.process'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('producer.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('1.15 login dengan password salah ditolak', function () {
    $user = User::factory()->create([
        'role' => 'pembeli',
        'password' => bcrypt('password'),
    ]);

    $this->post(route('buyer.login.process'), [
        'email' => $user->email,
        'password' => 'salah',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('1.16 logout mengakhiri sesi', function () {
    $user = User::factory()->create(['role' => 'pembeli']);

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect('/');

    $this->assertGuest();
});

test('1.17 setelah logout tidak bisa akses dashboard', function () {
    $user = User::factory()->create(['role' => 'pembeli']);

    $this->actingAs($user)->post(route('logout'));

    $this->get(route('buyer.dashboard'))
        ->assertRedirect(route('buyer.login'));
});

test('1.18 pembeli tidak bisa akses halaman produsen', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);

    $this->actingAs($buyer)
        ->get(route('producer.dashboard'))
        ->assertRedirect(route('buyer.dashboard'));
});

test('1.19 produsen tidak bisa akses halaman pembeli', function () {
    $producer = User::factory()->create(['role' => 'produsen']);

    $this->actingAs($producer)
        ->get(route('buyer.dashboard'))
        ->assertRedirect(route('producer.dashboard'));
});

test('1.20 pembeli tidak bisa akses halaman admin', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);

    $this->actingAs($buyer)
        ->get(route('admin.dashboard'))
        ->assertRedirect(route('buyer.dashboard'));
});

test('1.21 guest redirect ke login saat akses halaman auth', function () {
    $this->get(route('buyer.dashboard'))
        ->assertRedirect(route('buyer.login'));
});

// ═══════════════════════════════════════════════════════════════
// TAHAP 2 — MANAJEMEN PRODUK (SISI PRODUSEN)
// ═══════════════════════════════════════════════════════════════

test('2.1 produsen bisa tambah produk baru', function () {
    $profile = ProducerProfile::factory()->create();

    $this->actingAs($profile->user)
        ->post(route('producer.products.store'), [
            'nama_produk' => 'Beras Organik',
            'kategori' => 'beras_premium',
            'harga_jual' => 15000,
            'stok' => 100,
            'satuan' => 'kg',
            'deskripsi' => 'Beras organik berkualitas',
            'is_active' => true,
        ])
        ->assertRedirect(route('producer.products.index'));

    $this->assertDatabaseHas('products', [
        'producer_id' => $profile->id,
        'nama_produk' => 'Beras Organik',
        'kategori' => 'beras_premium',
    ]);
});

test('2.2 produsen bisa edit produk miliknya', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);

    $this->actingAs($profile->user)
        ->put(route('producer.products.update', $product), [
            'nama_produk' => 'Beras Super',
            'kategori' => $product->kategori,
            'harga_jual' => 20000,
            'stok' => 50,
            'satuan' => 'kg',
            'is_active' => true,
        ])
        ->assertRedirect(route('producer.products.index'));

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'nama_produk' => 'Beras Super',
        'harga_jual' => 20000,
    ]);
});

test('2.3 produsen A tidak bisa edit produk milik produsen B', function () {
    $profileA = ProducerProfile::factory()->create();
    $profileB = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profileB->id]);

    $this->actingAs($profileA->user)
        ->get(route('producer.products.edit', $product))
        ->assertForbidden();

    $this->actingAs($profileA->user)
        ->put(route('producer.products.update', $product), [
            'nama_produk' => 'Dicuri',
            'kategori' => 'jagung',
            'harga_jual' => 1,
            'stok' => 1,
            'satuan' => 'kg',
            'is_active' => true,
        ])
        ->assertForbidden();
});

test('2.4 produsen A tidak bisa hapus produk milik produsen B', function () {
    $profileA = ProducerProfile::factory()->create();
    $profileB = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profileB->id]);

    $this->actingAs($profileA->user)
        ->delete(route('producer.products.destroy', $product))
        ->assertForbidden();
});

test('2.5 produsen bisa hapus produk tanpa pesanan', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);

    $this->actingAs($profile->user)
        ->delete(route('producer.products.destroy', $product))
        ->assertRedirect();

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('2.6 produsen tidak bisa hapus produk yang sudah punya pesanan', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    Order::factory()->create(['product_id' => $product->id]);

    $this->actingAs($profile->user)
        ->delete(route('producer.products.destroy', $product))
        ->assertRedirect();

    $this->assertDatabaseHas('products', ['id' => $product->id]);
});

test('2.7 produsen bisa toggle status aktif produk', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create([
        'producer_id' => $profile->id,
        'is_active' => true,
    ]);

    $this->actingAs($profile->user)
        ->patch(route('producer.products.status', $product))
        ->assertRedirect();

    expect($product->fresh()->is_active)->toBeFalse();
});

// ═══════════════════════════════════════════════════════════════
// TAHAP 3 — KATALOG & PRICE TRACKER
// ═══════════════════════════════════════════════════════════════

test('3.1 dashboard pembeli menampilkan produk aktif', function () {
    $profile = ProducerProfile::factory()->create();
    $activeProduct = Product::factory()->create([
        'producer_id' => $profile->id,
        'is_active' => true,
    ]);
    $inactiveProduct = Product::factory()->create([
        'producer_id' => $profile->id,
        'is_active' => false,
    ]);

    $buyer = User::factory()->create(['role' => 'pembeli']);

    $response = $this->actingAs($buyer)->get(route('buyer.dashboard'));
    $response->assertOk();
    $response->assertSee($activeProduct->nama_produk);
    $response->assertDontSee($inactiveProduct->nama_produk);
});

test('3.3 halaman detail produk menampilkan data sesuai database', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create([
        'producer_id' => $profile->id,
        'is_active' => true,
        'nama_produk' => 'Beras Malang Premium',
        'harga_jual' => 14500,
    ]);

    $buyer = User::factory()->create(['role' => 'pembeli']);

    $response = $this->actingAs($buyer)->get(route('buyer.products.show', $product));
    $response->assertOk();
    $response->assertSee('Beras Malang Premium');
    $response->assertSee('14.500');
});

test('3.4 price tracker menampilkan data dari tabel price_references', function () {
    PriceReference::factory()->create([
        'kategori_komoditas' => 'BERAS MEDIUM',
        'sumber' => 'bps',
        'tipe_harga' => 'produsen',
        'periode' => '2026-07',
        'harga' => 9500,
    ]);
    PriceReference::factory()->create([
        'kategori_komoditas' => 'BERAS MEDIUM',
        'sumber' => 'siskaperbapo',
        'tipe_harga' => 'konsumen',
        'periode' => '2026-07',
        'harga' => 13000,
    ]);

    $user = User::factory()->create(['role' => 'pembeli']);

    $response = $this->actingAs($user)->get(route('price-tracker'));
    $response->assertOk();
    $response->assertSee('BERAS MEDIUM');
});

// ═══════════════════════════════════════════════════════════════
// TAHAP 4 — TRANSAKSI & ORDER POOL
// ═══════════════════════════════════════════════════════════════

test('4.7 pembeli gabung ke order pool menambah volume_terkumpul', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $pool = OrderPool::factory()->create([
        'target_volume' => 100,
        'volume_terkumpul' => 10,
        'status' => 'open',
    ]);

    $this->actingAs($buyer)
        ->post(route('order-pool.join', $pool), ['jumlah' => 20])
        ->assertRedirect();

    expect($pool->fresh()->volume_terkumpul)->toBe(30);

    $this->assertDatabaseHas('order_pool_members', [
        'order_pool_id' => $pool->id,
        'buyer_id' => $buyer->id,
        'jumlah' => 20,
    ]);
});

test('4.8 minimal pembelian order pool 5 kg', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $pool = OrderPool::factory()->create([
        'target_volume' => 100,
        'volume_terkumpul' => 10,
        'status' => 'open',
    ]);

    $this->actingAs($buyer)
        ->post(route('order-pool.join', $pool), ['jumlah' => 3])
        ->assertSessionHasErrors('jumlah');

    expect($pool->fresh()->volume_terkumpul)->toBe(10);
});

test('4.10 tidak bisa gabung order pool dua kali', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $pool = OrderPool::factory()->create([
        'target_volume' => 100,
        'volume_terkumpul' => 10,
        'status' => 'open',
    ]);

    OrderPoolMember::create([
        'order_pool_id' => $pool->id,
        'buyer_id' => $buyer->id,
        'jumlah' => 10,
    ]);

    $this->actingAs($buyer)
        ->post(route('order-pool.join', $pool), ['jumlah' => 10])
        ->assertSessionHas('error');
});

test('4.11 order pool fulfilled saat target tercapai', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $pool = OrderPool::factory()->create([
        'target_volume' => 50,
        'volume_terkumpul' => 45,
        'status' => 'open',
    ]);

    $this->actingAs($buyer)
        ->post(route('order-pool.join', $pool), ['jumlah' => 5])
        ->assertRedirect();

    expect($pool->fresh()->status)->toBe('fulfilled');
});

test('4.12 produsen bisa ubah status dipesan ke diproses ke dikirim', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'dipesan',
    ]);

    $this->actingAs($profile->user)
        ->put(route('producer.orders.updateStatus', $order))
        ->assertRedirect();
    expect($order->fresh()->status_pengiriman)->toBe('diproses');

    $this->actingAs($profile->user)
        ->put(route('producer.orders.updateStatus', $order))
        ->assertRedirect();
    expect($order->fresh()->status_pengiriman)->toBe('dikirim');
});

test('4.13 produsen TIDAK BISA ubah status menjadi diterima', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'dikirim',
    ]);

    $this->actingAs($profile->user)
        ->put(route('producer.orders.updateStatus', $order))
        ->assertSessionHas('error');

    expect($order->fresh()->status_pengiriman)->toBe('dikirim');
});

test('4.14 pembeli bisa konfirmasi penerimaan (dikirim ke diterima)', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'dikirim',
    ]);

    $this->actingAs($buyer)
        ->patch(route('buyer.orders.confirmReceived', $order))
        ->assertRedirect();

    expect($order->fresh()->status_pengiriman)->toBe('diterima');
});

test('4.14b pembeli tidak bisa konfirmasi jika bukan pemilik order', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $otherBuyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'dikirim',
    ]);

    $this->actingAs($otherBuyer)
        ->patch(route('buyer.orders.confirmReceived', $order))
        ->assertForbidden();

    expect($order->fresh()->status_pengiriman)->toBe('dikirim');
});

test('4.14c pembeli tidak bisa konfirmasi jika status bukan dikirim', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'diproses',
    ]);

    $this->actingAs($buyer)
        ->patch(route('buyer.orders.confirmReceived', $order))
        ->assertSessionHas('error');

    expect($order->fresh()->status_pengiriman)->toBe('diproses');
});

test('4.15 setiap perubahan status tercatat di shipment_status_logs', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'dipesan',
    ]);

    $this->actingAs($profile->user)
        ->put(route('producer.orders.updateStatus', $order));

    $this->assertDatabaseHas('shipment_status_logs', [
        'order_id' => $order->id,
        'status' => 'diproses',
    ]);

    $this->actingAs($profile->user)
        ->put(route('producer.orders.updateStatus', $order));

    $this->assertDatabaseHas('shipment_status_logs', [
        'order_id' => $order->id,
        'status' => 'dikirim',
    ]);

    $this->actingAs($buyer)
        ->patch(route('buyer.orders.confirmReceived', $order));

    $this->assertDatabaseHas('shipment_status_logs', [
        'order_id' => $order->id,
        'status' => 'diterima',
    ]);
});

// ═══════════════════════════════════════════════════════════════
// TAHAP 5 — RATING & ULASAN
// ═══════════════════════════════════════════════════════════════

test('5.1 pembeli bisa beri rating setelah status diterima', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'diterima',
    ]);

    $this->actingAs($buyer)
        ->post(route('buyer.orders.review', $order), [
            'rating' => 5,
            'komentar' => 'Kualitas bagus!',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('reviews', [
        'order_id' => $order->id,
        'buyer_id' => $buyer->id,
        'rating' => 5,
        'komentar' => 'Kualitas bagus!',
    ]);
});

test('5.4 halaman detail produk menampilkan rating agregat dari database', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create([
        'producer_id' => $profile->id,
        'is_active' => true,
    ]);
    $buyer = User::factory()->create(['role' => 'pembeli']);

    $order1 = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'diterima',
    ]);
    Review::factory()->create([
        'order_id' => $order1->id,
        'buyer_id' => $buyer->id,
        'producer_id' => $profile->id,
        'rating' => 4,
    ]);

    $buyer2 = User::factory()->create(['role' => 'pembeli']);
    $order2 = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer2->id,
        'status_pengiriman' => 'diterima',
    ]);
    Review::factory()->create([
        'order_id' => $order2->id,
        'buyer_id' => $buyer2->id,
        'producer_id' => $profile->id,
        'rating' => 5,
    ]);

    expect($product->fresh()->average_rating)->toBe(4.5);
    expect($product->fresh()->total_reviews)->toBe(2);

    $response = $this->actingAs($buyer)->get(route('buyer.products.show', $product));
    $response->assertOk();
    $response->assertSee('4.5');
    $response->assertSee('2 Ulasan');
});

test('5.5 rating hanya bisa diberikan sekali per order', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'diterima',
    ]);

    Review::factory()->create([
        'order_id' => $order->id,
        'buyer_id' => $buyer->id,
        'producer_id' => $profile->id,
    ]);

    $this->actingAs($buyer)
        ->post(route('buyer.orders.review', $order), [
            'rating' => 3,
            'komentar' => 'Duplikat',
        ])
        ->assertSessionHas('error');

    expect(Review::where('order_id', $order->id)->count())->toBe(1);
});

test('5.6 pembeli lain tidak bisa beri rating pada order orang lain', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $otherBuyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'diterima',
    ]);

    $this->actingAs($otherBuyer)
        ->post(route('buyer.orders.review', $order), [
            'rating' => 1,
            'komentar' => 'Bukan punya saya',
        ])
        ->assertForbidden();
});

test('5.7 rating tidak bisa diberikan jika status belum diterima', function () {
    $profile = ProducerProfile::factory()->create();
    $product = Product::factory()->create(['producer_id' => $profile->id]);
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $order = Order::factory()->create([
        'product_id' => $product->id,
        'buyer_id' => $buyer->id,
        'status_pengiriman' => 'dikirim',
    ]);

    $this->actingAs($buyer)
        ->post(route('buyer.orders.review', $order), [
            'rating' => 5,
        ])
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('reviews', ['order_id' => $order->id]);
});

// ═══════════════════════════════════════════════════════════════
// TAHAP 6 — PANEL ADMINISTRATOR
// ═══════════════════════════════════════════════════════════════

test('6.1 admin bisa akses dashboard admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('6.3 admin bisa lihat daftar produsen', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $producer = User::factory()->create(['role' => 'produsen', 'name' => 'Petani Hebat']);
    ProducerProfile::factory()->create([
        'user_id' => $producer->id,
        'status_verifikasi' => 'menunggu',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.producers.index'));
    $response->assertOk();
    $response->assertSee('Petani Hebat');
});

test('6.4 admin bisa verifikasi produsen', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $producer = User::factory()->create(['role' => 'produsen']);
    $profile = ProducerProfile::factory()->create([
        'user_id' => $producer->id,
        'status_verifikasi' => 'menunggu',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.producers.verify', $producer))
        ->assertRedirect(route('admin.producers.index'));

    expect($profile->fresh()->status_verifikasi)->toBe('terverifikasi');
});

test('6.5 non-admin tidak bisa akses halaman admin', function () {
    $buyer = User::factory()->create(['role' => 'pembeli']);
    $producer = User::factory()->create(['role' => 'produsen']);

    $this->actingAs($buyer)->get(route('admin.dashboard'))
        ->assertRedirect(route('buyer.dashboard'));

    $this->actingAs($producer)->get(route('admin.dashboard'))
        ->assertRedirect(route('producer.dashboard'));
});

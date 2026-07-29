<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F8FAF5] text-gray-900">
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-8">
            <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto">
                <span class="hidden text-sm font-semibold text-gray-600 sm:inline">Pembayaran Simulasi</span>
            </a>
            <a href="{{ route('buyer.products.show', $product) }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-green-700 hover:bg-green-50">← Batal</a>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-5 py-10 sm:px-8">
        <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-green-50 px-8 py-8">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-200 text-lg">🧪</span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-green-700">Pembayaran Simulasi</h1>
                        <p class="mt-1 text-sm text-gray-600">Konfirmasi pesanan Anda. Pembayaran akan disimulasikan secara otomatis.</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                {{-- Ringkasan Pesanan --}}
                <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Produk</p>
                            <p class="mt-2 text-lg font-bold text-gray-900">{{ $product->nama_produk }}</p>
                        </div>
                        <p class="text-sm font-semibold text-green-700">{{ $quantity }} {{ $product->satuan }}</p>
                    </div>

                    <div class="mt-6 grid gap-4 rounded-3xl bg-white p-6 shadow-sm">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Harga per kg</span>
                            <span>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Biaya layanan</span>
                            <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-4 text-lg font-bold text-gray-900">
                            <span>Total Bayar</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Metode Pembayaran (Simulasi) --}}
                <div class="mt-8 grid gap-4">
                    <div class="rounded-3xl bg-white p-6 shadow-sm">
                        <h2 class="font-bold text-gray-900">Metode Pembayaran</h2>
                        <div class="mt-4 rounded-3xl border-2 border-green-200 bg-green-50 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-200 text-sm">✓</span>
                                <div>
                                    <p class="font-semibold text-green-800">Simulasi Pembayaran</p>
                                    <p class="mt-0.5 text-sm text-green-600">Pesanan akan otomatis tercatat sebagai lunas.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Konfirmasi --}}
                    <div class="rounded-3xl bg-white p-6 shadow-sm">
                        <form action="{{ route('buyer.orders.store', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="jumlah" value="{{ $quantity }}">

                            <button type="submit"
                                    class="w-full rounded-3xl bg-green-700 px-6 py-4 text-lg font-bold text-white transition hover:bg-green-800 active:scale-[0.98]"
                                    onclick="return confirm('Konfirmasi pesanan {{ $quantity }} {{ $product->satuan }} {{ $product->nama_produk }}?')">
                                ✅ KONFIRMASI PESANAN
                            </button>
                        </form>
                        <p class="mt-3 text-center text-sm text-gray-500">Pesanan akan langsung masuk ke sistem. Produsen akan segera memprosesnya.</p>
                    </div>
                </div>

                {{-- ================================================================
                     MIDTRANS SNAP CHECKOUT (ASLI) — dinonaktifkan karena simulasi.
                     Aktifkan kembali jika ingin integrasi payment gateway sungguhan.
                     ================================================================ --}}
                {{--
                <form id="payment-form" action="{{ route('buyer.orders.store', $product) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="jumlah" value="{{ $quantity }}">
                    <input type="hidden" name="midtrans_order_id" id="midtrans_order_id">
                    <input type="hidden" name="midtrans_transaction_id" id="midtrans_transaction_id">
                    <input type="hidden" name="midtrans_payment_type" id="midtrans_payment_type">
                    <input type="hidden" name="midtrans_payment_status" id="midtrans_payment_status">
                </form>

                <script src="{{ filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                <script>
                    function fillMidtransResult(result) {
                        console.log('Midtrans result:', result);
                        document.getElementById('midtrans_order_id').value = result.order_id || result.orderId || '';
                        document.getElementById('midtrans_transaction_id').value = result.transaction_id || result.transactionId || '';
                        document.getElementById('midtrans_payment_type').value = result.payment_type || result.paymentType || '';
                        document.getElementById('midtrans_payment_status').value = result.transaction_status || result.transactionStatus || result.status || '';
                    }

                    document.getElementById('pay-button').addEventListener('click', function (event) {
                        event.preventDefault();
                        snap.pay('{{ $snapToken }}', {
                            onSuccess: function(result){
                                fillMidtransResult(result);
                                document.getElementById('payment-form').submit();
                            },
                            onPending: function(result){
                                fillMidtransResult(result);
                                document.getElementById('payment-form').submit();
                            },
                            onError: function(result){
                                console.error('Midtrans error result:', result);
                                alert('Pembayaran gagal. Silakan coba lagi.');
                            },
                            onClose: function(){
                                alert('Pembayaran dibatalkan.');
                            }
                        });
                    });
                </script>
                --}}
            </div>
        </section>
    </main>
</body>
</html>

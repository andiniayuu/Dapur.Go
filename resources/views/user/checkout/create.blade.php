@extends('layouts.app')

@section('title', 'Checkout - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <div class="section-heading">
            <p class="eyebrow">COMPLETE YOUR ORDER</p>
            <h2>Checkout Pesanan</h2>
            <p>Lengkapi informasi untuk pemesanan catering Anda.</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.checkout.store') }}"
              method="POST">

            @csrf

            <div class="checkout-layout">

                <!-- Form informasi pelanggan -->
                <div class="checkout-form">

                    <div class="checkout-card">

                        <h3>Informasi Pelanggan</h3>

                        <div class="form-group">

                            <label>Nama Lengkap</label>

                            <input
                                type="text"
                                name="customer_name"
                                value="{{ old('customer_name', auth()->user()->name) }}"
                                placeholder="Nama penerima"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Nomor Telepon</label>

                            <input
                                type="tel"
                                name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                placeholder="Contoh: 081234567890"
                                required>

                        </div>

                    </div>

                    <div class="checkout-card">

                        <h3>Informasi Acara</h3>

                        <div class="form-group">

                            <label>Tanggal Acara</label>

                            <input
                                type="date"
                                name="event_date"
                                min="{{ now()->toDateString() }}"
                                value="{{ old('event_date') }}"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Waktu Acara</label>

                            <input
                                type="time"
                                name="event_time"
                                value="{{ old('event_time') }}">

                        </div>

                        <div class="form-group">

                            <label>Metode Pengiriman</label>

                            <select
                                name="delivery_type"
                                id="delivery_type"
                                required>

                                <option value="delivery"
                                    @selected(old('delivery_type', 'delivery') === 'delivery')>
                                    Diantar ke alamat
                                </option>

                                <option value="pickup"
                                    @selected(old('delivery_type') === 'pickup')>
                                    Ambil sendiri
                                </option>

                            </select>

                        </div>

                        <div class="form-group" id="address_group">

                            <label>Alamat Pengiriman</label>

                            <textarea
                                name="delivery_address"
                                id="delivery_address"
                                rows="4"
                                placeholder="Masukkan alamat lengkap">{{ old('delivery_address') }}</textarea>

                        </div>

                        <div class="form-group">

                            <label>Catatan Tambahan</label>

                            <textarea
                                name="notes"
                                rows="3"
                                placeholder="Contoh: makanan diantar sebelum pukul 10.00">{{ old('notes') }}</textarea>

                        </div>

                    </div>

                    <div class="checkout-card">

                        <h3>Metode Pembayaran</h3>

                        <div class="payment-option">

                            <span>🏦</span>

                            <div>
                                <strong>Transfer Bank</strong>
                                <p>Pembayaran melalui transfer manual.</p>
                            </div>

                        </div>

                        <p class="form-hint">
                            Informasi rekening dan upload bukti pembayaran
                            tersedia setelah pesanan dibuat.
                        </p>

                    </div>

                </div>

                <!-- Ringkasan pesanan -->
                <div class="checkout-summary">

                    <div class="checkout-card">

                        <h3>Ringkasan Pesanan</h3>

                        @foreach ($items as $item)

                            <div class="summary-product">

                                <div>
                                    <strong>{{ $item['menu']->name }}</strong>

                                    <small>
                                        {{ $item['quantity'] }} porsi
                                    </small>
                                </div>

                                <strong>
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </strong>

                            </div>

                        @endforeach

                        <div class="summary-row">
                            <span>Subtotal</span>

                            <strong>
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Biaya Pengiriman</span>

                            <strong id="delivery_fee">
                                Rp 15.000
                            </strong>
                        </div>

                        <div class="summary-total">
                            <span>Total Pembayaran</span>

                            <strong id="total_price">
                                Rp {{ number_format($subtotal + 15000, 0, ',', '.') }}
                            </strong>
                        </div>

                        <button type="submit"
                                class="btn btn-primary checkout-button">

                            Buat Pesanan

                        </button>

                        <a href="{{ route('user.cart.index') }}"
                           class="continue-link">
                            ← Kembali ke Keranjang
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</section>

<script>
    // Menghitung ulang tampilan biaya pengiriman
    const deliveryType = document.getElementById('delivery_type');
    const addressGroup = document.getElementById('address_group');
    const addressInput = document.getElementById('delivery_address');

    const deliveryFee = document.getElementById('delivery_fee');
    const totalPrice = document.getElementById('total_price');

    const subtotal = {{ (float) $subtotal }};

    function updateCheckout() {

        const isDelivery = deliveryType.value === 'delivery';

        const fee = isDelivery ? 15000 : 0;

        addressGroup.style.display = isDelivery ? 'block' : 'none';

        addressInput.required = isDelivery;

        deliveryFee.textContent = 'Rp ' + fee.toLocaleString('id-ID');

        totalPrice.textContent =
            'Rp ' + (subtotal + fee).toLocaleString('id-ID');
    }

    deliveryType.addEventListener('change', updateCheckout);

    updateCheckout();
</script>

@endsection
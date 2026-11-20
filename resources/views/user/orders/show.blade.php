@extends('layouts.app')

@section('title', 'Detail Pesanan - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('user.orders.index') }}" class="back-link">
            ← Kembali ke Riwayat Pesanan
        </a>

        <div class="section-heading">
            <p class="eyebrow">ORDER DETAIL</p>
            <h2>Detail Pesanan</h2>
            <p>{{ $order->order_code }}</p>
        </div>

        <div class="order-detail-layout">

            <div class="checkout-card">

                <h3>Informasi Pesanan</h3>

                <div class="summary-row">
                    <span>Kode Pesanan</span>
                    <strong>{{ $order->order_code }}</strong>
                </div>

                <div class="summary-row">
                    <span>Status Pesanan</span>
                    <strong>
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Nama Pemesan</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>

                <div class="summary-row">
                    <span>Nomor Telepon</span>
                    <strong>{{ $order->customer_phone }}</strong>
                </div>

                <div class="summary-row">
                    <span>Tanggal Acara</span>
                    <strong>{{ $order->event_date->format('d/m/Y') }}</strong>
                </div>

                <div class="summary-row">
                    <span>Waktu Acara</span>
                    <strong>{{ $order->event_time ?? '-' }}</strong>
                </div>

                <div class="summary-row">
                    <span>Pengiriman</span>
                    <strong>
                        {{ $order->delivery_type === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}
                    </strong>
                </div>

                @if ($order->delivery_address)

                    <div class="summary-row">
                        <span>Alamat</span>
                        <strong>{{ $order->delivery_address }}</strong>
                    </div>

                @endif

                @if ($order->notes)

                    <div class="summary-row">
                        <span>Catatan</span>
                        <strong>{{ $order->notes }}</strong>
                    </div>

                @endif

            </div>

            <div class="checkout-card">

                <h3>Rincian Makanan</h3>

                @foreach ($order->items as $item)

                    <div class="summary-product">

                        <div>
                            <strong>{{ $item->menu_name }}</strong>

                            <small>
                                {{ $item->quantity }} porsi ×
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </small>
                        </div>

                        <strong>
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </strong>

                    </div>

                @endforeach

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Pengiriman</span>
                    <strong>
                        Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="summary-total">
                    <span>Total</span>
                    <strong>
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </strong>
                </div>

                <h3 class="payment-title">Informasi Pembayaran</h3>

                <div class="summary-row">
                    <span>Metode</span>
                    <strong>Transfer Bank</strong>
                </div>

                <div class="summary-row">
                    <span>Status Pembayaran</span>
                    <strong>
                        {{ $order->payment
                            ? ucwords(str_replace('_', ' ', $order->payment->status))
                            : 'Belum tersedia' }}
                    </strong>
                </div>

                @if ($order->status === 'pending_payment')
                    <div class="payment-notice">
                        Pesanan telah dibuat. Silakan lanjutkan ke proses
                        pembayaran setelah informasi rekening tersedia.
                    </div>
                @endif

            </div>

        </div>

    </div>

</section>

@endsection
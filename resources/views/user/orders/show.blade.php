@extends('layouts.app')

@section('title', 'Detail Pesanan - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <a href="{{ route('user.orders.index') }}" class="back-link">
            ← Kembali ke Riwayat Pesanan
        </a>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="section-heading">
            <p class="eyebrow">ORDER DETAIL</p>

            <h2>Detail Pesanan</h2>

            <p>{{ $order->order_code }}</p>
        </div>

        @php

           $trackingSteps = [
               [
                   'status' => 'pending_payment',
                   'label' => 'Menunggu Pembayaran',
                   'description' => 'Selesaikan pembayaran pesanan.',
               ],
               [
                   'status' => 'processing',
                   'label' => 'Pesanan Diproses',
                   'description' => 'Tim Dapur.Go sedang menyiapkan pesanan.',
               ],
               [
                   'status' => 'ready',
                   'label' => 'Pesanan Siap',
                   'description' => 'Pesanan telah selesai disiapkan.',
               ],
           ];

           if ($order->delivery_type === 'delivery') {

               $trackingSteps[] = [
                   'status' => 'delivering',
                   'label' => 'Dalam Pengiriman',
                   'description' => 'Pesanan sedang diantar.',
               ];

           }
       
           $trackingSteps[] = [
               'status' => 'completed',
               'label' => 'Pesanan Selesai',
               'description' => 'Terima kasih telah memesan di Dapur.Go.',
           ];
       
           $currentIndex = collect($trackingSteps)
               ->search(fn ($step) => $step['status'] === $order->status);
       
            @endphp
       
           <div class="tracking-card">
       
           <h3>Perjalanan Pesanan</h3>
       
           @if ($currentIndex === false)
       
               <div class="payment-notice">
                   Status pesanan belum dapat ditentukan.
                   Silakan hubungi Admin.
               </div>
           
           @else
           
               <div class="tracking-list">

                   @foreach ($trackingSteps as $index => $step)

                       <div class="tracking-step
                           {{ $index < $currentIndex ? 'done' : '' }}
                           {{ $index === $currentIndex ? 'current' : '' }}">

                           <div class="tracking-marker">

                               @if ($index < $currentIndex)
                                   ✓
                               @else
                                   {{ $index + 1 }}
                               @endif

                           </div>
                       
                           <div class="tracking-content">

                               <strong>{{ $step['label'] }}</strong>

                               <p>{{ $step['description'] }}</p>

                           </div>
                       
                       </div>
                   
                   @endforeach
                   
               </div>
           
           @endif
           
        </div>

        <div class="order-detail-layout">

            <!-- Informasi Pesanan -->
            <div class="checkout-card">

                <h3>Informasi Pesanan</h3>

                <div class="summary-row">
                    <span>Kode Pesanan</span>
                    <strong>{{ $order->order_code }}</strong>
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
                        {{ $order->delivery_type === 'delivery'
                            ? 'Diantar'
                            : 'Ambil Sendiri' }}
                    </strong>
                </div>

                @if ($order->delivery_address)
                    <div class="summary-row">
                        <span>Alamat</span>
                        <strong>{{ $order->delivery_address }}</strong>
                    </div>
                @endif

            </div>

            <!-- Rincian Menu -->
            <div class="checkout-card">

                <h3>Rincian Pesanan</h3>

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
                    <span>Biaya Pengiriman</span>
                    <strong>
                        Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="summary-total">
                    <span>Total Pembayaran</span>
                    <strong>
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </strong>
                </div>

            </div>

            <!-- Pembayaran -->
            <div class="checkout-card payment-card">

                <h3>Informasi Pembayaran</h3>

                @if ($order->payment)

                    <div class="summary-row">
                        <span>Metode</span>
                        <strong>Transfer Bank</strong>
                    </div>

                    <div class="summary-row">
                        <span>Status Pembayaran</span>
                        <strong>
                            {{ ucwords(str_replace('_', ' ', $order->payment->status)) }}
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Status Pesanan</span>
                        <strong>
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </strong>
                    </div>

                    @if ($order->payment->status === 'paid')

                        <div class="alert-success">
                            <strong>Pembayaran berhasil dikonfirmasi!</strong>
                            <p>Pesanan Anda sedang ditangani oleh tim Dapur.Go.</p>
                        </div>

                    @elseif ($order->payment->status === 'waiting_verification')

                        <div class="payment-notice">
                            <strong>Bukti pembayaran sudah dikirim.</strong>

                            <p>
                                Admin sedang memeriksa pembayaran Anda.
                                Mohon menunggu konfirmasi.
                            </p>
                        </div>

                    @elseif (in_array($order->payment->status, [
                        'waiting_payment',
                        'rejected'
                    ], true))

                        @if ($order->payment->rejection_note)

                            <div class="alert-error">
                                <strong>Pembayaran ditolak.</strong>

                                <p>
                                    {{ $order->payment->rejection_note }}
                                </p>

                                <p>
                                    Silakan periksa dan unggah kembali bukti pembayaran.
                                </p>
                            </div>

                        @endif

                        <div class="bank-information">

                            <h3>Rekening Pembayaran Dapur.Go</h3>

                            @if (
                                config('catering.bank_name') &&
                                config('catering.account_name') &&
                                config('catering.account_number')
                            )

                                <div class="bank-detail">
                                    <span>Bank</span>
                                    <strong>
                                        {{ config('catering.bank_name') }}
                                    </strong>
                                </div>

                                <div class="bank-detail">
                                    <span>Nomor Rekening</span>
                                    <strong>
                                        {{ config('catering.account_number') }}
                                    </strong>
                                </div>

                                <div class="bank-detail">
                                    <span>Atas Nama</span>
                                    <strong>
                                        {{ config('catering.account_name') }}
                                    </strong>
                                </div>

                                <div class="bank-amount">
                                    <span>Total Transfer</span>

                                    <h2>
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </h2>
                                </div>

                            @else

                                <div class="payment-notice">
                                    Informasi rekening belum tersedia.
                                    Silakan hubungi Admin Dapur.Go.
                                </div>

                            @endif

                        </div>

                        @if (
                            config('catering.bank_name') &&
                            config('catering.account_name') &&
                            config('catering.account_number')
                        )

                            <form
                                action="{{ route('user.payments.upload', $order) }}"
                                method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="form-group">

                                    <label for="proof">
                                        Upload Bukti Transfer
                                    </label>

                                    <input
                                        type="file"
                                        name="proof"
                                        id="proof"
                                        accept=".jpg,.jpeg,.png,.webp,.pdf"
                                        required>

                                    <small class="form-hint">
                                        Format JPG, PNG, WEBP, atau PDF.
                                        Maksimal 3 MB.
                                    </small>

                                </div>

                                <button type="submit"
                                        class="btn btn-primary checkout-button">

                                    Kirim Bukti Pembayaran

                                </button>

                            </form>

                        @endif

                    @endif

                @else

                    <div class="alert-error">
                        Informasi pembayaran belum tersedia.
                    </div>

                @endif

            </div>

        </div>

    </div>

</section>

@endsection
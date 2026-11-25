@extends('layouts.admin')

@section('title', 'Detail Pesanan - Dapur.Go')

@section('content')

<div class="page-heading">

    <div>
        <h1>Detail Pesanan</h1>
        <p>{{ $order->order_code }}</p>
    </div>

    <a href="{{ route('admin.orders.index') }}"
       class="btn btn-outline">
        Kembali
    </a>

</div>

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif


<div class="order-admin-grid">

    <!-- Informasi Pelanggan -->
    <div class="admin-panel">

        <h3>Informasi Pelanggan</h3>

        <div class="admin-detail-row">
            <span>Nama Pelanggan</span>
            <strong>{{ $order->customer_name }}</strong>
        </div>

        <div class="admin-detail-row">
            <span>Email</span>
            <strong>{{ $order->user->email }}</strong>
        </div>

        <div class="admin-detail-row">
            <span>Nomor Telepon</span>
            <strong>{{ $order->customer_phone }}</strong>
        </div>

        <div class="admin-detail-row">
            <span>Tanggal Acara</span>
            <strong>{{ $order->event_date->format('d/m/Y') }}</strong>
        </div>

        <div class="admin-detail-row">
            <span>Waktu Acara</span>
            <strong>{{ $order->event_time ?? '-' }}</strong>
        </div>

        <div class="admin-detail-row">
            <span>Metode Pengiriman</span>
            <strong>
                {{ $order->delivery_type === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}
            </strong>
        </div>

        <div class="admin-detail-row">
            <span>Alamat Pengiriman</span>
            <strong>{{ $order->delivery_address ?? '-' }}</strong>
        </div>

        <div class="admin-detail-row">
            <span>Catatan</span>
            <strong>{{ $order->notes ?? '-' }}</strong>
        </div>

    </div>


    <!-- Rincian Menu -->
    <div class="admin-panel">

        <h3>Rincian Menu</h3>

        @foreach ($order->items as $item)

            <div class="admin-detail-row">

                <div>
                    <strong>{{ $item->menu_name }}</strong>
                    <small>{{ $item->quantity }} porsi</small>
                </div>

                <strong>
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </strong>

            </div>

        @endforeach

        <hr class="admin-separator">

        <div class="admin-detail-row">
            <span>Subtotal</span>
            <strong>
                Rp {{ number_format($order->subtotal, 0, ',', '.') }}
            </strong>
        </div>

        <div class="admin-detail-row">
            <span>Biaya Pengiriman</span>
            <strong>
                Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
            </strong>
        </div>

        <div class="admin-detail-row admin-total">
            <span>Total Pembayaran</span>
            <strong>
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </strong>
        </div>

    </div>


    <!-- Informasi Pembayaran -->
    <div class="admin-panel">

        <h3>Verifikasi Pembayaran</h3>

        @if ($order->payment)

            <div class="admin-detail-row">
                <span>Metode Pembayaran</span>
                <strong>Transfer Bank</strong>
            </div>

            <div class="admin-detail-row">
                <span>Nominal</span>
                <strong>
                    Rp {{ number_format($order->payment->amount, 0, ',', '.') }}
                </strong>
            </div>

            <div class="admin-detail-row">
                <span>Status Pembayaran</span>
                <strong>
                    {{ ucwords(str_replace('_', ' ', $order->payment->status)) }}
                </strong>
            </div>

            @if ($order->payment->proof_path)

                <div class="proof-box">

                    <p>Bukti pembayaran sudah diunggah.</p>

                    <a
                        href="{{ route('admin.orders.payment-proof', $order) }}"
                        target="_blank"
                        rel="noopener"
                        class="btn btn-outline">

                        Lihat Bukti Pembayaran

                    </a>

                </div>

            @else

                <div class="payment-notice">
                    Pengguna belum mengunggah bukti pembayaran.
                </div>

            @endif

            @if ($order->payment->rejection_note)

                <div class="alert-error">
                    <strong>Alasan penolakan sebelumnya:</strong>
                    <p>{{ $order->payment->rejection_note }}</p>
                </div>

            @endif


            @if ($order->payment->status === 'waiting_verification')

                <div class="verification-actions">

                    <form
                        action="{{ route('admin.orders.approve-payment', $order) }}"
                        method="POST"
                        onsubmit="return confirm('Konfirmasi pembayaran ini?')">

                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-primary">
                            Setujui Pembayaran
                        </button>

                    </form>

                    <form
                        action="{{ route('admin.orders.reject-payment', $order) }}"
                        method="POST">

                        @csrf
                        @method('PATCH')

                        <label>Alasan Penolakan</label>

                        <textarea
                            name="rejection_note"
                            rows="3"
                            placeholder="Contoh: Bukti transfer tidak sesuai dengan nominal pesanan."
                            required></textarea>

                        <button type="submit" class="btn-delete">
                            Tolak Pembayaran
                        </button>

                    </form>

                </div>

            @elseif ($order->payment->status === 'paid')

                <div class="alert-success">
                    Pembayaran telah dikonfirmasi oleh Admin.
                </div>

            @elseif ($order->payment->status === 'waiting_payment')

                <div class="payment-notice">
                    Menunggu pengguna melakukan pembayaran.
                </div>

            @elseif ($order->payment->status === 'rejected')

                <div class="payment-notice">
                    Pembayaran ditolak. Menunggu bukti pembayaran baru dari pengguna.
                </div>

            @endif

        @else

            <div class="alert-error">
                Data pembayaran belum tersedia.
            </div>

        @endif

    </div>


    <!-- Perubahan Status Pesanan -->
    <div class="admin-panel">

        <h3>Status Pesanan</h3>

        <div class="admin-detail-row">
            <span>Status saat ini</span>
            <strong>
                {{ ucwords(str_replace('_', ' ', $order->status)) }}
            </strong>
        </div>

        @php

            $nextStatuses = [];

            if ($order->status === 'processing') {
                $nextStatuses = ['ready'];
            }

            if ($order->status === 'ready') {
                $nextStatuses = $order->delivery_type === 'delivery'
                    ? ['delivering']
                    : ['completed'];
            }

            if ($order->status === 'delivering') {
                $nextStatuses = ['completed'];
            }

        @endphp

        @if ($order->payment?->status === 'paid' && count($nextStatuses))

            <form
                action="{{ route('admin.orders.update-status', $order) }}"
                method="POST"
                class="status-form">

                @csrf
                @method('PATCH')

                <label for="status">Ubah Status Pesanan</label>

                <select name="status" id="status" required>

                    @foreach ($nextStatuses as $status)

                        <option value="{{ $status }}">
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>

                    @endforeach

                </select>

                <button type="submit" class="btn btn-primary">
                    Perbarui Status
                </button>

            </form>

        @elseif ($order->status === 'completed')

            <div class="alert-success">
                Pesanan telah selesai.
            </div>

        @elseif ($order->payment?->status !== 'paid')

            <div class="payment-notice">
                Status pesanan dapat diperbarui setelah pembayaran dikonfirmasi.
            </div>

        @endif

    </div>

</div>

@endsection
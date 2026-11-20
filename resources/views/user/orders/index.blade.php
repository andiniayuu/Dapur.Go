@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <div class="section-heading">
            <p class="eyebrow">ORDER HISTORY</p>
            <h2>Riwayat Pesanan</h2>
            <p>Pantau seluruh pemesanan catering Anda.</p>
        </div>

        @forelse ($orders as $order)

            <div class="order-card">

                <div class="order-header">

                    <div>
                        <small>Kode Pesanan</small>

                        <h3>{{ $order->order_code }}</h3>

                        <small>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <span class="order-status">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </span>

                </div>

                <div class="order-body">

                    <div>
                        <small>Tanggal Acara</small>
                        <strong>
                            {{ $order->event_date->format('d/m/Y') }}
                        </strong>
                    </div>

                    <div>
                        <small>Metode Pengiriman</small>
                        <strong>
                            {{ $order->delivery_type === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}
                        </strong>
                    </div>

                    <div>
                        <small>Total Pesanan</small>
                        <strong class="menu-price">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </strong>
                    </div>

                </div>

                <a href="{{ route('user.orders.show', $order) }}"
                   class="btn btn-primary">
                    Lihat Detail
                </a>

            </div>

        @empty

            <div class="empty-state">
                <div class="category-icon">📦</div>

                <h3>Belum Ada Pesanan</h3>

                <p>Pesanan catering Anda akan muncul di sini.</p>

                <a href="{{ route('catalog.index') }}"
                   class="btn btn-primary">
                    Pesan Catering
                </a>
            </div>

        @endforelse

        <div class="pagination">
            {{ $orders->links() }}
        </div>

    </div>

</section>

@endsection 
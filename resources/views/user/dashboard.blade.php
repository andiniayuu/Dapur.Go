@extends('layouts.app')

@section('title', 'Dashboard Pengguna - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <div class="section-heading">
            <p class="eyebrow">CUSTOMER DASHBOARD</p>

            <h2>Halo, {{ auth()->user()->name }}!</h2>

            <p>
                Selamat datang di Dapur.Go.
                Siap memilih menu catering hari ini?
            </p>
        </div>

        <div class="feature-grid">

            <div class="feature-card">
                <h3>🍱 Katalog Menu</h3>
                <p>Jelajahi pilihan menu catering.</p>

                <a href="{{ route('catalog.index') }}"
                   class="btn btn-primary">
                    Lihat Menu
                </a>
            </div>

            <div class="feature-card">
                <h3>🛒 Keranjang</h3>
                <p>Periksa pilihan makanan Anda.</p>

                <a href="{{ route('user.cart.index') }}"
                   class="btn btn-primary">
                    Buka Keranjang
                </a>
            </div>

            <div class="feature-card">
                <h3>📦 Riwayat Pesanan</h3>
                <p>Pantau pesanan catering Anda.</p>

                <a href="{{ route('user.orders.index') }}"
                   class="btn btn-primary">
                    Lihat Pesanan
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
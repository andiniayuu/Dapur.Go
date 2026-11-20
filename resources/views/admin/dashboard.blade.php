@extends('layouts.admin')

@section('title', 'Dashboard Admin - Dapur.Go')

@section('content')

    <div class="page-heading">
        <div>
            <h1>Dashboard</h1>
            <p>Selamat datang di pengelolaan Dapur.Go.</p>
        </div>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <span>Total Menu</span>
            <h2>{{ $totalMenus }}</h2>
            <p>Menu catering terdaftar</p>
        </div>

        <div class="stat-card">
            <span>Total Kategori</span>
            <h2>{{ $totalCategories }}</h2>
            <p>Kategori catering</p>
        </div>

        <div class="stat-card">
            <span>Total Pesanan</span>
            <h2>{{ $totalOrders }}</h2>
            <p>Seluruh pesanan pelanggan</p>
        </div>

        <div class="stat-card">
            <span>Pesanan Menunggu</span>
            <h2>{{ $pendingOrders }}</h2>
            <p>Perlu diproses</p>
        </div>

    </div>

    <div class="admin-panel">
        <div>
            <h3>Pengelolaan Menu Catering</h3>
            <p>
                Tambahkan produk, ubah informasi makanan,
                harga, kategori, dan ketersediaan menu.
            </p>
        </div>

        <a href="{{ route('admin.menus.index') }}"
           class="btn btn-primary">
            Kelola Menu
        </a>
    </div>

@endsection
@extends('layouts.app')

@section('title', 'Beranda - Dapur.Go')

@section('content')

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container hero-content">

            <div class="hero-text">

                <p class="eyebrow">
                    FRESH FOOD • QUALITY SERVICE
                </p>

                <h1>
                    Sajian Lezat untuk
                    <span>Setiap Momen.</span>
                </h1>

                <p class="hero-description">
                    Nikmati kemudahan memesan catering untuk
                    berbagai kebutuhan acara. Pilihan menu
                    lezat, praktis, dan siap menemani momen
                    spesial Anda.
                </p>

                <div class="hero-buttons">
                    <a href="#menu" class="btn btn-primary">
                        Jelajahi Menu
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-outline">
                        Mulai Pesan
                    </a>
                </div>

            </div>

            <div class="hero-image">

                <img
                    src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=1000&q=85"
                    alt="Hidangan makanan sehat">

                <div class="image-label">
                    Fresh Food, Happy Moments
                </div>

            </div>

        </div>
    </section>


    <!-- Kategori Catering -->
    <section class="section" id="kategori">

        <div class="container">

            <div class="section-heading">
                <p class="eyebrow">PILIHAN KAMI</p>

                <h2>Kategori Catering</h2>

                <p>
                    Temukan pilihan catering sesuai kebutuhan Anda.
                </p>
            </div>

            <div class="category-grid">

                <div class="category-card">
                    <div class="category-icon">🍱</div>
                    <h3>Nasi Box</h3>
                </div>

                <div class="category-card">
                    <div class="category-icon">🍽️</div>
                    <h3>Prasmanan</h3>
                </div>

                <div class="category-card">
                    <div class="category-icon">🥪</div>
                    <h3>Snack Box</h3>
                </div>

                <div class="category-card">
                    <div class="category-icon">🎉</div>
                    <h3>Paket Acara</h3>
                </div>

            </div>

        </div>

    </section>


    <!-- Menu Unggulan -->
    <section class="section" id="menu">

        <div class="container">

            <div class="section-heading">
                <p class="eyebrow">MENU PILIHAN</p>

                <h2>Menu Catering Kami</h2>

                <p>
                    Pilihan hidangan untuk melengkapi
                    setiap acara Anda.
                </p>
            </div>

            <div class="menu-grid">

                @forelse ($menus as $menu)

                    <div class="menu-card">

                        @if ($menu->image)

                            <img
                                class="menu-image"
                                src="{{ asset('storage/' . $menu->image) }}"
                                alt="{{ $menu->name }}">

                        @else

                            <div class="menu-placeholder">
                                🍱
                            </div>

                        @endif

                        <div class="menu-info">

                            <small>
                                {{ $menu->category?->name ?? 'Catering' }}
                            </small>

                            <h3>{{ $menu->name }}</h3>

                            <p>
                                {{ $menu->description
                                    ?: 'Hidangan pilihan dari Dapur.Go.' }}
                            </p>

                            <p class="menu-price">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="empty-state">

                        <div class="category-icon">🍱</div>

                        <h3>Menu Sedang Disiapkan</h3>

                        <p>
                            Pilihan menu catering kami akan segera hadir.
                            Silakan kunjungi kembali halaman ini.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    <!-- Keunggulan Dapur.Go -->
    <section class="section features" id="tentang">

        <div class="container">

            <div class="section-heading">

                <p class="eyebrow">KENAPA DAPUR.GO?</p>

                <h2>Solusi Catering untuk Anda</h2>

                <p>
                    Kami menghadirkan kemudahan dalam
                    memenuhi kebutuhan catering Anda.
                </p>

            </div>

            <div class="feature-grid">

                <div class="feature-card">
                    <div class="category-icon">🥗</div>

                    <h3>Menu Berkualitas</h3>

                    <p>
                        Pilihan hidangan yang disiapkan
                        untuk menemani berbagai acara.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="category-icon">📱</div>

                    <h3>Pemesanan Praktis</h3>

                    <p>
                        Pesan catering dengan mudah
                        melalui website.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="category-icon">📅</div>

                    <h3>Jadwal Terencana</h3>

                    <p>
                        Tentukan tanggal dan waktu
                        kebutuhan catering Anda.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="category-icon">🤝</div>

                    <h3>Pelayanan Terbaik</h3>

                    <p>
                        Kami berusaha memberikan
                        pengalaman pemesanan yang nyaman.
                    </p>
                </div>

            </div>

        </div>

    </section>

@endsection
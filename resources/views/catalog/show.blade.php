@extends('layouts.app')

@section('title', $menu->name . ' - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <a href="{{ route('catalog.index') }}" class="back-link">
            ← Kembali ke Katalog
        </a>

        <div class="detail-layout">

            <div>
                @if ($menu->image)
                    <img
                        src="{{ asset('storage/' . $menu->image) }}"
                        class="detail-image"
                        alt="{{ $menu->name }}">
                @else
                    <div class="detail-placeholder">🍱</div>
                @endif
            </div>

            <div class="detail-info">

                <p class="eyebrow">
                    {{ $menu->category?->name ?? 'Catering' }}
                </p>

                <h1>{{ $menu->name }}</h1>

                <p>
                    {{ $menu->description ?: 'Hidangan pilihan dari Dapur.Go.' }}
                </p>

                <h2 class="menu-price">
                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                </h2>

                <p class="availability">
                    <span>●</span> Tersedia
                </p>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Login untuk Memesan
                    </a>
                @else
                    @if (auth()->user()->role === 'user')
                        <a href="{{ route('user.dashboard') }}"
                           class="btn btn-primary">
                            Kembali ke Dashboard
                        </a>
                    @endif
                @endguest

            </div>

        </div>

    </div>

</section>

@endsection
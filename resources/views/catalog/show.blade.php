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

                @auth

                @if (auth()->user()->role === 'user')
                        
                    <form
                        action="{{ route('user.cart.add', $menu) }}"
                        method="POST"
                        class="add-cart-form">
                        
                        @csrf
                        
                        <label for="quantity">Jumlah Porsi</label>
                        
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="1"
                            min="1"
                            max="100"
                            required>
                        
                        <button type="submit" class="btn btn-primary">
                            + Tambah ke Keranjang
                        </button>
                    
                    </form>
                
                @else
                
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        Kembali ke Dashboard Admin
                    </a>
                
                @endif
                
            @else
                
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Login untuk Memesan
                </a>
            
            @endauth

            </div>

        </div>

    </div>

</section>

@endsection
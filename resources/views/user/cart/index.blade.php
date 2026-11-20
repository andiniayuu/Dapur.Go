@extends('layouts.app')

@section('title', 'Keranjang - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <div class="section-heading">
            <p class="eyebrow">YOUR ORDER</p>
            <h2>Keranjang Belanja</h2>
            <p>Periksa kembali menu catering pilihan Anda.</p>
        </div>

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
                {{ $errors->first() }}
            </div>
        @endif

        @if ($items->isEmpty())

            <div class="empty-state">
                <div class="category-icon">🛒</div>

                <h3>Keranjang Masih Kosong</h3>

                <p>
                    Yuk, pilih menu catering favorit Anda.
                </p>

                <a href="{{ route('catalog.index') }}"
                   class="btn btn-primary">
                    Jelajahi Menu
                </a>
            </div>

        @else

            <div class="cart-layout">

                <div class="cart-items">

                    @foreach ($items as $item)

                        <div class="cart-item">

                            @if ($item['menu']->image)
                                <img
                                    src="{{ asset('storage/' . $item['menu']->image) }}"
                                    alt="{{ $item['menu']->name }}"
                                    class="cart-image">
                            @else
                                <div class="cart-placeholder">🍱</div>
                            @endif

                            <div class="cart-detail">

                                <h3>{{ $item['menu']->name }}</h3>

                                <small>
                                    {{ $item['menu']->category?->name ?? 'Catering' }}
                                </small>

                                <p class="menu-price">
                                    Rp {{ number_format($item['menu']->price, 0, ',', '.') }}
                                </p>

                                <div class="cart-actions">

                                    <form
                                        action="{{ route('user.cart.update', $item['menu']) }}"
                                        method="POST"
                                        class="quantity-form">

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="100"
                                            required>

                                        <button type="submit" class="btn-edit">
                                            Perbarui
                                        </button>

                                    </form>

                                    <form
                                        action="{{ route('user.cart.remove', $item['menu']) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus menu ini dari keranjang?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </div>

                            <div class="cart-item-total">

                                <small>Subtotal</small>

                                <strong>
                                    Rp {{ number_format($item['menu']->price * $item['quantity'], 0, ',', '.') }}
                                </strong>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="cart-summary">

                    <h3>Ringkasan Pesanan</h3>

                    <div class="summary-row">
                        <span>Subtotal</span>

                        <strong>
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Pengiriman</span>
                        <span>Dihitung saat checkout</span>
                    </div>

                    <div class="summary-total">
                        <span>Total sementara</span>

                        <strong>
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </strong>
                    </div>

                    <a href="{{ route('user.checkout.create') }}"
                       class="btn btn-primary checkout-button">
                        Lanjut Checkout
                    </a>

                    <a href="{{ route('catalog.index') }}"
                       class="continue-link">
                        ← Lanjut Belanja
                    </a>

                </div>

            </div>

        @endif

    </div>

</section>

@endsection
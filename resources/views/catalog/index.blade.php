@extends('layouts.app')

@section('title', 'Katalog Menu - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <div class="section-heading">
            <p class="eyebrow">DAPUR.GO MENU</p>
            <h2>Jelajahi Menu Catering</h2>
            <p>Pilih hidangan sesuai kebutuhan acara Anda.</p>
        </div>

        <!-- Pencarian dan filter -->
        <form action="{{ route('catalog.index') }}"
              method="GET"
              class="catalog-filter">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari menu catering...">

            <select name="category_id">

                <option value="">Semua Kategori</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(request('category_id') == $category->id)>

                        {{ $category->name }}

                    </option>
                @endforeach

            </select>

            <button type="submit" class="btn btn-primary">
                Cari Menu
            </button>

        </form>

        <!-- Daftar menu -->
        <div class="menu-grid">

            @forelse ($menus as $menu)

                <div class="menu-card">

                    @if ($menu->image)
                        <img
                            src="{{ asset('storage/' . $menu->image) }}"
                            class="menu-image"
                            alt="{{ $menu->name }}">
                    @else
                        <div class="menu-placeholder">🍱</div>
                    @endif

                    <div class="menu-info">

                        <small>
                            {{ $menu->category?->name ?? 'Catering' }}
                        </small>

                        <h3>{{ $menu->name }}</h3>

                        <p>{{ $menu->description }}</p>

                        <p class="menu-price">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </p>

                        <a href="{{ route('catalog.show', $menu->slug) }}"
                           class="btn btn-primary catalog-button">
                            Lihat Detail
                        </a>

                    </div>

                </div>

            @empty

                <div class="empty-state">
                    <div class="category-icon">🍱</div>

                    <h3>Menu Tidak Ditemukan</h3>

                    <p>Coba cari dengan kata kunci lainnya.</p>
                </div>

            @endforelse

        </div>

        <div class="pagination">
            {{ $menus->links() }}
        </div>

    </div>

</section>

@endsection
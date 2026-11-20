@extends('layouts.admin')

@section('title', 'Kelola Menu - Dapur.Go')

@section('content')

    <div class="page-heading">
        <div>
            <h1>Kelola Menu</h1>
            <p>Atur daftar produk catering Dapur.Go.</p>
        </div>

        <a href="{{ route('admin.menus.create') }}"
           class="btn btn-primary">
            + Tambah Menu
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-panel">

        <form action="{{ route('admin.menus.index') }}"
              method="GET"
              class="filter-form">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari nama menu..."
            >

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
                Cari
            </button>

        </form>

        <div class="table-wrapper">

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($menus as $index => $menu)

                        <tr>
                            <td>
                                {{ $menus->firstItem() + $index }}
                            </td>

                            <td>
                                @if ($menu->image)
                                    <img
                                        src="{{ asset('storage/' . $menu->image) }}"
                                        class="table-image"
                                        alt="{{ $menu->name }}">
                                @else
                                    <div class="no-image">🍱</div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $menu->name }}</strong>
                            </td>

                            <td>
                                {{ $menu->category?->name ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($menu->is_available)
                                    <span class="status-active">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="status-inactive">
                                        Tidak tersedia
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-buttons">

                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                       class="btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.menus.destroy', $menu) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus menu ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="empty-table">
                                Belum ada menu catering.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

        <div class="pagination">
            {{ $menus->links() }}
        </div>

    </div>

@endsection
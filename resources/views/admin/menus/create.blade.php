@extends('layouts.admin')

@section('title', 'Tambah Menu - Dapur.Go')

@section('content')

    <div class="page-heading">
        <div>
            <h1>Tambah Menu</h1>
            <p>Masukkan informasi produk catering baru.</p>
        </div>
    </div>

    <div class="admin-panel form-panel">

        <form action="{{ route('admin.menus.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            @include('admin.menus._form')

            <div class="form-actions">
                <a href="{{ route('admin.menus.index') }}"
                   class="btn btn-outline">
                    Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Menu
                </button>
            </div>

        </form>

    </div>

@endsection 
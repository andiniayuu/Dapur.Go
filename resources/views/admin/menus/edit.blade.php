    @extends('layouts.admin')

@section('title', 'Edit Menu - Dapur.Go')

@section('content')

    <div class="page-heading">
        <div>
            <h1>Edit Menu</h1>
            <p>Perbarui informasi produk catering.</p>
        </div>
    </div>

    <div class="admin-panel form-panel">

        <form
            action="{{ route('admin.menus.update', $menu) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('admin.menus._form')

            <div class="form-actions">
                <a href="{{ route('admin.menus.index') }}"
                   class="btn btn-outline">
                    Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

@endsection
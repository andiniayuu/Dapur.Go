@extends('layouts.app')

@section('title', 'Profil Saya - Dapur.Go')

@section('content')

<section class="section">

    <div class="container">

        <div class="section-heading">

            <p class="eyebrow">MY ACCOUNT</p>

            <h2>Profil Saya</h2>

            <p>Kelola informasi akun Dapur.Go Anda.</p>

        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="profile-card">

            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <h3>{{ auth()->user()->name }}</h3>

            <p>{{ auth()->user()->email }}</p>

            <span class="profile-role">Pengguna Dapur.Go</span>

            <hr>

            <form action="{{ route('user.profile.update') }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label>Nama Lengkap</label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        required>

                </div>

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required>

                </div>

                <hr>

                <h3 class="profile-password-title">
                    Ubah Password
                </h3>

                <p class="form-hint">
                    Kosongkan jika tidak ingin mengubah password.
                </p>

                <div class="form-group">

                    <label>Password Saat Ini</label>

                    <input
                        type="password"
                        name="current_password"
                        autocomplete="current-password">

                </div>

                <div class="form-group">

                    <label>Password Baru</label>

                    <input
                        type="password"
                        name="password"
                        autocomplete="new-password">

                </div>

                <div class="form-group">

                    <label>Konfirmasi Password Baru</label>

                    <input
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password">

                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

</section>

@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dapur.Go')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="admin-wrapper">

    <aside class="admin-sidebar">

        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <span class="brand-icon">D.</span>
            Dapur.Go
        </a>

        <p class="sidebar-label">MENU UTAMA</p>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>▦</span> Dashboard
        </a>

        <a href="{{ route('admin.menus.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
            <span>🍱</span> Kelola Menu
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span>▤</span> Kelola Pesanan
        </a>

        <div class="sidebar-link disabled">
            <span>▥</span> Laporan
        </div>

        <div class="sidebar-bottom">
            <a href="{{ route('home') }}" class="sidebar-link">
                ← Kembali ke Website
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="sidebar-logout" type="submit">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <main class="admin-main">

        <header class="admin-topbar">
            <div>
                <span class="topbar-caption">Administrator</span>
                <strong>{{ auth()->user()->name }}</strong>
            </div>
        </header>

        <section class="admin-content">
            @yield('content')
        </section>

    </main>

</div>

</body>
</html>
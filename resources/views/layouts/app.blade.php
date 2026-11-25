<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dapur.Go - Catering Online')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">

            <a href="{{ route('home') }}" class="brand">
                <span class="brand-icon">D.</span>
                <span>Dapur<span class="brand-dot">.</span>Go</span>
            </a>

           <nav class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('home') }}#kategori">Kategori</a>
                <a href="{{ route('catalog.index') }}">Menu</a>
                <a href="{{ route('home') }}#tentang">Tentang Kami</a>
            </nav>

            <div class="nav-actions">

                @auth

                @if (auth()->user()->role === 'user')

                    <a href="{{ route('user.cart.index') }}" class="login-link">
                        🛒 Keranjang
                    </a>
                
                    <a href="{{ route('user.orders.index') }}" class="login-link">
                        Pesanan
                    </a>

                    <a href="{{ route('user.profile.edit') }}" class="login-link">
                        Profil
                    </a>
                
                @endif
                
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    Dashboard
                </a>
            
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                
                    <button type="submit" class="btn btn-primary">
                        Logout
                    </button>
                </form>
            
            @else
            
                <a href="{{ route('login') }}" class="login-link">
                    Masuk
                </a>
            
                <a href="{{ route('register') }}" class="btn btn-primary">
                    Daftar
                </a>
            
            @endauth

            <a href="{{ route('notifications.index') }}" class="login-link">
                🔔 Notifikasi
                        
                @if (auth()->user()->unreadNotifications()->count() > 0)
                    <span class="notification-count">
                        {{ auth()->user()->unreadNotifications()->count() }}
                    </span>
                @endif
            </a>

            </div>
        </div>
    </header>

    <!-- Konten halaman -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">

            <div>
                <h3>Dapur<span>.</span>Go</h3>
                <p>
                    Solusi catering praktis untuk
                    setiap momen spesial Anda.
                </p>
            </div>

            <div>
                <p>© {{ date('Y') }} Dapur.Go</p>
                <p>Fresh Food, Happy Moments.</p>
            </div>

        </div>
    </footer>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengguna - Dapur.Go</title>
</head>
<body>

    <h1>Selamat Datang di Dapur.Go</h1>

    <h3>Halo, {{ auth()->user()->name }}!</h3>

    <p>Selamat datang di layanan pemesanan catering kami.</p>

    <hr>

    <h3>Menu Pengguna</h3>

    <ul>
        <li>Lihat Menu Catering</li>
        <li>Keranjang Belanja</li>
        <li>Pemesanan Catering</li>
        <li>Riwayat Pesanan</li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
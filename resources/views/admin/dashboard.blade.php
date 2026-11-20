<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Dapur.Go</title>
</head>
<body>

    <h1>Dashboard Admin</h1>

    <h3>Selamat datang, {{ auth()->user()->name }}!</h3>

    <p>Anda login sebagai Administrator.</p>

    <hr>

    <h3>Menu Pengelolaan</h3>

    <ul>
        <li>Kelola Menu Catering</li>
        <li>Kelola Kategori</li>
        <li>Kelola Pesanan</li>
        <li>Konfirmasi Pembayaran</li>
        <li>Laporan Transaksi</li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
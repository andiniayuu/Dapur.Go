@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Dapur.Go')

@section('content')

<div class="page-heading">
    <div>
        <h1>Laporan Transaksi</h1>
        <p>Pantau pendapatan dan aktivitas pemesanan catering.</p>
    </div>
</div>

<!-- Filter tanggal -->
<div class="admin-panel">

    <form method="GET"
          action="{{ route('admin.reports.index') }}"
          class="filter-form">

        <div class="form-group">
            <label>Dari Tanggal</label>
            <input type="date" name="from" value="{{ $from }}" required>
        </div>

        <div class="form-group">
            <label>Sampai Tanggal</label>
            <input type="date" name="to" value="{{ $to }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Tampilkan
        </button>

    </form>

</div>

<!-- Statistik -->
<div class="stats-grid">

    <div class="stat-card">
        <span>Total Pendapatan</span>
        <h2>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
        <p>Pembayaran terkonfirmasi</p>
    </div>

    <div class="stat-card">
        <span>Total Pesanan</span>
        <h2>{{ $totalOrders }}</h2>
        <p>Semua transaksi</p>
    </div>

    <div class="stat-card">
        <span>Pesanan Dibayar</span>
        <h2>{{ $paidOrders }}</h2>
        <p>Pembayaran berhasil</p>
    </div>

    <div class="stat-card">
        <span>Pesanan Selesai</span>
        <h2>{{ $completedOrders }}</h2>
        <p>Transaksi telah selesai</p>
    </div>

</div>

<!-- Pendapatan harian -->
<div class="admin-panel">

    <h3>Ringkasan Pendapatan Harian</h3>

    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($dailyRevenue as $date => $revenue)

                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                        </td>

                        <td>
                            Rp {{ number_format($revenue, 0, ',', '.') }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="2" class="empty-table">
                            Belum ada pendapatan pada periode ini.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- Riwayat transaksi -->
<div class="admin-panel">

    <h3>Riwayat Transaksi</h3>

    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($orders as $order)

                    <tr>

                        <td>{{ $order->order_code }}</td>

                        <td>{{ $order->customer_name }}</td>

                        <td>
                            {{ $order->created_at->format('d/m/Y') }}
                        </td>

                        <td>
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $order->payment
                                ? ucwords(str_replace('_', ' ', $order->payment->status))
                                : '-' }}
                        </td>

                        <td>
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty-table">
                            Tidak ada transaksi pada periode tersebut.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="pagination">
        {{ $orders->links() }}
    </div>

</div>

@endsection
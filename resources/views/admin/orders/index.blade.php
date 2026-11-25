@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Dapur.Go')

@section('content')

<div class="page-heading">

    <div>
        <h1>Kelola Pesanan</h1>
        <p>Periksa pesanan dan pembayaran pelanggan.</p>
    </div>

</div>

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="admin-panel">

    <!-- Filter dan pencarian -->
    <form method="GET"
          action="{{ route('admin.orders.index') }}"
          class="filter-form">

        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari kode atau nama pelanggan...">

        <select name="status">

            <option value="">Semua Status</option>

            @foreach ([
                'pending_payment',
                'processing',
                'ready',
                'delivering',
                'completed'
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(request('status') === $status)>

                    {{ ucwords(str_replace('_', ' ', $status)) }}

                </option>

            @endforeach

        </select>

        <button class="btn btn-primary" type="submit">
            Cari
        </button>

        <a href="{{ route('admin.orders.index') }}"
           class="btn btn-outline">
            Reset
        </a>

    </form>


    <!-- Tabel pesanan -->
    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Acara</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($orders as $index => $order)

                    <tr>

                        <td>{{ $orders->firstItem() + $index }}</td>

                        <td>
                            <strong>{{ $order->order_code }}</strong>
                        </td>

                        <td>
                            {{ $order->customer_name }}
                            <br>
                            <small>{{ $order->customer_phone }}</small>
                        </td>

                        <td>
                            {{ $order->event_date->format('d/m/Y') }}
                        </td>

                        <td>
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>

                        <td>
                            @if ($order->payment)
                                {{ ucwords(str_replace('_', ' ', $order->payment->status)) }}
                            @else
                                Belum ada
                            @endif
                        </td>

                        <td>
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </td>

                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn-edit">
                                Detail
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="empty-table">
                            Belum ada pesanan masuk.
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
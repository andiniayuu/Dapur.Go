<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        // Periode default: 30 hari terakhir
        $from = $validated['from'] ?? now()->subDays(29)->toDateString();
        $to = $validated['to'] ?? now()->toDateString();

        // Mengambil pesanan berdasarkan tanggal
        $query = Order::with(['user', 'payment'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $orders = (clone $query)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Hanya transaksi yang pembayarannya sudah dikonfirmasi
        $paidQuery = (clone $query)->whereHas('payment', function ($q) {
            $q->where('status', 'paid');
        });

        $totalRevenue = (clone $paidQuery)->sum('total_price');

        $paidOrders = (clone $paidQuery)->count();

        $totalOrders = (clone $query)->count();

        $completedOrders = (clone $query)
            ->where('status', 'completed')
            ->count();

        // Pendapatan harian
        $dailyOrders = (clone $paidQuery)
            ->get(['id', 'total_price', 'created_at']);

        $dailyRevenue = $dailyOrders
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->sum('total_price');
            });

        return view('admin.reports.index', compact(
            'orders',
            'from',
            'to',
            'totalRevenue',
            'paidOrders',
            'totalOrders',
            'completedOrders',
            'dailyRevenue'
        ));
    }
}

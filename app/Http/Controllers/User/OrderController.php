<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    // Riwayat pesanan
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('payment')
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    // Detail pesanan
    public function show(Order $order)
    {
        // Memastikan pengguna hanya melihat pesanan miliknya
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['items', 'payment']);

        return view('user.orders.show', compact('order'));
    }
}

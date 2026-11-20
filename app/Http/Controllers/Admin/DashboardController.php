<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMenus = Menu::count();

        $totalCategories = Category::count();

        $totalOrders = Order::count();

        $pendingOrders = Order::where(
            'status',
            'pending_payment'
        )->count();

        return view('admin.dashboard', compact(
            'totalMenus',
            'totalCategories',
            'totalOrders',
            'pendingOrders'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil maksimal 3 menu yang tersedia
        $menus = Menu::with('category')
            ->where('is_available', true)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('menus'));
    }
}

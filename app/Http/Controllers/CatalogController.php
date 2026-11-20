<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // Menampilkan daftar menu
    public function index(Request $request)
    {
        $query = Menu::with('category')
            ->where('is_available', true);

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $menus = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('catalog.index', compact(
            'menus',
            'categories'
        ));
    }

    // Menampilkan detail menu
    public function show(Menu $menu)
    {
        abort_unless($menu->is_available, 404);

        $menu->load('category');

        return view('catalog.show', compact('menu'));
    }
}

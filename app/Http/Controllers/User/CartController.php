<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Menampilkan keranjang
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $menus = Menu::whereIn('id', array_keys($cart))
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(function ($data, $id) use ($menus) {

            $menu = $menus->get((int) $id);

            if (!$menu) {
                return null;
            }

            return [
                'menu' => $menu,
                'quantity' => (int) ($data['quantity'] ?? 0),
            ];
        })->filter(function ($item) {
            return $item !== null && $item['quantity'] > 0;
        })->values();

        // Hanya mempertahankan menu yang masih tersedia
        $validCart = [];

        foreach ($items as $item) {
            $validCart[(string) $item['menu']->id] = [
                'quantity' => $item['quantity'],
            ];
        }

        $request->session()->put('cart', $validCart);

        $subtotal = $items->sum(function ($item) {
            return $item['menu']->price * $item['quantity'];
        });

        return view('user.cart.index', compact(
            'items',
            'subtotal'
        ));
    }

    // Menambahkan menu ke keranjang
    public function add(Request $request, Menu $menu)
    {
        if (!$menu->is_available) {
            return back()->with('error', 'Menu tidak tersedia.');
        }

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);

        $cart = $request->session()->get('cart', []);

        $id = (string) $menu->id;

        $currentQuantity = (int) ($cart[$id]['quantity'] ?? 0);

        if ($currentQuantity + $quantity > 100) {
            return back()->with(
                'error',
                'Maksimal pemesanan 100 porsi untuk satu menu.'
            );
        }

        $cart[$id] = [
            'quantity' => $currentQuantity + $quantity,
        ];

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('user.cart.index')
            ->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }

    // Memperbarui jumlah menu
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        if (!$menu->is_available) {
            return back()->with('error', 'Menu tidak tersedia.');
        }

        $cart = $request->session()->get('cart', []);

        $id = (string) $menu->id;

        if (!isset($cart[$id])) {
            return back()->with('error', 'Menu tidak ditemukan di keranjang.');
        }

        $cart[$id]['quantity'] = (int) $validated['quantity'];

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Jumlah pesanan berhasil diperbarui.');
    }

    // Menghapus menu dari keranjang
    public function remove(Request $request, Menu $menu)
    {
        $cart = $request->session()->get('cart', []);

        unset($cart[(string) $menu->id]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Menu dihapus dari keranjang.');
    }
}

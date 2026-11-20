<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\ValidationException;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
    public function create(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('catalog.index')
                ->with('error', 'Keranjang masih kosong.');
        }

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
                'quantity' => (int) $data['quantity'],
                'subtotal' => $menu->price * (int) $data['quantity'],
            ];
        })->filter()->values();

        if ($items->isEmpty()) {
            return redirect()
                ->route('user.cart.index')
                ->with('error', 'Menu dalam keranjang sudah tidak tersedia.');
        }

        $subtotal = $items->sum('subtotal');

        return view('user.checkout.create', compact(
            'items',
            'subtotal'
        ));
    }

    // Memproses checkout
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],

            'customer_phone' => ['required', 'string', 'max:20'],

            'event_date' => ['required', 'date', 'after_or_equal:today'],

            'event_time' => ['nullable', 'date_format:H:i'],

            'delivery_type' => ['required', 'in:delivery,pickup'],

            'delivery_address' => [
                'required_if:delivery_type,delivery',
                'nullable',
                'string',
                'max:1000',
            ],

            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('user.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($validated, $cart) {

            $menus = Menu::whereIn('id', array_keys($cart))
                ->where('is_available', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Memastikan seluruh menu masih tersedia
            if ($menus->count() !== count($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada menu yang sudah tidak tersedia. Silakan periksa keranjang.',
                ]);
            }

            $subtotal = 0;

            // Menghitung harga dari database
            foreach ($cart as $id => $data) {

                $menu = $menus->get((int) $id);

                $quantity = (int) ($data['quantity'] ?? 0);

                if (!$menu || $quantity < 1 || $quantity > 100) {
                    throw ValidationException::withMessages([
                        'cart' => 'Data keranjang tidak valid. Silakan ulangi pemesanan.',
                    ]);
                }

                $subtotal += (float) $menu->price * $quantity;
            }

            // Biaya pengiriman sementara
            $deliveryFee = $validated['delivery_type'] === 'delivery'
                ? 15000
                : 0;

            $total = $subtotal + $deliveryFee;

            // Membuat pesanan
            $order = Order::create([

                'user_id' => auth()->id(),

                'order_code' => 'DG-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),

                'event_date' => $validated['event_date'],
                'event_time' => $validated['event_time'] ?? null,

                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],

                'delivery_type' => $validated['delivery_type'],

                'delivery_address' => $validated['delivery_type'] === 'delivery'
                    ? $validated['delivery_address']
                    : null,

                'notes' => $validated['notes'] ?? null,

                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_price' => $total,

                'status' => 'pending_payment',
            ]);

            // Menyimpan detail setiap menu
            foreach ($cart as $id => $data) {

                $menu = $menus->get((int) $id);

                $quantity = (int) $data['quantity'];

                $order->items()->create([

                    'menu_id' => $menu->id,

                    'menu_name' => $menu->name,

                    'unit_price' => $menu->price,

                    'quantity' => $quantity,

                    'subtotal' => $menu->price * $quantity,
                ]);
            }

            // Membuat catatan pembayaran awal
            $order->payment()->create([

                'method' => 'bank_transfer',

                'amount' => $total,

                'status' => 'waiting_payment',

            ]);

            return $order;
        }, attempts: 3);

        // Kosongkan keranjang setelah transaksi berhasil
        $request->session()->forget('cart');

        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.');
    }
}

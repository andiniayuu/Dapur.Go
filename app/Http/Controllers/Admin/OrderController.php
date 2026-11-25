<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Notifications\OrderUpdateNotification;

class OrderController extends Controller
{
    // Menampilkan seluruh pesanan
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment'])->latest();

        // Pencarian kode pesanan atau nama pelanggan
        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Filter status pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // Menampilkan detail pesanan
    public function show(Order $order)
    {
        $order->load(['user', 'items', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    // Menampilkan bukti pembayaran secara privat
    public function paymentProof(Order $order)
    {
        $order->load('payment');

        $path = $order->payment?->proof_path;

        abort_unless(
            $path && Storage::disk('local')->exists($path),
            404,
            'Bukti pembayaran tidak ditemukan.'
        );

        return response()->file(
            Storage::disk('local')->path($path)
        );
    }

    // Menyetujui pembayaran
    public function approvePayment(Order $order)
    {
        DB::transaction(function () use ($order) {

            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $payment = $lockedOrder->payment()
                ->lockForUpdate()
                ->first();

            if (
                !$payment ||
                $payment->status !== 'waiting_verification' ||
                !$payment->proof_path
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Pembayaran belum memiliki bukti yang menunggu verifikasi.',
                ]);
            }

            // Konfirmasi pembayaran
            $payment->update([
                'status' => 'paid',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_note' => null,
            ]);

            // Pesanan mulai diproses
            $lockedOrder->update([
                'status' => 'processing',
            ]);

            // Kirim notifikasi ke pengguna
            $order->user->notify(new OrderUpdateNotification(
                'Pembayaran Berhasil',
                'Pembayaran pesanan ' . $order->order_code
                    . ' telah dikonfirmasi. Pesanan sedang diproses.',
                route('user.orders.show', $order)
            ));
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pembayaran berhasil dikonfirmasi. Pesanan siap diproses.');
    }

    // Menolak bukti pembayaran
    public function rejectPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'rejection_note' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($order, $validated) {

            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            $payment = $lockedOrder->payment()
                ->lockForUpdate()
                ->first();

            if (
                !$payment ||
                $payment->status !== 'waiting_verification'
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Tidak ada pembayaran yang menunggu verifikasi.',
                ]);
            }

            $payment->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_note' => $validated['rejection_note'],
            ]);

            // Pengguna dapat mengunggah bukti pembayaran kembali
            $lockedOrder->update([
                'status' => 'pending_payment',
            ]);

            // Kirim notifikasi ke pengguna bahwa pembayaran ditolak
            $order->user->notify(new OrderUpdateNotification(
                'Pembayaran Ditolak',
                'Pembayaran pesanan ' . $order->order_code
                    . ' ditolak. Silakan periksa alasan penolakan dan unggah ulang bukti pembayaran.',
                route('user.orders.show', $order)
            ));
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Pembayaran ditolak. Pengguna dapat mengirim bukti baru.');
    }

    // Memperbarui status pesanan
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:ready,delivering,completed',
            ],
        ]);

        $currentStatus = $order->status;
        $newStatus = $validated['status'];

        // Aturan perpindahan status
        $transitions = [
            'processing' => ['ready'],
            'ready' => ['delivering', 'completed'],
            'delivering' => ['completed'],
        ];

        $allowed = $transitions[$currentStatus] ?? [];

        if (!in_array($newStatus, $allowed, true)) {
            return back()->withErrors([
                'status' => 'Perubahan status pesanan tidak diperbolehkan.',
            ]);
        }

        // Pesanan pickup tidak melewati status pengiriman
        if (
            $order->delivery_type === 'pickup' &&
            $newStatus === 'delivering'
        ) {
            return back()->withErrors([
                'status' => 'Pesanan ambil sendiri tidak menggunakan status pengiriman.',
            ]);
        }

        // Pesanan delivery harus melewati status dalam pengiriman
        if (
            $order->delivery_type === 'delivery' &&
            $currentStatus === 'ready' &&
            $newStatus === 'completed'
        ) {
            return back()->withErrors([
                'status' => 'Pesanan antar harus melewati status dalam pengiriman.',
            ]);
        }

        if (!$order->payment || $order->payment->status !== 'paid') {
            return back()->withErrors([
                'status' => 'Pesanan hanya dapat diproses setelah pembayaran dikonfirmasi.',
            ]);
        }

        $order->update([
            'status' => $newStatus,
        ]);

        // Kirim notifikasi ke pengguna tentang perubahan status pesanan
        $order->user->notify(new OrderUpdateNotification(
            'Status Pesanan Diperbarui',
            'Status pesanan ' . $order->order_code
                . ' sekarang: '
                . ucwords(str_replace('_', ' ', $newStatus)),
            route('user.orders.show', $order)
        ));

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}

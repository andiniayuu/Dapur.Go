<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Mengunggah bukti pembayaran
    public function upload(Request $request, Order $order)
    {
        // Pengguna hanya boleh mengakses pesanan sendiri
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('payment');

        $payment = $order->payment;

        if (!$payment) {
            abort(404, 'Data pembayaran tidak ditemukan.');
        }

        // Pembayaran yang sudah lunas tidak dapat diunggah ulang
        if (!in_array($payment->status, [
            'waiting_payment',
            'rejected',
        ], true)) {
            return back()->with(
                'error',
                'Bukti pembayaran tidak dapat diunggah pada status ini.'
            );
        }

        $validated = $request->validate([
            'proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:3072',
            ],
        ], [
            'proof.required' => 'Silakan pilih bukti pembayaran.',
            'proof.mimes' => 'Format file harus JPG, PNG, WEBP, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 3 MB.',
        ]);

        $oldProof = $payment->proof_path;

        // Simpan bukti pembayaran di penyimpanan privat
        $path = $validated['proof']->store(
            'payment-proofs',
            'local'
        );

        try {

            $payment->update([
                'method' => 'bank_transfer',
                'amount' => $order->total_price,
                'proof_path' => $path,
                'status' => 'waiting_verification',
                'verified_by' => null,
                'verified_at' => null,
                'rejection_note' => null,
            ]);
        } catch (\Throwable $e) {

            Storage::disk('local')->delete($path);

            throw $e;
        }

        // Hapus bukti lama setelah penggantian berhasil
        if ($oldProof) {
            Storage::disk('local')->delete($oldProof);
        }

        return back()->with(
            'success',
            'Bukti pembayaran berhasil dikirim. Menunggu verifikasi Admin.'
        );
    }
}

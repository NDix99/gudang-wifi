<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\StockHistory;
use App\Enums\CartStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store()
    {
        // Cek apakah ada cart yang siap untuk diproses (Draft saja)
        $cartsToProcess = Cart::where('user_id', Auth::id())
            ->where('status', CartStatus::Draft)
            ->get();

        if($cartsToProcess->isEmpty()){
            return back()->with('toast_error', 'Tidak ada item untuk diproses');
        }

        // Update status cart dari Draft ke Pending (untuk admin approval)
        foreach($cartsToProcess as $cart) {
            $cart->update([
                'status' => CartStatus::Pending
            ]);
        }

        return back()->with('toast_success', 'Pesanan berhasil dikirim ke admin untuk persetujuan. Silakan tunggu konfirmasi dari admin.');
    }
}
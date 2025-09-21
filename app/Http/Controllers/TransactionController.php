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

        // Filter hanya cart yang stoknya mencukupi
        $approvedCarts = $cartsToProcess->filter(function($cart) {
            $product = Product::find($cart->product_id);
            return $product && $product->quantity >= $cart->quantity;
        });

        if($approvedCarts->isEmpty()){
            return back()->with('toast_error', 'Tidak ada item dengan stok yang mencukupi untuk diproses');
        }

        $length = 8;
        $random = '';

        for($i = 0; $i < $length; $i++){
            $random .= rand(0,1) ? rand(0,9) : chr(rand(ord('a'), ord('z')));
        }

        $invoice = 'INV-'.Str::upper($random);

        $transaction = Transaction::create([
            'invoice' => $invoice,
            'user_id' => Auth::id(),
        ]);

        foreach($approvedCarts as $cart){
            $product = Product::find($cart->product_id);
            $quantityBefore = $product->quantity;
            
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
            ]);
            
            // Kurangi stok produk
            $product->decrement('quantity', $cart->quantity);
            $quantityAfter = $product->fresh()->quantity;
            
            // Catat histori stok keluar dari transaksi customer
            StockHistory::create([
                'product_id' => $cart->product_id,
                'quantity_change' => -$cart->quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'action' => 'out',
                'note' => 'Barang keluar dari transaksi customer- Invoice: ' . $invoice,
                'user_id' => Auth::id(),
            ]);
        }

        // Hapus cart yang sudah diproses
        Cart::where('user_id', Auth::id())
            ->where('status', CartStatus::Draft)
            ->delete();

        return redirect(route('landing'))->with('toast_success', 'Terimakasih! Pesanan Anda berhasil diproses. Invoice: ' . $invoice);
    }
}

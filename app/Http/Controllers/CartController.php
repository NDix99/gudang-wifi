<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\CartStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Hanya tampilkan cart dengan status Draft, Pending, Rejected, dan OutOfStock
        // Cart yang sudah Approved akan dihapus dan dibuat order tracking
        $carts = Cart::where('user_id', Auth::id())
            ->whereIn('status', [CartStatus::Draft, CartStatus::Pending, CartStatus::Rejected, CartStatus::OutOfStock])
            ->latest()
            ->get();

        $grandQuantity = $carts->sum('quantity');

        if($carts->count() > 0){
            return view('landing.cart.index', compact('carts', 'grandQuantity'));
        }
        return back()->with('toast_error', 'Keranjang anda kosong');
    }

    public function store(Product $product)
    {
        $user = Auth::user();


        $alreadyInCart = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('status', CartStatus::Draft)
            ->first();

        if($alreadyInCart){
            return back()->with('toast_error', 'Produk sudah ada didalam keranjang');
        }

        // Cek stok tersedia
        if($product->quantity <= 0){
            $cart = $user->carts()->create([
                'product_id' => $product->id,
                'quantity' => '1',
                'status' => CartStatus::OutOfStock,
                'admin_note' => 'Stok produk kosong'
            ]);
            return redirect(route('cart.index'))
                ->with('toast_warning', 'Produk ditambahkan ke keranjang namun stok kosong.');
        }

        $cart = $user->carts()->create([
            'product_id' => $product->id,
            'quantity' => '1',
            'status' => CartStatus::Draft,
        ]);
        return redirect(route('cart.index'))
            ->with('toast_success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, Cart $cart)
    {
        // Hanya bisa update jika status draft atau pending
        if($cart->status !== CartStatus::Draft && $cart->status !== CartStatus::Pending){
            return back()->with('toast_error', 'Tidak dapat mengubah item yang sudah diproses admin');
        }

        $product = Product::whereId($cart->product_id)->first();

        if($product->quantity < $request->quantity){
            $cart->update([
                'quantity' => $request->quantity,
                'status' => CartStatus::OutOfStock,
                'admin_note' => 'Stok tidak mencukupi'
            ]);
            return back()->with('toast_warning', 'Jumlah produk diubah namun stok tidak mencukupi.');
        }else{
            $cart->update([
                'quantity' => $request->quantity,
            ]);
            return back()->with('toast_success', 'Jumlah produk berhasil diubah');
        }
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        if($cart->count() >= 1){
            return back()->with('toast_success', 'Produk berhasil dikeluarkan dari keranjang');
        }else{
            return redirect(route('landing'))->with('toast_success', 'Keranjang anda kosong');
        }
    }

    public function submitToAdmin()
    {
        $user = Auth::user();
        $draftCarts = Cart::where('user_id', Auth::id())
            ->where('status', CartStatus::Draft)
            ->with('product')
            ->get();


        if($draftCarts->isEmpty()){
            return back()->with('toast_error', 'Tidak ada item draft untuk dikirim ke admin');
        }

        // Update status cart dari Draft ke Pending (untuk admin approval)
        foreach($draftCarts as $cart) {
            $cart->update([
                'status' => CartStatus::Pending
            ]);
        }

        return back()->with('toast_success', 'Keranjang berhasil dikirim ke admin untuk persetujuan.');
    }

    public function order(Product $product)
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->where('name', $product->name)->get();

        foreach($orders as $order){
            $item = Product::where('name', $order->name)->where('quantity', $order->quantity)->first();

            $order->update([
                'status' => OrderStatus::Done,
            ]);

            $user->carts()->create([
                'product_id' => $product->id,
                'quantity' => $item->quantity,
            ]);
        }

        return redirect(route('cart.index'))->with('toast_success', 'Produk berhasil ditambahkan keranjang');
    }
}

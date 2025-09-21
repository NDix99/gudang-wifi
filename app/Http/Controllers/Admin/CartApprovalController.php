<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Enums\CartStatus;
use Illuminate\Http\Request;

class CartApprovalController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['user', 'product'])
            ->whereIn('status', ['Menunggu Persetujuan', 'Stok Kosong'])
            ->latest()
            ->get();

        return view('admin.cart-approval.index', compact('carts'));
    }

    public function approve(Cart $cart)
    {
        $product = Product::find($cart->product_id);
        
        // Cek stok tersedia
        if($product->quantity < $cart->quantity){
            $cart->update([
                'status' => CartStatus::OutOfStock,
                'admin_note' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->quantity
            ]);
            return back()->with('toast_warning', 'Stok tidak mencukupi untuk ' . $product->name);
        }

        // Buat order tracking
        \App\Models\Order::create([
            'user_id' => $cart->user_id,
            'name' => $product->name,
            'quantity' => $cart->quantity,
            'status' => \App\Enums\OrderStatus::Verified,
            'unit' => 'qty'
        ]);

        // Hapus cart dari keranjang user
        $cart->delete();

        return back()->with('toast_success', 'Permintaan ' . $product->name . ' disetujui dan dipindahkan ke tracking pesanan');
    }

    public function reject(Request $request, Cart $cart)
    {
        $request->validate([
            'admin_note' => 'required|string|max:255'
        ]);

        $cart->update([
            'status' => CartStatus::Rejected,
            'admin_note' => $request->admin_note
        ]);

        return back()->with('toast_success', 'Permintaan ' . $cart->product->name . ' ditolak');
    }

    public function updateNote(Request $request, Cart $cart)
    {
        $request->validate([
            'admin_note' => 'required|string|max:255'
        ]);

        $cart->update([
            'admin_note' => $request->admin_note
        ]);

        return back()->with('toast_success', 'Catatan admin berhasil diupdate');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\StockHistory;
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

        // Kurangi stok produk
        $quantityBefore = $product->quantity;
        $product->decrement('quantity', $cart->quantity);
        $quantityAfter = $product->fresh()->quantity;

        // Catat histori stok keluar
        StockHistory::create([
            'product_id' => $cart->product_id,
            'quantity_change' => -$cart->quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'action' => 'out',
            'note' => 'Barang keluar dari approval admin untuk user: ' . $cart->user->name,
            'user_id' => auth()->id(),
        ]);

        // Buat order tracking
        \App\Models\Order::create([
            'user_id' => $cart->user_id,
            'name' => $product->name,
            'quantity' => $cart->quantity,
            'status' => \App\Enums\OrderStatus::Verified,
            'unit' => $product->unit
        ]);

        // Update status cart ke Approved dan hapus
        $cart->update([
            'status' => CartStatus::Approved,
            'admin_note' => 'Disetujui dan diproses'
        ]);

        // Hapus cart yang sudah diproses
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
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(10);

        return view('admin.stock.index', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $quantityBefore = $product->quantity;

        $product->update([
            'quantity' => $request->quantity,
        ]);

        $quantityAfter = $product->quantity;

        StockHistory::create([
            'product_id' => $product->id,
            'quantity_change' => $quantityAfter - $quantityBefore,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'action' => ($quantityAfter - $quantityBefore) >= 0 ? 'in' : 'adjust',
            'note' => 'Update stok manual',
            'user_id' => auth()->id(),
        ]);

        return back()->with('toast_success', 'Berhasil Menambahkan Stok Produk');
    }

    public function report()
    {
        $products = Product::paginate(10);

        return view('admin.stock.report', compact('products'));
    }

    /**
     * Update minimum stock for a product
     */
    public function updateMinimum(Request $request, $id)
    {
        $request->validate([
            'minimum_stock' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'minimum_stock' => $request->minimum_stock,
        ]);

        return back()->with('toast_success', 'Minimum stok berhasil diupdate');
    }
}

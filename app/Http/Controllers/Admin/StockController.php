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
    public function index(Request $request)
    {
        $searchKeyword = $request->get('search');

        $products = Product::with(['supplier', 'category'])
            ->when($searchKeyword, function ($query) use ($searchKeyword) {
                $query->where(function ($subQuery) use ($searchKeyword) {
                    $subQuery->where('name', 'like', "%{$searchKeyword}%")
                        ->orWhere('unit', 'like', "%{$searchKeyword}%")
                        ->orWhereHas('supplier', function ($supplierQuery) use ($searchKeyword) {
                            $supplierQuery->where('name', 'like', "%{$searchKeyword}%");
                        })
                        ->orWhereHas('category', function ($categoryQuery) use ($searchKeyword) {
                            $categoryQuery->where('name', 'like', "%{$searchKeyword}%");
                        });
                });
            })
            ->paginate(10)
            ->withQueryString();

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
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($id);

        $quantityBefore = $product->quantity;

        // Tambah stok, bukan overwrite
        $product->increment('quantity', (int) $request->quantity);

        $quantityAfter = $product->fresh()->quantity;

        StockHistory::create([
            'product_id' => $product->id,
            'quantity_change' => $quantityAfter - $quantityBefore,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'action' => 'in',
            'note' => 'Penambahan stok manual',
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

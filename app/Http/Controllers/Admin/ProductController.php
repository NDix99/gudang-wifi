<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockHistory;
use App\Traits\HasImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use HasImage;

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

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::get();

        $categories = Category::get();

        return view('admin.product.create', compact('suppliers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $image = $this->uploadImage($request, $path = 'products/', $name = 'image');

        $product = Product::create([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'name' => $request->name,
            'image' => $image->hashName(),
            'unit' => $request->unit,
            'minimum_stock' => $request->minimum_stock ?? 10,
        ]);

        // Catat histori barang masuk saat produk baru dibuat (qty awal 0)
        StockHistory::create([
            'product_id' => $product->id,
            'quantity_change' => 0,
            'quantity_before' => 0,
            'quantity_after' => 0,
            'action' => 'in',
            'note' => 'Produk baru dibuat',
            'user_id' => auth()->id(),
        ]);

        return redirect((route('admin.product.index')))->with('toast_success', 'Kategori Berhasil Ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::get();

        $categories = Category::get();

        return view('admin.product.edit', compact('product', 'suppliers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $image = $this->uploadImage($request, $path = 'products/', $name = 'image');

        $product->update([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'name' => $request->name,
            'unit' => $request->unit,
            'minimum_stock' => $request->minimum_stock ?? 10,
        ]);

        if($request->file($name)){
            $this->updateImage(
                $path = 'products/', $name = 'image', $data = $product, $url = $image->hashName()
            );
        }

        return redirect(route('admin.product.index'))->with('toast_success', 'Produk Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        Storage::disk('public')->delete('products/'. basename($product->image));

        return back()->with('toast_success', 'Kategori Berhasil Dihapus');
    }
}

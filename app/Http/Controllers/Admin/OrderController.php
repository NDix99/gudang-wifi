<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockHistory;
use App\Traits\HasImage;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    use HasImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('user')->paginate(10);

        $categories = Category::get();

        $suppliers = Supplier::get();

        return view('admin.order.index', compact('orders', 'categories', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // Upload image untuk kebutuhan membuat produk dari permintaan
        $image = $this->uploadImage($request, $path = 'products/', $name = 'image');

        if($order->status == OrderStatus::Pending){
            $order->update([
                'status' => OrderStatus::Verified,
            ]);
        }else{
            // Validasi input wajib saat membuat produk dari permintaan
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'name' => 'required|string',
                'unit' => 'required|string',
                'quantity' => 'required|numeric',
                'image' => 'required|mimes:png,jpg,jpeg|max:2048',
                'description' => 'nullable|string',
            ]);

            $product = Product::create([
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'name' => $request->name,
                'image' => $image ? $image->hashName() : null,
                'unit' => $request->unit,
                'description' => $request->description,
                'quantity' => $request->quantity
            ]);

            // Catat histori stok masuk dari permintaan customer
            StockHistory::create([
                'product_id' => $product->id,
                'quantity_change' => $request->quantity,
                'quantity_before' => 0,
                'quantity_after' => $request->quantity,
                'action' => 'in',
                'note' => 'Barang masuk dari permintaan customer',
                'user_id' => auth()->id(),
            ]);

            $order->update([
                'status' => OrderStatus::Success
            ]);
        }

        return back()->with('toast_success', 'Permintaan Barang Berhasil Dikonfirmasi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

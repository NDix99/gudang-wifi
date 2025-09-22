<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    public function index()
    {
        // Ambil data dari Cart (yang masih proses) dan Order (yang sudah disetujui/dst)
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('status', [CartStatus::Draft, CartStatus::Pending, CartStatus::Rejected, CartStatus::OutOfStock])
            ->latest()
            ->get()
            ->map(function (Cart $cart) {
                return [
                    'id' => $cart->id,
                    'type' => 'cart',
                    'name' => $cart->product?->name ?? '-',
                    'quantity' => $cart->quantity,
                    'unit' => 'qty',
                    'status' => $cart->status->value,
                    'created_at' => $cart->created_at,
                    'model' => $cart,
                ];
            });

        $orderItems = Order::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function (Order $order) {
                return [
                    'id' => $order->id,
                    'type' => 'order',
                    'name' => $order->name,
                    'quantity' => $order->quantity,
                    'unit' => $order->unit ?? 'qty',
                    'status' => $order->status->value,
                    'created_at' => $order->created_at,
                    'model' => $order,
                ];
            });

        $merged = $cartItems->concat($orderItems)
            ->sortByDesc('created_at')
            ->values();

        // Paginate manual
        $perPage = 10;
        $page = request()->get('page', 1);
        $paged = new LengthAwarePaginator(
            $merged->forPage($page, $perPage),
            $merged->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $stats = [
            'total' => $merged->count(),
            'pending' => $cartItems->where('status', CartStatus::Pending->value)->count(),
            'approved' => $orderItems->where('status', OrderStatus::Verified->value)->count(),
            'rejected' => $cartItems->where('status', CartStatus::Rejected->value)->count(),
            'out_of_stock' => $cartItems->where('status', CartStatus::OutOfStock->value)->count(),
        ];

        $orders = $paged; // agar kompatibel dengan view lama

        return view('customer.order-tracking.index', compact('orders', 'stats'));
    }

    public function show(Cart $cart)
    {
        if($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.order-tracking.show', [
            'cart' => $cart,
        ]);
    }

    public function showOrder(Order $order)
    {
        if($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.order-tracking.show-order', [
            'order' => $order,
        ]);
    }
}
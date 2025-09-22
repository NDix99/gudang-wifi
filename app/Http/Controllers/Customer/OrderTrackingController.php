<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Enums\CartStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    public function index()
    {
        $orders = Cart::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Cart::where('user_id', Auth::id())->count(),
            'pending' => Cart::where('user_id', Auth::id())->where('status', CartStatus::Pending)->count(),
            'approved' => Cart::where('user_id', Auth::id())->where('status', CartStatus::Approved)->count(),
            'rejected' => Cart::where('user_id', Auth::id())->where('status', CartStatus::Rejected)->count(),
            'out_of_stock' => Cart::where('user_id', Auth::id())->where('status', CartStatus::OutOfStock)->count(),
        ];

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
}
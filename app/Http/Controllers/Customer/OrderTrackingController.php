<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Order::where('user_id', Auth::id())->count(),
            'pending' => Order::where('user_id', Auth::id())->where('status', OrderStatus::Pending)->count(),
            'verified' => Order::where('user_id', Auth::id())->where('status', OrderStatus::Verified)->count(),
            'success' => Order::where('user_id', Auth::id())->where('status', OrderStatus::Success)->count(),
            'done' => Order::where('user_id', Auth::id())->where('status', OrderStatus::Done)->count(),
        ];

        return view('customer.order-tracking.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        // Pastikan order milik user yang sedang login
        if($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('customer.order-tracking.show', compact('order'));
    }
}
<?php

namespace App\Http\Controllers\Customer;

use App\Models\Transaction;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = Auth::id();

        // Ambil data dari stock_histories untuk barang keluar (action = 'out') milik user ini
        $stockHistories = StockHistory::with(['product.category', 'user'])
            ->where('action', 'out')
            ->where('user_id', $user)
            ->latest()
            ->paginate(10);

        $grandTransaction = StockHistory::where('action', 'out')->where('user_id', $user)->count();

        $grandQuantity = StockHistory::where('action', 'out')->where('user_id', $user)->sum('quantity_change');

        return view('customer.transaction.index', compact('stockHistories', 'grandTransaction', 'grandQuantity'));
    }
}

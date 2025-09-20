<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rent;
use App\Models\Transaction;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function product()
    {
        // Ambil data dari stock_histories untuk barang keluar (action = 'out')
        $stockHistories = StockHistory::with(['product.category', 'user'])
            ->where('action', 'out')
            ->latest()
            ->paginate(10);

        $grandQuantity = StockHistory::where('action', 'out')->sum('quantity_change');

        return view('admin.transaction.product', compact('stockHistories', 'grandQuantity'));
    }

    public function vehicle()
    {
        $rents = Rent::with('vehicle', 'user')->when(request()->q, function($search){
            $search = $search->whereHas('user', function($query){
                $query->where('name', 'like', '%'.request()->q.'%');
            })->orWhereHas('vehicle', function($query){
                $query->where('name', 'like', '%'.request()->q.'%');
            });
        })->latest()->paginate(10);

        return view('admin.transaction.vehicle', compact('rents'));
    }
}

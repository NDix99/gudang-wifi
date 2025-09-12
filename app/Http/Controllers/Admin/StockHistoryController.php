<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockHistory;
use App\Http\Controllers\Controller;

class StockHistoryController extends Controller
{
    public function index()
    {
        $histories = StockHistory::with(['product'])->latest()->paginate(15);
        return view('admin.stock.history', compact('histories'));
    }
}



<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        return view('penjualan.index', compact('items'));
    }
}

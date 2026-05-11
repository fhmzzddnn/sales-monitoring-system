<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $availableSales = Sale::where('payment_status', 'Belum Dibayar')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembayaran.index', compact('availableSales'));
    }
}

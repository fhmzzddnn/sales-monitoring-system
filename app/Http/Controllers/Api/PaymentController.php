<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('sale');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        return DataTables::of($query->orderBy('created_at', 'desc'))
            ->editColumn('amount_paid', function ($payment) {
                return $payment->amount_paid;
            })
            ->editColumn('created_at', function ($payment) {
                return $payment->created_at->format('Y-m-d H:i');
            })
            ->addColumn('sale_code', function ($payment) {
                return $payment->sale->code;
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sale_id' => 'required|unique:payments,sale_id|exists:sales,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sale = Sale::findOrFail($request->sale_id);

        if ($request->amount_paid > $sale->total_price) {
            return response()->json(['errors' => ['amount_paid' => ['Jumlah bayar tidak boleh melebihi total harga penjualan.']]], 422);
        }

        return DB::transaction(function () use ($request, $sale) {
            $paymentStatus = $request->amount_paid == $sale->total_price ? 'Lunas' : 'Belum Lunas';
            $saleStatus = $request->amount_paid == $sale->total_price ? 'Sudah Dibayar' : 'Dibayar Sebagian';

            $payment = Payment::create([
                'code' => 'PAY-' . strtoupper(dechex(round(microtime(true) * 1000))),
                'sale_id' => $sale->id,
                'amount_paid' => $request->amount_paid,
                'payment_status' => $paymentStatus,
            ]);

            $sale->update(['payment_status' => $saleStatus]);

            return response()->json(['message' => 'Pembayaran berhasil disimpan', 'payment' => $payment]);
        });
    }

    public function show(Payment $pembayaran)
    {
        return response()->json([
            'payment' => $pembayaran,
            'sale' => $pembayaran->sale,
            'items' => $pembayaran->sale->items()->with('item')->get()
        ]);
    }

    public function update(Request $request, Payment $pembayaran)
    {
        if ($pembayaran->payment_status === 'Lunas') {
            return response()->json(['message' => 'Tidak dapat mengubah pembayaran yang sudah lunas.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount_paid' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sale = $pembayaran->sale;

        if ($request->amount_paid > $sale->total_price) {
            return response()->json(['errors' => ['amount_paid' => ['Jumlah bayar tidak boleh melebihi total harga penjualan.']]], 422);
        }

        return DB::transaction(function () use ($request, $pembayaran, $sale) {
            $paymentStatus = $request->amount_paid == $sale->total_price ? 'Lunas' : 'Belum Lunas';
            $saleStatus = $request->amount_paid == $sale->total_price ? 'Sudah Dibayar' : 'Dibayar Sebagian';

            $pembayaran->update([
                'amount_paid' => $request->amount_paid,
                'payment_status' => $paymentStatus,
            ]);

            $sale->update(['payment_status' => $saleStatus]);

            return response()->json(['message' => 'Pembayaran berhasil diperbarui', 'payment' => $pembayaran]);
        });
    }

    public function destroy(Payment $pembayaran)
    {
        return DB::transaction(function () use ($pembayaran) {
            $pembayaran->sale->update(['payment_status' => 'Belum Dibayar']);
            $pembayaran->delete();
            return response()->json(['message' => 'Pembayaran berhasil dihapus']);
        });
    }
}

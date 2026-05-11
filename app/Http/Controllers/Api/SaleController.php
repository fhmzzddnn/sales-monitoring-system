<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::query();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        return DataTables::of($query->orderBy('created_at', 'desc'))
            ->editColumn('total_price', function ($sale) {
                return $sale->total_price;
            })
            ->editColumn('created_at', function ($sale) {
                return $sale->created_at->format('Y-m-d H:i');
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $totalPrice = 0;
            $saleItems = [];

            foreach ($request->items as $itemData) {
                $item = \App\Models\Item::findOrFail($itemData['item_id']);
                $subtotal = $item->price * $itemData['quantity'];
                $totalPrice += $subtotal;

                $saleItems[] = [
                    'item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $item->price,
                    'subtotal' => $subtotal,
                ];
            }

            $sale = Sale::create([
                'code' => 'SLS-' . strtoupper(dechex(round(microtime(true) * 1000))),
                'total_price' => $totalPrice,
                'payment_status' => 'Belum Dibayar',
            ]);

            foreach ($saleItems as $saleItem) {
                $sale->items()->create($saleItem);
            }

            return response()->json(['message' => 'Penjualan berhasil disimpan', 'sale' => $sale]);
        });
    }

    public function show(Sale $penjualan)
    {
        return response()->json([
            'sale' => $penjualan,
            'items' => $penjualan->items()->with('item')->get()
        ]);
    }

    public function update(Request $request, Sale $penjualan)
    {
        if ($penjualan->payment_status !== 'Belum Dibayar') {
            return response()->json(['message' => 'Tidak dapat mengubah penjualan yang sudah dibayar atau dibayar sebagian'], 403);
        }

        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $penjualan) {
            $totalPrice = 0;
            
            // Delete old items
            $penjualan->items()->delete();

            foreach ($request->items as $itemData) {
                $item = \App\Models\Item::findOrFail($itemData['item_id']);
                $subtotal = $item->price * $itemData['quantity'];
                $totalPrice += $subtotal;

                $penjualan->items()->create([
                    'item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $item->price,
                    'subtotal' => $subtotal,
                ]);
            }

            $penjualan->update(['total_price' => $totalPrice]);

            return response()->json(['message' => 'Penjualan berhasil diperbarui', 'sale' => $penjualan]);
        });
    }

    public function destroy(Sale $penjualan)
    {
        if ($penjualan->payment_status !== 'Belum Dibayar') {
            return response()->json(['message' => 'Tidak dapat menghapus penjualan yang sudah dibayar atau dibayar sebagian'], 403);
        }

        $penjualan->delete();
        return response()->json(['message' => 'Penjualan berhasil dihapus']);
    }
}

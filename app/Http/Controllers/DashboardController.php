<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats
        $totalTransactions = Sale::count();
        $totalRevenue = Sale::sum('total_price');
        $totalQuantitySold = SaleItem::sum('quantity');

        // 2. Chart 1: Total Sales in Rupiah per Month (Current Year)
        $monthlyRevenue = Sale::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('SUM(total_price) as revenue')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('revenue', 'month')
        ->all();

        // Ensure months up to current month are present
        $revenueData = [];
        $currentMonth = date('n');
        for ($m = 1; $m <= $currentMonth; $m++) {
            $revenueData[] = (float) ($monthlyRevenue[$m] ?? 0);
        }

        // 3. Chart 2: Total Sales in Quantity per Item
        $items = Item::all();
        $itemQuantityData = [];

        foreach ($items as $item) {
            $monthlyItemQty = SaleItem::where('item_id', $item->id)
                ->select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                    DB::raw('SUM(quantity) as qty')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('qty', 'month')
                ->all();

            $data = [];
            for ($m = 1; $m <= $currentMonth; $m++) {
                $data[] = (int) ($monthlyItemQty[$m] ?? 0);
            }

            $itemQuantityData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'data' => $data,
                'color' => $this->getRandomColor($item->id)
            ];
        }

        return view('dashboard', compact(
            'totalTransactions',
            'totalRevenue',
            'totalQuantitySold',
            'revenueData',
            'itemQuantityData'
        ));
    }

    private function getRandomColor($id)
    {
        $colors = [
            '#6750A4', '#B3261E', '#21005D', '#625B71', '#7D5260',
            '#006A6A', '#984061', '#4F378B', '#0061A4', '#BA1A1A'
        ];
        return $colors[$id % count($colors)];
    }
}

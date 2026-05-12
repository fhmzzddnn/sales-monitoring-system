<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $categories = [
            ['name' => 'Electronics', 'prefix' => 'EL'],
            ['name' => 'Fashion', 'prefix' => 'FS'],
            ['name' => 'Food', 'prefix' => 'FD'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['prefix' => $cat['prefix']], $cat);
        }

        $elCat = Category::where('prefix', 'EL')->first();
        $fsCat = Category::where('prefix', 'FS')->first();
        $fdCat = Category::where('prefix', 'FD')->first();

        // 2. Seed Items
        $items = [
            ['category_id' => $elCat->id, 'code' => 'EL-0001', 'name' => 'Smartphone', 'price' => 5000000],
            ['category_id' => $elCat->id, 'code' => 'EL-0002', 'name' => 'Laptop', 'price' => 12000000],
            ['category_id' => $fsCat->id, 'code' => 'FS-0001', 'name' => 'T-Shirt', 'price' => 150000],
            ['category_id' => $fsCat->id, 'code' => 'FS-0002', 'name' => 'Jeans', 'price' => 300000],
            ['category_id' => $fdCat->id, 'code' => 'FD-0001', 'name' => 'Coffee', 'price' => 50000],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(['code' => $item['code']], $item);
        }

        // 3. Seed Sales and Payments
        $allItemModels = Item::all();
        $smartphone = $allItemModels->where('code', 'EL-0001')->first();
        $laptop = $allItemModels->where('code', 'EL-0002')->first();
        $tshirt = $allItemModels->where('code', 'FS-0001')->first();
        $jeans = $allItemModels->where('code', 'FS-0002')->first();
        $coffee = $allItemModels->where('code', 'FD-0001')->first();

        // Targets: 40, 30, 23, 10, 2
        $targets = [
            $smartphone->id => 40,
            $tshirt->id => 30,
            $coffee->id => 23,
            $jeans->id => 10,
            $laptop->id => 2,
        ];

        $itemsById = [
            $smartphone->id => $smartphone,
            $laptop->id => $laptop,
            $tshirt->id => $tshirt,
            $jeans->id => $jeans,
            $coffee->id => $coffee,
        ];

        // Helper to generate codes like controllers based on a timestamp
        $genSaleCode = fn($ts) => 'SLS-' . strtoupper(dechex($ts * 1000));
        $genPayCode = fn($ts) => 'PAY-' . strtoupper(dechex($ts * 1000));

        $startDate = strtotime('2026-01-01 00:00:00');
        $endDate = strtotime('2026-05-12 23:59:59');

        // We will create 10 sales
        for ($i = 1; $i <= 10; $i++) {
            // Generate a random timestamp within the range
            $timestamp = rand($startDate, $endDate);
            $date = date('Y-m-d H:i:s', $timestamp);
            
            $saleItemsData = [];
            $saleTotalPrice = 0;

            // In each sale, try to pick some items
            foreach ($targets as $itemId => &$remaining) {
                if ($remaining <= 0) continue;

                // Pick a random quantity, but don't take all at once unless it's the last sale
                if ($i == 10) {
                    $qty = $remaining;
                } else {
                    $maxPick = ceil($remaining / (11 - $i)) * 2; // Heuristic to spread it out
                    $qty = rand(0, min($remaining, (int)$maxPick));
                }

                if ($qty > 0) {
                    $item = $itemsById[$itemId];
                    $subtotal = $item->price * $qty;
                    $saleItemsData[] = [
                        'item_id' => $itemId,
                        'quantity' => $qty,
                        'price' => $item->price,
                        'subtotal' => $subtotal,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                    $saleTotalPrice += $subtotal;
                    $remaining -= $qty;
                }
            }

            if (empty($saleItemsData)) continue;

            $status = $i % 3 == 0 ? 'Belum Dibayar' : ($i % 2 == 0 ? 'Dibayar Sebagian' : 'Sudah Dibayar');

            $sale = Sale::create([
                'code' => $genSaleCode($timestamp),
                'total_price' => $saleTotalPrice,
                'payment_status' => $status,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $sale->items()->createMany($saleItemsData);

            if ($status === 'Sudah Dibayar') {
                Payment::create([
                    'code' => $genPayCode($timestamp + 60), // +1 min
                    'sale_id' => $sale->id,
                    'amount_paid' => $saleTotalPrice,
                    'payment_status' => 'Lunas',
                    'created_at' => date('Y-m-d H:i:s', $timestamp + 60),
                    'updated_at' => date('Y-m-d H:i:s', $timestamp + 60),
                ]);
            } elseif ($status === 'Dibayar Sebagian') {
                Payment::create([
                    'code' => $genPayCode($timestamp + 60), // +1 min
                    'sale_id' => $sale->id,
                    'amount_paid' => floor($saleTotalPrice / 2),
                    'payment_status' => 'Belum Lunas',
                    'created_at' => date('Y-m-d H:i:s', $timestamp + 60),
                    'updated_at' => date('Y-m-d H:i:s', $timestamp + 60),
                ]);
            }
        }
    }
}

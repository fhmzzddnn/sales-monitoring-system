<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category');

        return DataTables::of($items)
            ->addColumn('category_name', function ($item) {
                return $item->category->name;
            })
            ->addColumn('action', function ($item) {
                $btn = '<div class="flex gap-2">';
                $btn .= '<button onclick="editItem('.$item->id.')" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>';
                $btn .= '<button onclick="deleteItem('.$item->id.')" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate Item Code
        $category = Category::findOrFail($request->category_id);
        $lastItem = Item::where('category_id', $category->id)->orderBy('id', 'desc')->first();
        $nextNumber = $lastItem ? (int) substr($lastItem->code, strrpos($lastItem->code, '-') + 1) + 1 : 1;
        $itemCode = $category->prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Item::create([
            'category_id' => $request->category_id,
            'code' => $itemCode,
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Item created successfully with code: ' . $itemCode]);
    }

    public function show(Item $item)
    {
        return response()->json(['item' => $item]);
    }

    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Only regenerate code if category changed
        if ($item->category_id != $request->category_id) {
            $category = Category::findOrFail($request->category_id);
            $lastItem = Item::where('category_id', $category->id)->orderBy('id', 'desc')->first();
            $nextNumber = $lastItem ? (int) substr($lastItem->code, strrpos($lastItem->code, '-') + 1) + 1 : 1;
            $itemCode = $category->prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $item->code = $itemCode;
        }

        $item->category_id = $request->category_id;
        $item->name = $request->name;
        $item->price = $request->price;
        $item->save();

        return response()->json(['message' => 'Item updated successfully']);
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}

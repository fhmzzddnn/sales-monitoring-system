<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class ItemController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('master.items.index', compact('categories'));
    }
}

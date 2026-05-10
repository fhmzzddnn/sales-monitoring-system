<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class SettingController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('master.settings.index', compact('permissions'));
    }
}

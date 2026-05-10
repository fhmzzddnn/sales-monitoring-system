<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        return DataTables::of(Role::with('permissions'))
            ->addColumn('permissions_name', function ($role) {
                return $role->permissions->pluck('name')->map(function($name) {
                    return '<span class="bg-[#EADDFF] text-[#21005D] px-2 py-0.5 rounded-full text-[10px] font-semibold mr-1">'.$name.'</span>';
                })->implode('');
            })
            ->addColumn('action', function ($role) {
                return '
                    <div class="flex gap-2">
                        <button onclick="editRole('.$role->id.')" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="deleteRole('.$role->id.')" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>';
            })
            ->rawColumns(['permissions_name', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create(['name' => $request->name]);
        
        $permissions = $request->permissions ?? [];
        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Role created successfully']);
    }

    public function show(Role $role)
    {
        return response()->json([
            'role' => $role,
            'permissions' => $role->permissions->pluck('name')
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update(['name' => $request->name]);
        
        $permissions = $request->permissions ?? [];
        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}

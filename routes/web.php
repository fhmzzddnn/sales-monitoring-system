<?php

use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\ItemController as WebItemController;
use App\Http\Controllers\Web\SettingController as WebSettingController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\ItemController as ApiItemController;
use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\Api\RoleController as ApiRoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\PermissionController as ApiPermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Routes (Web)
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('users', [WebUserController::class, 'index'])->name('users.index')->middleware('permission:user-list');
        Route::get('items', [WebItemController::class, 'index'])->name('items.index')->middleware('permission:item-list');
        Route::get('settings', [WebSettingController::class, 'index'])->name('settings.index')->middleware('permission:setting-manage');
    });

    // API Routes (AJAX)
    Route::prefix('api')->name('api.')->group(function () {
        // ... (User routes remain)
        Route::get('users', [ApiUserController::class, 'index'])->name('users.index')->middleware('permission:user-list');
        Route::post('users', [ApiUserController::class, 'store'])->name('users.store')->middleware('permission:user-create');
        Route::get('users/{user}', [ApiUserController::class, 'show'])->name('users.show')->middleware('permission:user-list');
        Route::put('users/{user}', [ApiUserController::class, 'update'])->name('users.update')->middleware('permission:user-edit');
        Route::delete('users/{user}', [ApiUserController::class, 'destroy'])->name('users.destroy')->middleware('permission:user-delete');

        // Categories
        Route::get('categories', [ApiCategoryController::class, 'index'])->name('categories.index')->middleware('permission:setting-manage');
        Route::post('categories', [ApiCategoryController::class, 'store'])->name('categories.store')->middleware('permission:setting-manage');
        Route::get('categories/{category}', [ApiCategoryController::class, 'show'])->name('categories.show')->middleware('permission:setting-manage');
        Route::put('categories/{category}', [ApiCategoryController::class, 'update'])->name('categories.update')->middleware('permission:setting-manage');
        Route::delete('categories/{category}', [ApiCategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:setting-manage');

        // Roles
        Route::get('roles', [ApiRoleController::class, 'index'])->name('roles.index')->middleware('permission:setting-manage');
        Route::post('roles', [ApiRoleController::class, 'store'])->name('roles.store')->middleware('permission:setting-manage');
        Route::get('roles/{role}', [ApiRoleController::class, 'show'])->name('roles.show')->middleware('permission:setting-manage');
        Route::put('roles/{role}', [ApiRoleController::class, 'update'])->name('roles.update')->middleware('permission:setting-manage');
        Route::delete('roles/{role}', [ApiRoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:setting-manage');

        // Permissions
        Route::get('permissions', [ApiPermissionController::class, 'index'])->name('permissions.index')->middleware('permission:setting-manage');
        Route::post('permissions', [ApiPermissionController::class, 'store'])->name('permissions.store')->middleware('permission:setting-manage');
        Route::get('permissions/{permission}', [ApiPermissionController::class, 'show'])->name('permissions.show')->middleware('permission:setting-manage');
        Route::put('permissions/{permission}', [ApiPermissionController::class, 'update'])->name('permissions.update')->middleware('permission:setting-manage');
        Route::delete('permissions/{permission}', [ApiPermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:setting-manage');

        Route::get('items', [ApiItemController::class, 'index'])->name('items.index')->middleware('permission:item-list');
        Route::post('items', [ApiItemController::class, 'store'])->name('items.store')->middleware('permission:item-create');
        Route::get('items/{item}', [ApiItemController::class, 'show'])->name('items.show')->middleware('permission:item-list');
        Route::put('items/{item}', [ApiItemController::class, 'update'])->name('items.update')->middleware('permission:item-edit');
        Route::delete('items/{item}', [ApiItemController::class, 'destroy'])->name('items.destroy')->middleware('permission:item-delete');
    });
});

require __DIR__.'/auth.php';

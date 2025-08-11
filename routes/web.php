<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\WebAppController;

Route::get('/', function () {
    return redirect('/admin/onboarding');
});

Route::prefix('admin')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('admin.onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('admin.onboarding.store');

    Route::prefix('{slug}')->group(function () {
        Route::get('/menu/categories', [MenuCategoryController::class, 'index'])->name('admin.menu.categories.index');
        Route::post('/menu/categories', [MenuCategoryController::class, 'store'])->name('admin.menu.categories.store');
        Route::put('/menu/categories/{category}', [MenuCategoryController::class, 'update'])->name('admin.menu.categories.update');
        Route::delete('/menu/categories/{category}', [MenuCategoryController::class, 'destroy'])->name('admin.menu.categories.destroy');

        Route::get('/menu/items', [MenuItemController::class, 'index'])->name('admin.menu.items.index');
        Route::post('/menu/items', [MenuItemController::class, 'store'])->name('admin.menu.items.store');
        Route::put('/menu/items/{item}', [MenuItemController::class, 'update'])->name('admin.menu.items.update');
        Route::delete('/menu/items/{item}', [MenuItemController::class, 'destroy'])->name('admin.menu.items.destroy');
    });
});

Route::prefix('w/{slug}')->group(function () {
    Route::get('/', [WebAppController::class, 'index'])->name('webapp.index');
    Route::post('/checkout', [WebAppController::class, 'checkout'])->name('webapp.checkout');
    Route::get('/thanks', [WebAppController::class, 'thanks'])->name('webapp.thanks');
});

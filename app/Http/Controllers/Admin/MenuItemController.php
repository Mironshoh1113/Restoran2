<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    protected function restaurantOrFail(string $slug): Restaurant
    {
        return Restaurant::where('slug', $slug)->firstOrFail();
    }

    public function index(string $slug)
    {
        $restaurant = $this->restaurantOrFail($slug);
        $categories = MenuCategory::where('restaurant_id', $restaurant->id)->orderBy('sort')->get();
        $items = MenuItem::where('restaurant_id', $restaurant->id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.menu.items.index', compact('restaurant', 'categories', 'items'));
    }

    public function store(Request $request, string $slug)
    {
        $restaurant = $this->restaurantOrFail($slug);
        $data = $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'sku' => $data['sku'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        return back()->with('status', 'Mahsulot yaratildi');
    }

    public function update(Request $request, string $slug, MenuItem $item)
    {
        $restaurant = $this->restaurantOrFail($slug);
        abort_unless($item->restaurant_id === $restaurant->id, 404);
        $data = $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $item->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'sku' => $data['sku'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);
        return back()->with('status', 'Mahsulot yangilandi');
    }

    public function destroy(string $slug, MenuItem $item)
    {
        $restaurant = $this->restaurantOrFail($slug);
        abort_unless($item->restaurant_id === $restaurant->id, 404);
        $item->delete();
        return back()->with('status', 'Mahsulot o‘chirildi');
    }
} 
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    protected function restaurantOrFail(string $slug): Restaurant
    {
        return Restaurant::where('slug', $slug)->firstOrFail();
    }

    public function index(string $slug)
    {
        $restaurant = $this->restaurantOrFail($slug);
        $categories = MenuCategory::where('restaurant_id', $restaurant->id)->orderBy('sort')->get();
        return view('admin.menu.categories.index', compact('restaurant', 'categories'));
    }

    public function store(Request $request, string $slug)
    {
        $restaurant = $this->restaurantOrFail($slug);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort' => 'nullable|integer|min:0',
        ]);
        MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'name' => $data['name'],
            'sort' => $data['sort'] ?? 0,
        ]);
        return back()->with('status', 'Kategoriya yaratildi');
    }

    public function update(Request $request, string $slug, MenuCategory $category)
    {
        $restaurant = $this->restaurantOrFail($slug);
        abort_unless($category->restaurant_id === $restaurant->id, 404);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sort' => 'nullable|integer|min:0',
        ]);
        $category->update($data);
        return back()->with('status', 'Kategoriya yangilandi');
    }

    public function destroy(string $slug, MenuCategory $category)
    {
        $restaurant = $this->restaurantOrFail($slug);
        abort_unless($category->restaurant_id === $restaurant->id, 404);
        $category->delete();
        return back()->with('status', 'Kategoriya o‘chirildi');
    }
} 
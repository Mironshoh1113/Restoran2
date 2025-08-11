<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebAppController extends Controller
{
    public function index(string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $categories = MenuCategory::where('restaurant_id', $restaurant->id)
            ->orderBy('sort')
            ->get();
        $items = MenuItem::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->get()
            ->groupBy('category_id');

        return view('webapp.index', compact('restaurant', 'categories', 'items'));
    }

    public function checkout(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $data = $request->validate([
            'chat_id' => 'nullable|string',
            'type' => 'nullable|in:delivery,pickup',
            'cart_json' => 'required|string',
        ]);

        $cart = json_decode($data['cart_json'], true) ?: [];
        if (empty($cart)) {
            return back()->with('status', 'Savatcha bo‘sh');
        }

        $type = $data['type'] ?? 'pickup';
        $chatId = $data['chat_id'] ? (int) $data['chat_id'] : 0;
        $subtotal = 0;

        // Recalculate prices from DB for security
        $normalizedItems = [];
        foreach ($cart as $row) {
            $item = MenuItem::where('restaurant_id', $restaurant->id)
                ->where('id', $row['id'] ?? 0)
                ->first();
            if (!$item) {
                continue;
            }
            $qty = max(1, (int) ($row['qty'] ?? 1));
            $linePrice = $item->price * $qty;
            $subtotal += $linePrice;
            $normalizedItems[] = [
                'item' => $item,
                'qty' => $qty,
                'price' => $item->price,
            ];
        }

        if (empty($normalizedItems)) {
            return back()->with('status', 'Mavjud emas yoki bo‘sh savatcha');
        }

        $deliveryFee = 0;
        $discount = 0;
        $total = $subtotal + $deliveryFee - $discount;

        DB::transaction(function () use ($restaurant, $chatId, $type, $subtotal, $deliveryFee, $discount, $total, $normalizedItems) {
            $order = Order::create([
                'restaurant_id' => $restaurant->id,
                'user_id' => null,
                'chat_id' => $chatId,
                'type' => $type,
                'status' => 'new',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount' => $discount,
                'total' => $total,
                'payment_status' => 'pending',
                'delivery_address_json' => null,
            ]);

            foreach ($normalizedItems as $row) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $row['item']->id,
                    'name' => $row['item']->name,
                    'qty' => $row['qty'],
                    'price' => $row['price'],
                    'options_json' => null,
                ]);
            }
        });

        return redirect()->route('webapp.thanks', ['slug' => $restaurant->slug])
            ->with('status', 'Buyurtma qabul qilindi');
    }

    public function thanks(string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        return view('webapp.thanks', compact('restaurant'));
    }
} 
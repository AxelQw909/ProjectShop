<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;
        $items = $cart ? $cart->items()->with('product')->get() : collect();
        
        return view('cart.index', compact('items'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $cart = Auth::user()->cart;

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $item->product->stock
        ]);

        $item->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Количество обновлено');
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Товар удален из корзины');
    }

    public function getCartTotal()
    {
        $cart = Auth::user()->cart;
        $total = $cart ? $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        }) : 0;
        
        return response()->json(['total' => $total]);
    }
}
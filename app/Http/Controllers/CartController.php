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

        // Проверяем доступность товара
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 
                'Товар "' . $product->name . '" недоступен в количестве ' . $request->quantity . ' шт. Доступно: ' . $product->stock . ' шт.');
        }

        $cart = Auth::user()->cart;

        // Проверяем, есть ли уже этот товар в корзине
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            // Проверяем общее количество
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 
                    'Нельзя добавить больше ' . $product->stock . ' шт. товара "' . $product->name . '"');
            }
            
            $existingItem->update([
                'quantity' => $newQuantity
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

        // Проверяем доступность товара
        if ($item->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 
                'Товар "' . $item->product->name . '" недоступен в количестве ' . $request->quantity . ' шт. Доступно: ' . $item->product->stock . ' шт.');
        }

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
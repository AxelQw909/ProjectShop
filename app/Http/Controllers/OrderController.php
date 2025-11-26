<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create()
    {
        $cart = Auth::user()->cart;
        $items = $cart->items()->with('product')->get();
        
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        return view('orders.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|min:2',
            'address' => 'required|string|min:10',
            'payment_method' => 'required|in:МИР,VISA,MASTERCARD',
            'delivery_method' => 'required|in:Самовывоз,Курьер,Почта',
        ]);

        $cart = Auth::user()->cart;
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        // Проверяем доступность товаров
        foreach ($items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 
                    'Товар "' . $item->product->name . '" недоступен в количестве ' . $item->quantity . ' шт. Доступно: ' . $item->product->stock . ' шт.');
            }
        }

        $deliveryCost = $this->calculateDeliveryCost($request->delivery_method, $cart->total);

        // Используем транзакцию для безопасности данных
        DB::transaction(function () use ($request, $cart, $items, $deliveryCost) {
            // Создание заказа
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'delivery_method' => $request->delivery_method,
                'subtotal' => $cart->total,
                'delivery_cost' => $deliveryCost,
                'total' => $cart->total + $deliveryCost,
                'promo_code' => $request->promo_code,
            ]);

            // Создание элементов заказа и уменьшение количества товаров
            foreach ($items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->quantity * $cartItem->product->price,
                ]);

                // Уменьшаем количество товара на складе
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Очистка корзины
            $cart->items()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Заказ успешно оформлен!');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    private function calculateDeliveryCost($method, $subtotal)
    {
        return match($method) {
            'Самовывоз' => 0,
            'Курьер' => $subtotal > 5000 ? 0 : 300,
            'Почта' => 200,
            default => 0,
        };
    }
}
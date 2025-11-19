<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $orders = Order::with('user', 'items')->latest()->get();
        $users = User::where('role', '!=', 'admin')->get();
        $products = Product::count();
        $totalSales = Order::where('status', 'Завершена')->sum('total');
        
        return view('admin.dashboard', compact('orders', 'users', 'products', 'totalSales'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'status' => 'required|in:Новая,В работе,Отменена,Завершена'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Статус заказа обновлен');
    }

    public function deleteUser(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Нельзя удалить администратора');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Пользователь удален');
    }

    public function updateUserRole(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'role' => 'required|in:user,admin,manager'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Роль пользователя обновлена');
    }

    // Управление товарами
    public function products()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        
        return view('admin.products', compact('products', 'categories'));
    }

    public function createProduct()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $categories = Category::all();
        return view('admin.product-create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.products')->with('success', 'Товар успешно добавлен');
    }

    public function editProduct(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $categories = Category::all();
        return view('admin.product-edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.products')->with('success', 'Товар успешно обновлен');
    }

    public function deleteProduct(Product $product)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Товар успешно удален');
    }

    // Управление категориями
    public function categories()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $categories = Category::withCount('products')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories')->with('success', 'Категория успешно создана');
    }

    public function deleteCategory(Category $category)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Нельзя удалить категорию с товарами');
        }

        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Категория успешно удалена');
    }
}
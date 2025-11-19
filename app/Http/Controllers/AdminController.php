<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $orders = Order::with('user', 'items')->latest()->get();
        $users = User::where('role', '!=', 'admin')->get();
        
        return view('admin.dashboard', compact('orders', 'users'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Новая,В работе,Отменена,Завершена'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Статус заказа обновлен');
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Нельзя удалить администратора');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Пользователь удален');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin,manager'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Роль пользователя обновлена');
    }
}
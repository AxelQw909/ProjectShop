<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('products.index');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('products.index');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Аккаунт с таким email не найден. Зарегистрируйтесь!',
            ])->withInput();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors([
                'password' => 'Неверный пароль.',
            ])->withInput();
        }

        $request->session()->regenerate();
        
        $user = Auth::user();
        if (!$user->cart) {
            Cart::create(['user_id' => $user->id]);
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('products.index');
    }

    public function showRegister()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('products.index');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('products.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6|regex:/^[а-яА-ЯёЁ\s]+$/u',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.regex' => 'Имя должно содержать только кириллические символы.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Cart::create(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('products.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Магазин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .navbar-brand { font-weight: bold; }
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .cart-badge { position: absolute; top: -5px; right: -5px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">Магазин</a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Панель администратора</a>
                    @endif
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        Корзина
                        <span class="badge bg-danger cart-badge" id="cart-count">0</span>
                    </a>
                    <a class="nav-link" href="{{ route('orders.index') }}">Мои заказы</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">Выйти</button>
                    </form>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Войти</a>
                    <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Добавляем CSRF токен ко всем AJAX запросам
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Обновление счетчика корзины
        async function updateCartCount() {
            try {
                const response = await fetch('{{ route("cart.total") }}');
                const data = await response.json();
                document.getElementById('cart-count').textContent = data.total || 0;
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>
</html>
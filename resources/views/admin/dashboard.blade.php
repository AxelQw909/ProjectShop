@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Панель администратора</h1>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4>{{ $orders->count() }}</h4>
                    <p class="mb-0">Всего заказов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4>{{ number_format($totalSales, 2) }} ₽</h4>
                    <p class="mb-0">Общие продажи</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h4>{{ $products }}</h4>
                    <p class="mb-0">Товаров в каталоге</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h4>{{ $users->count() }}</h4>
                    <p class="mb-0">Пользователей</p>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                Заказы
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                Пользователи
            </button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        <!-- Вкладка заказов -->
        <div class="tab-pane fade show active" id="orders" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>№ заказа</th>
                            <th>Клиент</th>
                            <th>Дата</th>
                            <th>Статус</th>
                            <th>Сумма</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}<br><small>{{ $order->user->email }}</small></td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="Новая" {{ $order->status == 'Новая' ? 'selected' : '' }}>Новая</option>
                                            <option value="В работе" {{ $order->status == 'В работе' ? 'selected' : '' }}>В работе</option>
                                            <option value="Завершена" {{ $order->status == 'Завершена' ? 'selected' : '' }}>Завершена</option>
                                            <option value="Отменена" {{ $order->status == 'Отменена' ? 'selected' : '' }}>Отменена</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ number_format($order->total, 2) }} ₽</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        Просмотр
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Вкладка пользователей -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                                            <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Менеджер</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    @if(!$user->isAdmin())
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Удалить пользователя {{ $user->name }}?')">
                                                Удалить
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Системный</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
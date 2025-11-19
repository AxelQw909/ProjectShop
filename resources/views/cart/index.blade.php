@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<h1 class="mb-4">Корзина покупок</h1>

@if($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Итого</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80x60?text=No+Image' }}" 
                                     alt="{{ $item->product->name }}" class="me-3" style="width: 80px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ number_format($item->product->price, 2) }} ₽</td>
                        <td>
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                       min="1" max="{{ $item->product->stock }}" 
                                       class="form-control form-control-sm" style="width: 80px;" 
                                       onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>{{ number_format($item->total, 2) }} ₽</td>
                        <td>
                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Удалить товар из корзины?')">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Общая сумма:</strong></td>
                    <td colspan="2"><strong>{{ number_format($items->sum('total'), 2) }} ₽</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            ← Продолжить покупки
        </a>
        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">
            Оформить заказ →
        </a>
    </div>
@else
    <div class="text-center py-5">
        <h3 class="text-muted">Ваша корзина пуста</h3>
        <p class="text-muted">Добавьте товары из каталога</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            Перейти к покупкам
        </a>
    </div>
@endif
@endsection
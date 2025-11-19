@extends('layouts.app')

@section('title', 'Заказ ' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Заказ {{ $order->order_number }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Дата заказа:</strong><br>
                        {{ $order->created_at->format('d.m.Y H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Статус:</strong><br>
                        <span class="badge 
                            @if($order->status == 'Новая') bg-primary
                            @elseif($order->status == 'В работе') bg-warning
                            @elseif($order->status == 'Завершена') bg-success
                            @elseif($order->status == 'Отменена') bg-danger
                            @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Клиент:</strong><br>
                        {{ $order->customer_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Адрес доставки:</strong><br>
                        {{ $order->address }}
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Способ оплаты:</strong><br>
                        {{ $order->payment_method }}
                    </div>
                    <div class="col-md-6">
                        <strong>Способ доставки:</strong><br>
                        {{ $order->delivery_method }}
                    </div>
                </div>

                <h5 class="mb-3">Состав заказа:</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Итого</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ number_format($item->price, 2) }} ₽</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->total, 2) }} ₽</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Итоговая стоимость</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Товары:</span>
                    <span>{{ number_format($order->subtotal, 2) }} ₽</span>
                </div>
                
                @if($order->promo_code)
                <div class="d-flex justify-content-between mb-2">
                    <span>Промокод ({{ $order->promo_code }}):</span>
                    <span class="text-danger">-{{ number_format($order->discount, 2) }} ₽</span>
                </div>
                @endif

                <div class="d-flex justify-content-between mb-2">
                    <span>Доставка:</span>
                    <span>{{ number_format($order->delivery_cost, 2) }} ₽</span>
                </div>
                
                <hr>
                <div class="d-flex justify-content-between fs-5">
                    <strong>Итого:</strong>
                    <strong>{{ number_format($order->total, 2) }} ₽</strong>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">
                ← Назад к заказам
            </a>
        </div>
    </div>
</div>
@endsection
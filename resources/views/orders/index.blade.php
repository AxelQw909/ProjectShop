@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<h1 class="mb-4">Мои заказы</h1>

@if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>№ заказа</th>
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
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <span class="badge 
                                @if($order->status == 'Новая') bg-primary
                                @elseif($order->status == 'В работе') bg-warning
                                @elseif($order->status == 'Завершена') bg-success
                                @elseif($order->status == 'Отменена') bg-danger
                                @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td>{{ number_format($order->total, 2) }} ₽</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                Подробнее
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5">
        <h3 class="text-muted">У вас пока нет заказов</h3>
        <p class="text-muted">Сделайте свой первый заказ в нашем магазине</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            Перейти к покупкам
        </a>
    </div>
@endif
@endsection
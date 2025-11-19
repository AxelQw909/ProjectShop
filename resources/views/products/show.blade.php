@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-md-6">
        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x400?text=No+Image' }}" 
             class="img-fluid rounded" alt="{{ $product->name }}">
    </div>
    <div class="col-md-6">
        <h1>{{ $product->name }}</h1>
        <p class="text-muted">Категория: {{ $product->category->name }}</p>
        <p class="lead">{{ $product->description }}</p>
        
        <div class="mb-3">
            <h3 class="text-primary">{{ number_format($product->price, 2) }} ₽</h3>
            <p class="text-muted">
                @if($product->stock > 0)
                    <span class="text-success">В наличии: {{ $product->stock }} шт.</span>
                @else
                    <span class="text-danger">Нет в наличии</span>
                @endif
            </p>
        </div>

        @if($product->stock > 0)
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Количество:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" 
                               value="1" min="1" max="{{ $product->stock }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    Добавить в корзину
                </button>
            </form>
        @else
            <button class="btn btn-secondary btn-lg" disabled>Нет в наличии</button>
        @endif

        <div class="mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                ← Назад к товарам
            </a>
        </div>
    </div>
</div>
@endsection
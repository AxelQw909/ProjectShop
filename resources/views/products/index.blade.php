@extends('layouts.app')

@section('title', 'Товары')

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Фильтры -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Фильтры</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="mb-3">
                        <label for="search" class="form-label">Поиск</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Название товара...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Категория</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Применить</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary w-100 mt-2">Сбросить</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Товары -->
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                             class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                            <div class="mt-auto">
                                <p class="card-text"><strong>{{ number_format($product->price, 2) }} ₽</strong></p>
                                <p class="card-text">
                                    <small class="text-muted">Категория: {{ $product->category->name }}</small>
                                </p>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
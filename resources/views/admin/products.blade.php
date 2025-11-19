@extends('layouts.app')

@section('title', 'Управление товарами')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление товарами</h1>
        <div>
            <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary me-2">
                Управление категориями
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                + Добавить товар
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Остаток</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div style="width: 50px; height: 50px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <small>Нет фото</small>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ number_format($product->price, 2) }} ₽</td>
                                <td>{{ $product->stock }} шт.</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Активен</span>
                                    @else
                                        <span class="badge bg-secondary">Неактивен</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-primary">Редактировать</a>
                                        <form action="{{ route('admin.products.delete', $product) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Удалить товар?')">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
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
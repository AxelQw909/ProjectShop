@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Вход в систему</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Для тестирования:</strong><br>
                    Админ: admin@shop.ru / admin123<br>
                    Пользователь: user@shop.ru / user123
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required
                               placeholder="Введите ваш email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required 
                               placeholder="Введите ваш пароль">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Войти</button>
                </form>

                <div class="text-center mt-3">
                    <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
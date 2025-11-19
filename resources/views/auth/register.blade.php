@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Регистрация</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Уже есть аккаунт?</strong><br>
                    Админ: admin@shop.ru / admin123<br>
                    Пользователь: user@shop.ru / user123
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Имя (кириллица, минимум 6 символов)</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required 
                               pattern="[а-яА-ЯёЁ\s]{6,}"
                               placeholder="Например: Иван Иванов">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required
                               placeholder="example@mail.ru">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль (минимум 6 символов)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required minlength="6"
                               placeholder="Не менее 6 символов">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required
                               placeholder="Повторите пароль">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                </form>

                <div class="text-center mt-3">
                    <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
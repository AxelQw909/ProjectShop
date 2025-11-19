@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Данные для заказа</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Ваше имя *</label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                   id="customer_name" name="customer_name" 
                                   value="{{ old('customer_name', Auth::user()->name) }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Адрес доставки *</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" 
                                   value="{{ old('address', Auth::user()->address) }}" 
                                   placeholder="Город, улица, дом, квартира" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Способ оплаты *</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">Выберите способ оплаты</option>
                                <option value="МИР" {{ old('payment_method') == 'МИР' ? 'selected' : '' }}>МИР</option>
                                <option value="VISA" {{ old('payment_method') == 'VISA' ? 'selected' : '' }}>VISA</option>
                                <option value="MASTERCARD" {{ old('payment_method') == 'MASTERCARD' ? 'selected' : '' }}>MASTERCARD</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="delivery_method" class="form-label">Способ доставки *</label>
                            <select class="form-select @error('delivery_method') is-invalid @enderror" 
                                    id="delivery_method" name="delivery_method" required>
                                <option value="">Выберите способ доставки</option>
                                <option value="Самовывоз" {{ old('delivery_method') == 'Самовывоз' ? 'selected' : '' }}>Самовывоз (бесплатно)</option>
                                <option value="Курьер" {{ old('delivery_method') == 'Курьер' ? 'selected' : '' }}>Курьер (300 ₽, бесплатно от 5000 ₽)</option>
                                <option value="Почта" {{ old('delivery_method') == 'Почта' ? 'selected' : '' }}>Почта России (200 ₽)</option>
                            </select>
                            @error('delivery_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="promo_code" class="form-label">Промокод (если есть)</label>
                        <input type="text" class="form-control" id="promo_code" name="promo_code" 
                               value="{{ old('promo_code') }}" placeholder="Введите промокод">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        Подтвердить заказ
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ваш заказ</h5>
            </div>
            <div class="card-body">
                @foreach($items as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                            <small class="text-muted">{{ $item->quantity }} × {{ number_format($item->product->price, 2) }} ₽</small>
                        </div>
                        <span>{{ number_format($item->total, 2) }} ₽</span>
                    </div>
                @endforeach
                
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Товары:</strong>
                    <strong>{{ number_format($items->sum('total'), 2) }} ₽</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Доставка:</span>
                    <span id="delivery-cost">0 ₽</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5">
                    <strong>Итого:</strong>
                    <strong id="total-cost">{{ number_format($items->sum('total'), 2) }} ₽</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('delivery_method').addEventListener('change', function() {
        const subtotal = {{ $items->sum('total') }};
        const deliveryMethod = this.value;
        let deliveryCost = 0;

        switch(deliveryMethod) {
            case 'Самовывоз':
                deliveryCost = 0;
                break;
            case 'Курьер':
                deliveryCost = subtotal >= 5000 ? 0 : 300;
                break;
            case 'Почта':
                deliveryCost = 200;
                break;
        }

        document.getElementById('delivery-cost').textContent = deliveryCost + ' ₽';
        document.getElementById('total-cost').textContent = (subtotal + deliveryCost).toFixed(2) + ' ₽';
    });
</script>
@endsection
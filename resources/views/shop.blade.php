@extends('layouts.app')

@section('content')
<style>
.shop-page {
    background-color: #ffffff;
    min-height: 100vh;
    padding: 40px 150px;
}

.shop-title {
    font-family: "Nunito Sans-Bold", Helvetica;
    font-weight: 700;
    color: #111111;
    font-size: 48px;
    letter-spacing: 0;
    line-height: 58px;
    margin-bottom: 60px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}

.product-card {
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: block;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.product-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%;
    background-color: #f3eee3;
    overflow: hidden;
}

.product-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image-wrapper img {
    transform: scale(1.1);
}

.sale-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: #111111;
    color: #ffffff;
    padding: 6px 12px;
    font-family: "Nunito Sans-Bold", Helvetica;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    z-index: 10;
}

.product-info {
    padding: 24px 20px;
}

.product-name {
    font-family: "Nunito Sans-Bold", Helvetica;
    font-weight: 700;
    color: #111111;
    font-size: 24px;
    letter-spacing: 0;
    line-height: 32px;
    margin-bottom: 12px;
}

.product-price {
    font-family: "Nunito Sans", Helvetica;
    font-weight: 600;
    color: #111111;
    font-size: 20px;
    letter-spacing: 0;
}

@media (max-width: 1440px) {
    .shop-page {
        padding: 40px 80px;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .shop-page {
        padding: 40px 20px;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="shop-page">
    <h1 class="shop-title">Our Products</h1>
    
    <div class="products-grid">
        @foreach($products as $product)
            <a href="/product/{{ $product['id'] }}" class="product-card">
                <div class="product-image-wrapper">
                    <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}">
                    @if($product['sale'])
                    <div class="sale-badge">SALE</div>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product['name'] }}</h3>
                    <p class="product-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection


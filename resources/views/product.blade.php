@extends('layouts.app')

@section('content')
<style>
.product-detail-page {
    background-color: #ffffff;
    min-height: 100vh;
    padding: 40px 150px;
}

.product-detail-container {
    display: flex;
    gap: 80px;
    align-items: flex-start;
}

.back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 30px;
    text-decoration: none;
    color: #111111;
}

.back-button:hover {
    background-color: #f5f5f5;
    transform: translateX(-5px);
}

.product-image-section {
    flex: 1;
    max-width: 500px;
}

.product-image-wrapper {
    width: 100%;
    padding-top: 100%;
    position: relative;
    background-color: #f3eee3;
    border-radius: 8px;
    overflow: hidden;
}

.product-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-details-section {
    flex: 1;
    max-width: 500px;
    padding-top: 20px;
}

.product-name {
    font-family: "Nunito Sans-Bold", Helvetica;
    font-weight: 700;
    color: #111111;
    font-size: 48px;
    letter-spacing: 0;
    line-height: 58px;
    margin-bottom: 20px;
}

.product-price {
    font-family: "Nunito Sans", Helvetica;
    font-weight: 600;
    color: #111111;
    font-size: 32px;
    letter-spacing: 0;
    margin-bottom: 40px;
}

.color-selector {
    margin-bottom: 30px;
}

.color-label {
    font-family: "Nunito Sans-SemiBold", Helvetica;
    font-weight: 600;
    color: #111111;
    font-size: 18px;
    margin-bottom: 12px;
    display: block;
}

.color-option {
    display: inline-block;
    padding: 12px 24px;
    background-color: #f3eee3;
    border: 1px solid #d4c5b3;
    border-radius: 4px;
    font-family: "Nunito Sans", Helvetica;
    font-weight: 400;
    font-size: 16px;
    color: #111111;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-option:hover {
    background-color: #e8ddd0;
    border-color: #c4b5a3;
}

.quantity-selector {
    margin-bottom: 40px;
}

.quantity-label {
    font-family: "Nunito Sans-SemiBold", Helvetica;
    font-weight: 600;
    color: #111111;
    font-size: 18px;
    margin-bottom: 12px;
    display: block;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 20px;
}

.quantity-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #e5e5e5;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 20px;
    color: #111111;
}

.quantity-button:hover {
    background-color: #f5f5f5;
    border-color: #d4d4d4;
}

.quantity-value {
    font-family: "Nunito Sans", Helvetica;
    font-weight: 600;
    font-size: 20px;
    color: #111111;
    min-width: 40px;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 20px;
    margin-bottom: 60px;
}

.action-button {
    flex: 1;
    padding: 16px 32px;
    background-color: #f3eee3;
    border: none;
    border-radius: 4px;
    font-family: "Nunito Sans-Bold", Helvetica;
    font-weight: 700;
    font-size: 16px;
    color: #111111;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}

.action-button:hover {
    background-color: #e8ddd0;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-info-section {
    margin-top: 80px;
}

.product-info-title {
    font-family: "Nunito Sans-Bold", Helvetica;
    font-weight: 700;
    color: #111111;
    font-size: 32px;
    letter-spacing: 0;
    line-height: 42px;
    margin-bottom: 20px;
}

.product-description {
    font-family: "Nunito Sans", Helvetica;
    font-weight: 400;
    color: #333333;
    font-size: 16px;
    line-height: 26px;
}

@media (max-width: 1440px) {
    .product-detail-page {
        padding: 40px 80px;
    }
    
    .product-detail-container {
        gap: 60px;
    }
}

@media (max-width: 768px) {
    .product-detail-page {
        padding: 40px 20px;
    }
    
    .product-detail-container {
        flex-direction: column;
        gap: 40px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="product-detail-page">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50" @click="show = false">
            {{ session('success') }}
        </div>
    @endif
    <a href="/shop" class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    
    <div class="product-detail-container">
        <div class="product-image-section">
            <div class="product-image-wrapper">
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
            </div>
        </div>
        
        <div class="product-details-section">
            <h1 class="product-name">{{ $product->name }}</h1>
            <p class="product-price">RP {{ number_format($product->price, 0, ',', '.') }}</p>
            
            <!-- Color selector: tampilkan jika sudah ada field color di database -->
            @if(property_exists($product, 'color') && $product->color)
            <div class="color-selector">
                <label class="color-label">Color:</label>
                <div class="color-option" id="colorOption">
                    {{ $product->color }}
                </div>
            </div>
            @endif
            
                <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}" onsubmit="return addToCartAjax(event)">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="quantity-selector">
                        <label class="quantity-label">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-button" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantityValue" name="quantity" value="1" min="1" style="width:60px;text-align:center;font-size:18px;">
                            <button type="button" class="quantity-button" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button type="submit" class="action-button">Add to Cart</button>
                        <button type="button" class="action-button" onclick="buyNowToCart(this.form)">Buy Now</button>
                    </div>
                </form>
            <script>
            function changeQuantity(val) {
                var input = document.getElementById('quantityValue');
                var qty = parseInt(input.value) || 1;
                qty += val;
                if (qty < 1) qty = 1;
                input.value = qty;
            }
            function addToCartAjax(e) {
                e.preventDefault();
                var form = document.getElementById('addToCartForm');
                var formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
                    },
                    body: formData
                })
                .then(response => response.ok ? response.text() : Promise.reject(response))
                .then(() => {
                    // Show pop-up success
                    var popup = document.createElement('div');
                    popup.textContent = 'Produk berhasil ditambahkan ke keranjang!';
                    popup.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50';
                    document.body.appendChild(popup);
                    setTimeout(() => popup.remove(), 2000);
                });
                return false;
            }
            function buyNowToCart(form) {
                var formData = new FormData(form);
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
                    },
                    body: formData
                })
                .then(response => response.ok ? response.text() : Promise.reject(response))
                .then(() => {
                    window.location.href = '/cart';
                });
            }
            </script>
        </div>
    </div>
    
    <div class="product-info-section">
        <h2 class="product-info-title">Produk Info</h2>
        <p class="product-description">
            {{ $product->description }}
        </p>
    </div>
</div>

<script>
let quantity = 0;

function increaseQuantity() {
    quantity++;
    document.getElementById('quantityValue').textContent = quantity;
}

function decreaseQuantity() {
    if (quantity > 0) {
        quantity--;
        document.getElementById('quantityValue').textContent = quantity;
    }
}

function addToCart() {
    if (quantity > 0) {
        alert('Product added to cart!');
        // Add cart functionality here
    } else {
        alert('Please select quantity first');
    }
}

function buyNow() {
    if (quantity > 0) {
        alert('Redirecting to checkout...');
        // Add checkout functionality here
    } else {
        alert('Please select quantity first');
    }
}
</script>
@endsection






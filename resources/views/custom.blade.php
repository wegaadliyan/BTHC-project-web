@extends('layouts.app')

@section('content')
<style>
    .custom-page {
        padding: 60px 120px;
        background-color: #f3eee3;
        min-height: 100vh;
    }

    .page-title {
        font-size: 48px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 16px;
    }

    .page-description {
        font-size: 18px;
        color: #666666;
        margin-bottom: 60px;
        font-style: italic;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
    }

    .product-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
    }

    .product-info {
        padding: 24px;
    }

    .product-name {
        font-size: 24px;
        font-weight: 600;
        color: #111111;
        margin-bottom: 8px;
    }

    .product-price {
        font-size: 18px;
        color: #111111;
        margin-bottom: 0;
    }

    /* Custom Product Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 32px;
        border-radius: 8px;
        max-width: 600px;
        width: 90%;
        position: relative;
    }

    .close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #111111;
    }

    .product-customization {
        display: flex;
        gap: 32px;
        margin-bottom: 32px;
    }

    .product-preview {
        flex: 1;
    }

    .product-preview img {
        width: 100%;
        border-radius: 4px;
    }

    .customization-options {
        flex: 1;
    }

    .option-group {
        margin-bottom: 24px;
    }

    .option-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .color-options,
    .size-options,
    .charm-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .option-button {
        padding: 8px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-button:hover,
    .option-button.active {
        background: #111111;
        color: white;
        border-color: #111111;
    }

    .quantity-input {
        width: 100px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 12px;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        margin-top: 24px;
    }

    .add-to-cart,
    .buy-now {
        padding: 16px 32px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .add-to-cart {
        background-color: #f3eee3;
        color: #111111;
    }

    .buy-now {
        background-color: #111111;
        color: white;
    }

    .add-to-cart:hover {
        background-color: #e3ddd3;
    }

    .buy-now:hover {
        background-color: #333333;
    }

    @media (max-width: 1200px) {
        .custom-page {
            padding: 40px 60px;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .custom-page {
            padding: 30px 20px;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .product-customization {
            flex-direction: column;
        }
    }
</style>

<div class="custom-page">
    <h1 class="page-title">Custom</h1>
    <p class="page-description">Pilih desain dasar dan modifikasi sesuai keinginan Anda.</p>

    <div class="products-grid">
        <!-- Terra Soul -->
        <div class="product-card" onclick="openCustomModal('Terra Soul', 35000, 'terra-soul.png')">
            <img src="{{ asset('images/terra-soul.png') }}" alt="Terra Soul" class="product-image">
            <div class="product-info">
                <h3 class="product-name">Terra Soul</h3>
                <p class="product-price">Rp 35.000</p>
            </div>
        </div>

        <!-- Red Pearl -->
        <div class="product-card" onclick="openCustomModal('Red Pearl', 60000, 'red-pearl.png')">
            <img src="{{ asset('images/red-pearl.png') }}" alt="Red Pearl" class="product-image">
            <div class="product-info">
                <h3 class="product-name">Red Pearl</h3>
                <p class="product-price">Rp 60.000</p>
            </div>
        </div>

        <!-- Lapis Lazuli -->
        <div class="product-card" onclick="openCustomModal('Lapis Lazuli', 60000, 'lapis-lazuli.png')">
            <img src="{{ asset('images/lapis-lazuli.png') }}" alt="Lapis Lazuli" class="product-image">
            <div class="product-info">
                <h3 class="product-name">Lapis Lazuli</h3>
                <p class="product-price">Rp 60.000</p>
            </div>
        </div>
    </div>
</div>

<!-- Customization Modal -->
<div id="customModal" class="modal">
    <div class="modal-content">
        <button class="close-button" onclick="closeCustomModal()">&times;</button>
        
        <div class="product-customization">
            <div class="product-preview">
                <img id="modalProductImage" src="" alt="Product Preview">
            </div>
            
            <div class="customization-options">
                <h2 id="modalProductName" class="product-name"></h2>
                <p id="modalProductPrice" class="product-price"></p>
                
                <div class="option-group">
                    <h3 class="option-title">Warna</h3>
                    <div class="color-options">
                        <button class="option-button" onclick="selectOption(this, 'color')">Hitam</button>
                        <button class="option-button" onclick="selectOption(this, 'color')">Putih</button>
                        <button class="option-button" onclick="selectOption(this, 'color')">Merah</button>
                        <button class="option-button" onclick="selectOption(this, 'color')">Biru</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">Ukuran</h3>
                    <div class="size-options">
                        <button class="option-button" onclick="selectOption(this, 'size')">15cm</button>
                        <button class="option-button" onclick="selectOption(this, 'size')">17cm</button>
                        <button class="option-button" onclick="selectOption(this, 'size')">19cm</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">Charm</h3>
                    <div class="charm-options">
                        <button class="option-button" onclick="selectOption(this, 'charm')">Love</button>
                        <button class="option-button" onclick="selectOption(this, 'charm')">Key</button>
                        <button class="option-button" onclick="selectOption(this, 'charm')">Star</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">Jumlah</h3>
                    <input type="number" class="quantity-input" value="1" min="1">
                </div>

                <div class="action-buttons">
                    <button class="add-to-cart">Add to Cart</button>
                    <button class="buy-now">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openCustomModal(productName, price, image) {
        const modal = document.getElementById('customModal');
        const modalProductName = document.getElementById('modalProductName');
        const modalProductPrice = document.getElementById('modalProductPrice');
        const modalProductImage = document.getElementById('modalProductImage');

        modalProductName.textContent = productName;
        modalProductPrice.textContent = `Rp ${price.toLocaleString()}`;
        modalProductImage.src = `/images/${image}`;
        
        modal.classList.add('show');
    }

    function closeCustomModal() {
        const modal = document.getElementById('customModal');
        modal.classList.remove('show');
    }

    function selectOption(button, type) {
        // Remove active class from siblings
        const parent = button.parentElement;
        parent.querySelectorAll('.option-button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to selected button
        button.classList.add('active');
    }
</script>
@endsection

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
        cursor: pointer;
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
        max-height: 90vh;
        overflow-y: auto;
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

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50" @click="show = false">
        {{ session('success') }}
    </div>
@endif
<div class="custom-page">
    <h1 class="page-title">Custom</h1>
    <p class="page-description">Pilih charm sesuai dengan keinginan anda.</p>

    <div class="products-grid">
        @forelse($customProducts as $product)
            <div class="product-card" onclick="openCustomModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image }}', '{{ addslashes($product->size) }}', {{ json_encode(['charm_1' => $product->charm_1, 'charm_1_image' => $product->charm_1_image, 'charm_2' => $product->charm_2, 'charm_2_image' => $product->charm_2_image, 'charm_3' => $product->charm_3, 'charm_3_image' => $product->charm_3_image]) }})">
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
        @empty
            <p>Tidak ada produk custom tersedia.</p>
        @endforelse
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
                    <h3 class="option-title">Ukuran</h3>
                    <div class="size-options" id="sizeOptions">
                        <button class="option-button" onclick="selectOption(this, 'size')">15 cm</button>
                        <button class="option-button" onclick="selectOption(this, 'size')">17 cm</button>
                        <button class="option-button" onclick="selectOption(this, 'size')">19 cm</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3 class="option-title">Charm</h3>
                    <div class="charm-options" id="charmOptions"></div>
                </div>

                <form id="customAddToCartForm" method="POST" action="{{ route('cart.add') }}" onsubmit="return addToCartCustomAjax(event)">
                    @csrf
                    <input type="hidden" name="custom_product_id" id="formCustomProductId">
                    <input type="hidden" name="size" id="formSize">
                    <input type="hidden" name="charm" id="formCharm">
                    <input type="hidden" name="price" id="formPrice">
                    <div class="option-group">
                        <h3 class="option-title">Jumlah</h3>
                        <input type="number" class="quantity-input" name="quantity" id="formQuantity" value="1" min="1">
                    </div>
                    <div class="action-buttons">
                        <button type="submit" class="add-to-cart">Add to Cart</button>
                        <button type="button" class="buy-now" onclick="buyNowCustom()">Buy Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openCustomModal(productId, productName, price, image, size, charms) {
        const modal = document.getElementById('customModal');
        const modalProductName = document.getElementById('modalProductName');
        const modalProductPrice = document.getElementById('modalProductPrice');
        const modalProductImage = document.getElementById('modalProductImage');
        const charmOptions = document.getElementById('charmOptions');

        // Set form values
        document.getElementById('formCustomProductId').value = productId;
        document.getElementById('formPrice').value = price;
        document.getElementById('formSize').value = size;
        document.getElementById('formQuantity').value = 1;

        modalProductName.textContent = productName;
        modalProductPrice.textContent = `Rp ${price.toLocaleString('id-ID')}`;
        modalProductImage.src = `/storage/products/${image}`;

        // Build charm options dynamically
        charmOptions.innerHTML = '';
        const charmButtonsContainer = document.createElement('div');
        charmButtonsContainer.style.display = 'flex';
        charmButtonsContainer.style.flexWrap = 'wrap';
        charmButtonsContainer.style.gap = '12px';
        charmButtonsContainer.style.marginBottom = '16px';
        
        const charmDetailsContainer = document.createElement('div');
        charmDetailsContainer.id = 'charmDetailsContainer';
        charmDetailsContainer.style.marginTop = '12px';
        
        for (let i = 1; i <= 3; i++) {
            const charmName = charms[`charm_${i}`];
            const charmImage = charms[`charm_${i}_image`];
            
            if (charmName) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'option-button';
                btn.textContent = charmName;
                btn.dataset.charm = charmName;
                btn.dataset.image = charmImage || '';
                btn.style.flex = '1';
                btn.style.minWidth = '100px';
                btn.onclick = function(e) {
                    e.preventDefault();
                    // Remove active from all charm buttons
                    charmButtonsContainer.querySelectorAll('.option-button').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    // Add active to clicked button
                    this.classList.add('active');
                    // Update form value
                    document.getElementById('formCharm').value = charmName;
                    // Show charm image
                    showCharmDetails(charmImage);
                };
                
                charmButtonsContainer.appendChild(btn);
            }
        }
        
        charmOptions.appendChild(charmButtonsContainer);
        charmOptions.appendChild(charmDetailsContainer);

        // Set default selections from product data
        if (size) {
            const sizeBtn = Array.from(document.querySelectorAll('#sizeOptions .option-button'))
                .find(btn => btn.textContent.includes(size));
            if (sizeBtn) {
                sizeBtn.click();
            }
        }
        
        modal.classList.add('show');
    }
    
    function showCharmDetails(charmImage) {
        const container = document.getElementById('charmDetailsContainer');
        container.innerHTML = '';
        if (charmImage) {
            const imgContainer = document.createElement('div');
            imgContainer.style.textAlign = 'center';
            const img = document.createElement('img');
            img.src = `/storage/products/${charmImage}`;
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '4px';
            imgContainer.appendChild(img);
            container.appendChild(imgContainer);
        }
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
        // Set value to hidden input
        document.getElementById('form' + capitalize(type)).value = button.textContent;
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Add to Cart: default submit, tetap di halaman custom
    function addToCartCustomAjax(e) {
        e.preventDefault();
        var form = document.getElementById('customAddToCartForm');
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
            var popup = document.createElement('div');
            popup.textContent = 'Produk custom berhasil ditambahkan ke keranjang!';
            popup.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50';
            document.body.appendChild(popup);
            setTimeout(() => popup.remove(), 2000);
        });
        return false;
    }
    function buyNowCustom() {
        const form = document.getElementById('customAddToCartForm');
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
@endsection

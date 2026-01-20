@extends('layouts.app')

@section('content')
<style>
    /* Global styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Reveal Animation Keyframes */
    @keyframes revealFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes revealSlideLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes revealSlideRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Reveal classes */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .reveal.active {
        animation: revealFadeIn 0.6s ease-out forwards;
    }

    .reveal-left {
        opacity: 0;
        transform: translateX(-50px);
    }

    .reveal-left.active {
        animation: revealSlideLeft 0.6s ease-out forwards;
    }

    .reveal-right {
        opacity: 0;
        transform: translateX(50px);
    }

    .reveal-right.active {
        animation: revealSlideRight 0.6s ease-out forwards;
    }

    /* Parallax background */
    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }

    /* Lazy loading image placeholder */
    .lazy-image {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        min-height: 300px;
    }

    .lazy-image.loaded {
        animation: none;
        background: none;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    /* Hero Section */
    .hero-section {
        background-color: #f3eee3;
        padding: 80px 120px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 100px;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        flex: 0 1 45%;
        max-width: 500px;
    }

    .collection-title {
        color: #FF4A4A;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        margin-bottom: 24px;
    }

    .main-title {
        font-size: 56px;
        font-weight: 700;
        color: #111111;
        line-height: 1.15;
        margin-bottom: 28px;
        letter-spacing: -1px;
    }

    .description {
        font-size: 16px;
        color: #555555;
        line-height: 1.7;
        margin-bottom: 48px;
        max-width: 100%;
    }

    .shop-now-btn {
        display: inline-block;
        background-color: #111111;
        color: #ffffff;
        padding: 14px 36px;
        text-decoration: none;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1.5px;
        font-size: 13px;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .shop-now-btn:hover {
        background-color: #333333;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .hero-image {
        flex: 0 1 45%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-image img {
        width: 100%;
        height: auto;
        max-width: 450px;
        border-radius: 20px;
        object-fit: cover;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    /* Product Grid Section */
    .products-section {
        padding: 100px 120px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 80px 60px;
    }

    .product-item {
        position: relative;
        transition: all 0.3s ease;
    }

    .product-item:hover {
        transform: translateY(-8px);
    }

    .product-image-container {
        width: 100%;
        position: relative;
        margin-bottom: 28px;
        overflow: visible;
        border-radius: 12px;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 320px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .product-item:hover .product-image-container {
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    }

    .product-image-container img {
        width: auto;
        height: auto;
        max-width: 80%;
        max-height: 280px;
        display: block;
        transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        object-fit: contain;
    }

    .product-item:hover .product-image-container img {
        transform: scale(1.08);
    }

    .sale-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        background: linear-gradient(135deg, #111111 0%, #333333 100%);
        color: #ffffff;
        padding: 10px 18px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        z-index: 10;
        border-radius: 6px;
        font-size: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .product-title {
        font-size: 24px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 14px;
        letter-spacing: -0.5px;
        line-height: 1.3;
    }

    .product-link {
        display: inline-block;
        font-size: 13px;
        font-weight: 700;
        color: #111111;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-decoration: none;
        border-bottom: 2px solid #111111;
        padding-bottom: 6px;
        transition: all 0.3s ease;
    }

    .product-link:hover {
        color: #666666;
        border-color: #666666;
        padding-bottom: 2px;
    }

    /* Custom Section */
    .custom-section {
        background: linear-gradient(135deg, #f3eee3 0%, #faf7f2 100%);
        padding: 100px 120px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 100px;
        position: relative;
        overflow: hidden;
    }

    .custom-section::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 74, 74, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }

    .custom-content {
        flex: 0 1 40%;
        position: relative;
        z-index: 1;
    }

    .custom-title {
        font-size: 48px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 40px;
        line-height: 1.15;
        letter-spacing: -1px;
    }

    .custom-subtitle {
        color: #FF4A4A;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 12px;
    }

    .custom-product-title {
        font-size: 40px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 36px;
        letter-spacing: -0.5px;
    }

    .custom-now-btn {
        display: inline-block;
        background-color: #D4C3B4;
        color: #111111;
        padding: 14px 36px;
        text-decoration: none;
        font-weight: 700;
        letter-spacing: 1.5px;
        font-size: 13px;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .custom-now-btn:hover {
        background-color: #C4B3A4;
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .custom-image {
        flex: 0 1 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .custom-image img {
        width: 100%;
        height: auto;
        max-width: 420px;
        border-radius: 12px;
        object-fit: contain;
    }

    /* Brand Slider Section */
    .brand-slider-section {
        background-color: transparent;
        padding: 0;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    .brand-slider-container {
        position: relative;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }

    .brand-slider-wrapper {
        overflow: hidden;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        width: 100%;
        height: 280px;
    }

    .brand-slider {
        display: flex;
        transition: transform 0.5s ease-in-out;
        width: 100%;
        height: 100%;
    }

    .brand-slide {
        min-width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        padding: 0;
        position: relative;
    }

    .brand-slide img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        animation: slideInFade 0.6s ease-out forwards;
    }

    .brand-slide a {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .brand-slide a:hover {
        opacity: 0.95;
    }

    @keyframes slideInFade {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .brand-slider-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-top: 15px;
        padding: 0;
        position: relative;
        z-index: 10;
    }

    .brand-slider-btn {
        width: 38px;
        height: 38px;
        border: 2px solid #111111;
        background-color: #ffffff;
        color: #111111;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border-radius: 4px;
        font-weight: bold;
    }

    .brand-slider-btn:hover {
        background-color: #111111;
        color: #ffffff;
        transform: scale(1.08);
    }

    .brand-slider-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        transform: scale(1);
    }

    .brand-slider-btn:disabled:hover {
        background-color: #ffffff;
        color: #111111;
    }

    .brand-slider-dots {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex: 1;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #cccccc;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dot.active {
        background-color: #111111;
        width: 24px;
        border-radius: 5px;
    }

    .dot:hover {
        background-color: #999999;
    }

    @media (max-width: 1200px) {
        .hero-section,
        .custom-section {
            padding: 80px 60px;
            gap: 60px;
        }

        .brand-slider-section {
            padding: 20px 60px;
        }

        .main-title {
            font-size: 48px;
        }

        .custom-title {
            font-size: 40px;
        }

        .custom-product-title {
            font-size: 32px;
        }

        .custom-section::before {
            width: 400px;
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            flex-direction: column;
            padding: 60px 20px;
            gap: 40px;
        }

        .hero-content {
            flex: 1;
            max-width: 100%;
        }

        .hero-image {
            flex: 1;
            max-width: 100%;
        }

        .hero-image img {
            max-width: 350px;
            border-radius: 16px;
        }

        .main-title {
            font-size: 40px;
        }

        .description {
            font-size: 15px;
            margin-bottom: 32px;
        }

        .custom-section {
            flex-direction: column;
            padding: 60px 20px;
            gap: 40px;
        }

        .custom-content {
            flex: 1;
            max-width: 100%;
            text-align: center;
        }

        .custom-image {
            flex: 1;
            max-width: 100%;
        }

        .custom-image img {
            max-width: 300px;
        }

        .custom-title {
            font-size: 32px;
            margin-bottom: 30px;
        }

        .custom-product-title {
            font-size: 28px;
            margin-bottom: 24px;
        }

        .custom-section::before {
            display: none;
        }

        .brand-slider-section {
            padding: 20px 20px;
        }

        .brand-slider-wrapper {
            height: 200px;
        }

        .brand-slider-btn {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }

        .brand-slider-controls {
            gap: 12px;
            margin-top: 12px;
        }

        .dot {
            width: 8px;
            height: 8px;
        }

        .dot.active {
            width: 20px;
        }
    }
</style>

<!-- Banner Slider Section -->
<section class="brand-slider-section">
    <div class="brand-slider-container">
        <div class="brand-slider-wrapper">
            <div class="brand-slider" id="brandSlider">
                @forelse($banners as $banner)
                    <div class="brand-slide">
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('storage/banners/' . $banner->image) }}" alt="{{ $banner->alt_text ?? 'Banner' }}" loading="lazy">
                            </a>
                        @else
                            <img src="{{ asset('storage/banners/' . $banner->image) }}" alt="{{ $banner->alt_text ?? 'Banner' }}" loading="lazy">
                        @endif
                    </div>
                @empty
                    <div class="brand-slide">
                        <img src="{{ asset('images/placeholder-banner.png') }}" alt="Banner" loading="lazy">
                    </div>
                @endforelse
            </div>
        </div>
        <div class="brand-slider-controls">
            <button class="brand-slider-btn" id="prevBtn" aria-label="Previous slide">❮</button>
            <div class="brand-slider-dots" id="dotsContainer">
                @foreach($banners as $index => $banner)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                @endforeach
            </div>
            <button class="brand-slider-btn" id="nextBtn" aria-label="Next slide">❯</button>
        </div>
    </div>
</section>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content reveal-left">
        <p class="collection-title">CHECK OUR NEW COLLECTIONS</p>
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin: 32px 0 24px 0;">
            <div style="background: #fff; border-radius: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 32px 48px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 2.2rem; color: #222; font-weight: 700; letter-spacing: 1px;">Better Hope Collection</span>
            </div>
        </div>
        <p class="description">Better Hope Collection brings craftsmanship into accessories that are full of meaning. Every product is a manifestation of the stories and hopes that we carry into the future.</p>
        <a href="/shop" class="shop-now-btn">SHOP NOW</a>
    </div>
    <div class="hero-image reveal-right">
        <img src="{{ asset('images/utama.png') }}" alt="Better Hope Collection" loading="lazy" style="max-width: 420px; width: 100%; height: auto; border-radius: 32px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); background: #fff;">
    </div>
</section>

<!-- Custom Section -->

<section class="custom-section" style="display: flex; align-items: center; justify-content: space-between; background: #f8f5ed; padding: 32px 0;">
    <div style="flex: 1; display: flex; align-items: center; justify-content: flex-end; min-width: 260px;">
            <div style="padding: 0; display: flex; align-items: center; justify-content: flex-start; width: 100%;">
                <img src='{{ asset('images/logotransparant.png') }}' alt='Better Hope Collection Logo' style='max-width: 220px; height: 70px; display: inline-block; background: transparent; box-shadow: none; margin-right: 24px; vertical-align: middle;'>
                <span style="font-size: 1.1rem; color: #222; font-weight: 500; vertical-align: middle;">BETTER HOPE COLLECTION</span>
            </div>
    </div>
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; min-width: 260px;">
        <img src="{{ asset('images/transparant.png') }}" alt="Bracelet" style="width: 220px; height: 220px; object-fit: contain; border-radius: 32px; box-shadow: none; background: transparent;">
    </div>
    <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 16px; min-width: 260px;">
        <p style="color: #d46a5a; font-size: 1.2rem; font-weight: 500; margin-bottom: 0;">Change Based on Your Choices.</p>
        <button onclick="window.location.href='{{ url('/custom') }}'" style="background: #d6c1ab; color: #222; font-weight: 500; padding: 12px 32px; border-radius: 16px; text-decoration: none; font-size: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: none; cursor: pointer; z-index: 99; position: relative; pointer-events: auto;">Custom Now</button>
    </div>
</section>

<script>
    // Brand Slider Functionality
    class BrandSlider {
        constructor() {
            this.slider = document.getElementById('brandSlider');
            this.prevBtn = document.getElementById('prevBtn');
            this.nextBtn = document.getElementById('nextBtn');
            this.dots = document.querySelectorAll('.dot');
            this.slides = document.querySelectorAll('.brand-slide');
            this.currentIndex = 0;
            this.totalSlides = this.slides.length;
            this.autoSlideInterval = null;

            this.init();
        }

        init() {
            // Event listeners untuk tombol
            this.prevBtn.addEventListener('click', () => this.prevSlide());
            this.nextBtn.addEventListener('click', () => this.nextSlide());

            // Event listeners untuk dots
            this.dots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    this.currentIndex = parseInt(e.target.getAttribute('data-index'));
                    this.updateSlider();
                });
            });

            // Auto slide every 5 seconds
            this.startAutoSlide();

            // Pause auto slide on hover
            this.slider.parentElement.addEventListener('mouseenter', () => this.stopAutoSlide());
            this.slider.parentElement.addEventListener('mouseleave', () => this.startAutoSlide());
        }

        nextSlide() {
            this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
            this.updateSlider();
            this.resetAutoSlide();
        }

        prevSlide() {
            this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
            this.updateSlider();
            this.resetAutoSlide();
        }

        updateSlider() {
            const translateX = -this.currentIndex * 100;
            this.slider.style.transform = `translateX(${translateX}%)`;

            // Update dots
            this.dots.forEach((dot, index) => {
                if (index === this.currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        startAutoSlide() {
            this.autoSlideInterval = setInterval(() => this.nextSlide(), 5000);
        }

        stopAutoSlide() {
            if (this.autoSlideInterval) {
                clearInterval(this.autoSlideInterval);
                this.autoSlideInterval = null;
            }
        }

        resetAutoSlide() {
            this.stopAutoSlide();
            this.startAutoSlide();
        }
    }

    // Reveal Animation on Scroll
    function revealOnScroll() {
        const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
        
        reveals.forEach(element => {
            const windowHeight = window.innerHeight;
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < windowHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    }

    // Lazy Loading Images
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize brand slider
        new BrandSlider();

        lazyLoadImages();
        revealOnScroll();
    });

    // Trigger animations on scroll
    window.addEventListener('scroll', () => {
        revealOnScroll();
    }, { passive: true });

    // Trigger on resize (untuk responsiveness)
    window.addEventListener('resize', () => {
        revealOnScroll();
    }, { passive: true });
</script>
@endsection
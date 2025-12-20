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
        padding: 60px 120px;
        height: 100vh;
        display: flex;
        align-items: center;
        gap: 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        flex: 1;
        max-width: 600px;
    }

    .collection-title {
        color: #FF4A4A;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .main-title {
        font-size: 64px;
        font-weight: 700;
        color: #111111;
        line-height: 1.2;
        margin-bottom: 24px;
    }

    .description {
        font-size: 18px;
        color: #666666;
        line-height: 1.6;
        margin-bottom: 40px;
        max-width: 500px;
    }

    .shop-now-btn {
        display: inline-block;
        background-color: #111111;
        color: #ffffff;
        padding: 16px 32px;
        text-decoration: none;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 2px;
        transition: all 0.3s ease;
    }

    .shop-now-btn:hover {
        background-color: #333333;
        transform: translateY(-2px);
    }

    .hero-image {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-image img {
        max-width: 100%;
        height: auto;
    }

    /* Product Grid Section */
    .products-section {
        padding: 80px 120px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }

    .product-item {
        position: relative;
    }

    .product-image-container {
        width: 100%;
        position: relative;
        margin-bottom: 24px;
        overflow: hidden;
        border-radius: 8px;
    }

    .product-image-container img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.8s ease;
    }

    .product-item:hover .product-image-container img {
        transform: scale(1.05);
    }

    .sale-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: #111111;
        color: #ffffff;
        padding: 8px 16px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        z-index: 10;
    }

    .product-title {
        font-size: 32px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 16px;
    }

    .product-link {
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        color: #111111;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        border-bottom: 2px solid #111111;
        padding-bottom: 4px;
        transition: all 0.3s ease;
    }

    .product-link:hover {
        color: #666666;
        border-color: #666666;
    }

    /* Custom Section */
    .custom-section {
        background: linear-gradient(135deg, #f3eee3 0%, #faf7f2 100%);
        padding: 80px 120px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 80px;
        position: relative;
        overflow: hidden;
    }

    .custom-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 74, 74, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }

    .custom-content {
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .custom-title {
        font-size: 48px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 16px;
    }

    .custom-subtitle {
        color: #666666;
        font-size: 18px;
        margin-bottom: 24px;
    }

    .custom-product-title {
        font-size: 32px;
        color: #111111;
        margin-bottom: 32px;
    }

    .custom-now-btn {
        display: inline-block;
        background-color: #D4C3B4;
        color: #111111;
        padding: 16px 32px;
        text-decoration: none;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .custom-now-btn:hover {
        background-color: #C4B3A4;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .custom-image {
        flex: 1;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    .custom-image img {
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 1200px) {
        .hero-section,
        .products-section,
        .custom-section {
            padding: 60px;
        }

        .main-title {
            font-size: 48px;
        }

        .custom-section::before {
            width: 400px;
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            flex-direction: column;
            height: auto;
            text-align: center;
            padding: 40px 20px;
        }

        .hero-content {
            margin-bottom: 40px;
        }

        .products-section {
            padding: 40px 20px;
            grid-template-columns: 1fr;
        }

        .custom-section {
            flex-direction: column;
            text-align: center;
            padding: 40px 20px;
        }

        .custom-section::before {
            display: none;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content reveal-left">
        <p class="collection-title">OUR NEW COLLECTIONS</p>
        <h1 class="main-title">Bracelet Collections</h1>
        <p class="description">Better Hope Collection brings craftsmanship into accessories that are full of meaning. Every product is a manifestation of the stories and hopes that we carry into the future.</p>
        <a href="/shop" class="shop-now-btn">SHOP NOW</a>
    </div>
    <div class="hero-image reveal-right">
        <img src="{{ asset('images/bracelet-collections.png') }}" alt="Bracelet Collections" loading="lazy">
    </div>
</section>

<!-- Products Section -->
<section class="products-section">
    <!-- Radiant Gemstone -->
    <div class="product-item reveal">
        <div class="product-image-container">
            <img src="{{ asset('images/radiant-gemstone.png') }}" alt="Radiant Gemstone" loading="lazy">
        </div>
        <h2 class="product-title">Radiant Gemstone</h2>
        <a href="/shop" class="product-link">SHOP NOW</a>
    </div>
    
    <!-- Kalung Manik -->
    <div class="product-item reveal">
        <div class="product-image-container">
            <img src="{{ asset('images/kalung-manik.png') }}" alt="Kalung Manik" loading="lazy">
            <div class="sale-badge">SALE</div>
        </div>
        <h2 class="product-title">Kalung Manik</h2>
        <a href="/shop" class="product-link">SHOP NOW</a>
    </div>
</section>

<!-- Gelang Tridatu Section -->
<section class="products-section" style="padding-top: 0;">
    <div class="product-item reveal" style="grid-column: span 2;">
        <div class="product-image-container">
            <img src="{{ asset('images/gelang-tridatu.png') }}" alt="Gelang Tridatu" style="max-width: 50%; margin: 0 auto;" loading="lazy">
        </div>
        <div style="text-align: center;">
            <h2 class="product-title">Gelang Tridatu</h2>
            <a href="/shop" class="product-link">SHOP NOW</a>
        </div>
    </div>
</section>

<!-- Custom Section -->
<section class="custom-section">
    <div class="custom-content reveal-left">
        <h2 class="custom-title">Bracelet Collections</h2>
        <p class="custom-subtitle">Change Based on Your Choices.</p>
        <h3 class="custom-product-title">Terra Soul</h3>
        <a href="/custom" class="custom-now-btn">Custom Now</a>
    </div>
    <div class="custom-image reveal-right">
        <img src="{{ asset('images/terra-soul.png') }}" alt="Terra Soul" loading="lazy">
    </div>
</section>

<script>
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

    // Parallax Scroll Effect
    function parallaxScroll() {
        const scrollPosition = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.hero-image img');
        
        parallaxElements.forEach(element => {
            element.style.transform = `translateY(${scrollPosition * 0.5}px)`;
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
        lazyLoadImages();
        revealOnScroll();
    });

    // Trigger animations on scroll
    window.addEventListener('scroll', () => {
        revealOnScroll();
        parallaxScroll();
    }, { passive: true });

    // Trigger on resize (untuk responsiveness)
    window.addEventListener('resize', () => {
        revealOnScroll();
    }, { passive: true });
</script>
@endsection
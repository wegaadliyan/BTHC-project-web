@extends('layouts.app')

@section('content')
<style>
    .about-page {
        background-color: #f3eee3;
        min-height: 100vh;
    }

    /* Hero Section */
    .about-hero {
        padding: 60px 120px;
        text-align: center;
        margin-bottom: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .main-image {
        max-width: 1000px;
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Info Sections */
    .info-sections {
        padding: 0 120px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        margin-bottom: 80px;
    }

    .info-section {
        text-align: left;
    }

    .info-title {
        font-size: 24px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 20px;
    }

    .info-description {
        font-size: 16px;
        line-height: 1.6;
        color: #666666;
    }

    /* Quote Section */
    .quote-section {
        padding: 80px 120px;
        display: flex;
        align-items: center;
        gap: 60px;
    }

    .quote-content {
        flex: 1;
        position: relative;
        padding: 40px 0;
    }

    .quote-mark {
        color: #FF4A4A;
        font-size: 64px;
        font-family: serif;
        position: absolute;
        top: 0;
        left: 0;
    }

    .quote-text {
        font-size: 24px;
        line-height: 1.6;
        color: #111111;
        font-style: italic;
        margin-bottom: 20px;
        max-width: 600px;
    }

    .quote-image {
        flex: 1;
    }

    .quote-image img {
        width: 100%;
        height: auto;
        max-width: 500px;
        display: block;
    }

    @media (max-width: 1200px) {
        .about-hero,
        .info-sections,
        .quote-section {
            padding: 40px 60px;
        }
    }

    @media (max-width: 768px) {
        .about-hero,
        .info-sections,
        .quote-section {
            padding: 30px 20px;
        }

        .info-sections {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .quote-section {
            flex-direction: column;
            text-align: center;
        }

        .quote-content {
            padding: 20px 0;
        }

        .quote-mark {
            position: static;
            display: block;
            margin-bottom: 20px;
        }

        .quote-text {
            font-size: 20px;
            margin: 0 auto 20px;
        }
    }
</style>

<div class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <img src="{{ asset('images/about-us.jpg') }}" alt="BTHC Collections" class="main-image">
    </section>

    <!-- Info Sections -->
    <div class="info-sections">
        <div class="info-section">
            <h2 class="info-title">Who We Are?</h2>
            <p class="info-description">
                BetterHope Collection adalah brand aksesoris etnik modern handmade yang menghadirkan keindahan budaya dalam sentuhan gaya masa kini. Setiap produk dibuat dengan cinta, ketelitian, dan nilai keaslian.
            </p>
        </div>

        <div class="info-section">
            <h2 class="info-title">What We Do?</h2>
            <p class="info-description">
                Kami merancang dan memproduksi aksesoris handmade berkualitas, menggabungkan unsur etnik, kreativitas lokal, dan desain modern untuk melengkapi gaya sehari-hari maupun momen spesial.
            </p>
        </div>

        <div class="info-section">
            <h2 class="info-title">Why Choose Us?</h2>
            <ul class="info-description">
                <li>- Handmade & limited pieces</li>
                <li>- Desain etnik modern yang unik</li>
                <li>- Kualitas terjaga dan penuh makna</li>
                <li>- Mendukung karya dan pengrajin lokal</li>
            </ul>
        </div>
    </div>

    <!-- Quote Section -->
    <section class="quote-section">
        <div class="quote-content">
            <span class="quote-mark">"</span>
            <p class="quote-text">
                Bebas Menjadi Diri Sendiri, Ekspresikan Gayamu.
            </p>
        </div>
        <div class="quote-image">
            <img src="{{ asset('images/kalung-home-scrolldown1.png') }}" alt="BTHC Collection">
        </div>
    </section>
</div>
@endsection

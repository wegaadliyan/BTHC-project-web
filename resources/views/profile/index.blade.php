@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        display: flex;
        flex-direction: row;
        gap: 32px;
        padding: 40px 80px;
        min-height: 100vh;
        background-color: #fff;
    }
    .profile-sidebar {
        width: 340px;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 48px 0 0 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 650px;
        margin-left: 32px;
    }
    .user-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background-color: #e6dfd5;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .user-name {
        font-size: 24px;
        font-weight: 700;
        color: #222;
        margin-bottom: 40px;
        text-align: center;
        letter-spacing: 0.5px;
    }
    .profile-menu {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 40px;
    }
    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 40px;
        font-size: 17px;
        color: #444;
        background: none;
        border: none;
        border-radius: 0;
        text-align: left;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }
    .profile-menu-item.active, .profile-menu-item:hover {
        background: #f3eee3;
        color: #222;
    }
    .profile-menu-item span {
        font-size: 20px;
        width: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .profile-content {
        flex: 1;
        background: #f3eee3;
        border-radius: 16px;
        padding: 48px;
        min-height: 600px;
        box-sizing: border-box;
    }
    @media (max-width: 1024px) {
        .profile-container {
            flex-direction: column;
            padding: 20px;
        }
        .profile-sidebar {
            width: 100%;
            min-height: unset;
            border-radius: 16px;
            padding: 32px 0 0 0;
        }
        .profile-content {
            padding: 24px;
            min-height: unset;
        }
    }
    @media (max-width: 768px) {
        .profile-container {
            flex-direction: column;
            padding: 8px;
        }
        .profile-sidebar {
            padding: 16px 0 0 0;
        }
        .profile-content {
            padding: 12px;
        }
    }
</style>


<div class="profile-container">
    <div class="profile-sidebar">
        <div class="user-avatar">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="#aaa">
                    <circle cx="12" cy="8" r="4" stroke-width="2"/>
                    <path stroke-width="2" d="M4 20c0-4 4-7 8-7s8 3 8 7"/>
                </svg>
            @endif
        </div>
        <div class="user-name">{{ Auth::user()->name }}</div>
        <nav class="profile-menu">
            <a href="{{ route('profile.edit') }}" class="profile-menu-item @if(Route::currentRouteName() == 'profile.edit') active @endif">
                <span>👤</span> Akun Saya
            </a>
            <a href="{{ route('profile.password') }}" class="profile-menu-item @if(Route::currentRouteName() == 'profile.password') active @endif">
                <span>🔐</span> Ubah Password
            </a>
            <a href="{{ route('profile.address') }}" class="profile-menu-item @if(Route::currentRouteName() == 'profile.address') active @endif">
                <span>📍</span> Alamat
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="profile-menu-item">
                <span>🚪</span> Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>
    <div class="profile-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if(Route::currentRouteName() == 'profile.edit')
            @include('profile.partials.edit')
        @elseif(Route::currentRouteName() == 'profile.password')
            @include('profile.partials.password')
        @elseif(Route::currentRouteName() == 'profile.address')
            @include('profile.partials.address')
        @else
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;">
                <h2 style="font-size: 2rem; font-weight: 700; color: #222; margin-bottom: 16px;">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p style="font-size: 1.1rem; color: #555; max-width: 480px; text-align: center;">Ini adalah halaman profil Anda. Kelola data akun, ubah password, dan atur alamat pengiriman dengan mudah melalui menu di sebelah kiri.</p>
                <div style="margin-top: 32px;">
                    <svg width="120" height="120" fill="none" viewBox="0 0 24 24" stroke="#e6dfd5">
                        <circle cx="12" cy="8" r="4" stroke-width="2"/>
                        <path stroke-width="2" d="M4 20c0-4 4-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
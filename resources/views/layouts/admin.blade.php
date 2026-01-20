<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #F5F1EB;
            padding: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #E6DFD5;
        }

        .sidebar-menu i {
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="sidebar">
            <h2>Admin</h2>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a></li>
                <li><a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="fas fa-image"></i> Banner Slider
                </a></li>
                <li><a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Produk
                </a></li>
                <li><a href="{{ route('admin.custom-products') }}" class="{{ request()->routeIs('admin.custom-products') ? 'active' : '' }}">
                    <i class="fas fa-magic"></i> Custom
                </a></li>
                <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User
                </a></li>
                <li><a href="{{ route('admin.payments') }}" class="{{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i> Payment
                </a></li>
                <li><a href="{{ route('admin.contacts') }}" class="{{ request()->routeIs('admin.contacts') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Contacts
                </a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a></li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
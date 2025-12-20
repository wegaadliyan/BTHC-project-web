@extends('layouts.admin')

@section('content')
<div class="dashboard">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <div class="dashboard-actions">
            <a href="{{ url('/') }}" class="btn-action btn-web" target="_blank">
                <i class="fas fa-globe"></i> Lihat Website
            </a>
            <a href="{{ url('/shop') }}" class="btn-action btn-shop" target="_blank">
                <i class="fas fa-shopping-bag"></i> Lihat Shop
            </a>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3>Total Sales</h3>
                <p>Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3>Total Orders</h3>
                <p>{{ $totalOrders }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3>Total Products</h3>
                <p>{{ $totalProducts + $totalCustomProducts }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>Total Customers</h3>
                <p>{{ $totalCustomers }}</p>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard {
    padding: 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-header h1 {
    margin: 0;
    font-size: 2.5rem;
    font-weight: 700;
}

.dashboard-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-web {
    background-color: #4CAF50;
    color: white;
}

.btn-web:hover {
    background-color: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
}

.btn-shop {
    background-color: #2196F3;
    color: white;
}

.btn-shop:hover {
    background-color: #0b7dda;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
}

.btn-action i {
    font-size: 1.1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #E6DFD5;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.stat-icon i {
    font-size: 20px;
    color: #333;
}

.stat-content h3 {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.stat-content p {
    margin: 5px 0 0;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}
</style>
@endsection
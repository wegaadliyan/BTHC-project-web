@extends('layouts.admin')

@section('content')
<div class="dashboard">
    <h1>Admin Dashboard</h1>
    
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
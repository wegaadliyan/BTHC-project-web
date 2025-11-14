<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CustomProduct;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomProducts = CustomProduct::count();
        $totalSales = Payment::sum('subtotal');
        $totalOrders = Payment::count();
        $totalCustomers = User::where('is_admin', false)->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCustomProducts', 
            'totalSales',
            'totalOrders',
            'totalCustomers'
        ));
    }

    public function products()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    public function customProducts()
    {
        $customProducts = CustomProduct::all();
        return view('admin.custom-products', compact('customProducts'));
    }

    public function users()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.users', compact('users'));
    }

    public function payments()
    {
        $payments = Payment::all();
        return view('admin.payments', compact('payments'));
    }

    public function profile()
    {
        return view('admin.profile');
    }
}
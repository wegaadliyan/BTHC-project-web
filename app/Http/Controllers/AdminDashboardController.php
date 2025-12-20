<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CustomProduct;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    // Tampilkan form tambah produk
    public function createProduct()
    {
        return view('admin.product-create');
    }

    // Simpan produk baru
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products,product_code',
            'name' => 'required',
            'price' => 'required|numeric',
            'weight' => 'required|numeric|min:100',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = basename($imagePath);
        }

        Product::create($validated);
        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Tampilkan form edit produk
    public function editProduct(Product $product)
    {
        return view('admin.product-edit', compact('product'));
    }

    // Update produk
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'sometimes|required|unique:products,product_code,' . $product->id,
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'weight' => 'sometimes|required|numeric|min:100',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = basename($imagePath);
        }

        $product->update($validated);
        return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate!');
    }

    // Hapus produk
    public function destroyProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomProducts = CustomProduct::count();
        $totalSales = Payment::sum('total_price');
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

    // Create custom product
    public function createCustomProduct()
    {
        return view('admin.custom-product-create');
    }

    // Store custom product
    public function storeCustomProduct(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:custom_products,product_code',
            'name' => 'required',
            'price' => 'required|numeric',
            'color' => 'nullable',
            'size' => 'nullable',
            'charm' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = basename($imagePath);
        }

        CustomProduct::create($validated);
        return redirect()->route('admin.custom-products')->with('success', 'Produk custom berhasil ditambahkan!');
    }

    // Edit custom product
    public function editCustomProduct(CustomProduct $customProduct)
    {
        return view('admin.custom-product-edit', compact('customProduct'));
    }

    // Update custom product
    public function updateCustomProduct(Request $request, CustomProduct $customProduct)
    {
        $validated = $request->validate([
            'product_code' => 'sometimes|required|unique:custom_products,product_code,' . $customProduct->id,
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'color' => 'nullable',
            'size' => 'nullable',
            'charm' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = basename($imagePath);
        }

        $customProduct->update($validated);
        return redirect()->route('admin.custom-products')->with('success', 'Produk custom berhasil diupdate!');
    }

    // Delete custom product
    public function destroyCustomProduct(CustomProduct $customProduct)
    {
        $customProduct->delete();
        return redirect()->route('admin.custom-products')->with('success', 'Produk custom berhasil dihapus!');
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

    public function confirmPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        // Update status for all items in the same order
        Payment::where('order_id', $payment->order_id)->update(['status' => 'confirmed']);

        return redirect()->route('admin.payments')->with('success', 'Pembayaran untuk Order ID ' . $payment->order_id . ' telah dikonfirmasi.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function checkout(Request $request)
    {
        // Simpan data alamat ke session, lalu redirect ke halaman checkout
        $alamat = $request->only([
            'nama','telp','provinsi','provinsi_nama',
            'kota','kota_nama',
            'kecamatan','kecamatan_nama',
            'kodepos','detail'
        ]);
        session(['alamat_checkout' => $alamat]);
        return redirect()->route('checkout.show');
    }

    public function showCheckout()
    {
        $user = auth()->user();
        $cartItems = Cart::where('user_id', $user->id)->with(['product','customProduct'])->get();
        $alamat = session('alamat_checkout', []);
        
        // Calculate total weight from cart items
        $totalWeight = 0;
        foreach ($cartItems as $item) {
            $product = $item->product ?? $item->customProduct;
            if ($product && $product->weight) {
                $totalWeight += $product->weight * $item->quantity;
            }
        }
        
        return view('checkout', compact('cartItems','alamat','totalWeight'));
    }
    public function index()
    {
        $user = auth()->user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        return view('cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $user = auth()->user();

        // Jika custom_product_id ada, berarti produk custom
        if ($request->has('custom_product_id')) {
            $request->validate([
                'custom_product_id' => 'required|exists:custom_products,id',
                'quantity' => 'required|integer|min:1',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'charm' => 'nullable|string',
                'price' => 'required|numeric',
            ]);

            $customProductId = $request->custom_product_id;
            $quantity = $request->quantity;
            $color = $request->color;
            $size = $request->size;
            $charm = $request->charm;
            $price = $request->price;

            // Cek apakah produk custom sudah ada di cart user dengan opsi yang sama
            $cartItem = Cart::where('user_id', $user->id)
                ->where('custom_product_id', $customProductId)
                ->where('color', $color)
                ->where('size', $size)
                ->where('charm', $charm)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->price = $price * $cartItem->quantity;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'custom_product_id' => $customProductId,
                    'quantity' => $quantity,
                    'price' => $price * $quantity,
                    'color' => $color,
                    'size' => $size,
                    'charm' => $charm,
                ]);
            }
            return redirect()->back()->with('success', 'Produk custom berhasil ditambahkan ke keranjang!');
        } else {
            // Produk biasa
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity;
            $product = Product::findOrFail($productId);

            $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->price = $product->price * $cartItem->quantity;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price * $quantity,
                ]);
            }
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        $quantity = max(1, (int)$request->input('quantity', 1));
        $cartItem->quantity = $quantity;
        if ($cartItem->custom_product_id && $cartItem->customProduct) {
            $cartItem->price = $cartItem->customProduct->price * $quantity;
        } elseif ($cartItem->product_id && $cartItem->product) {
            $cartItem->price = $cartItem->product->price * $quantity;
        } else {
            $cartItem->price = 0;
        }
        $cartItem->save();
        return redirect()->route('cart.index')->with('success', 'Quantity berhasil diupdate!');
    }

    public function delete($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang!');
    }
}

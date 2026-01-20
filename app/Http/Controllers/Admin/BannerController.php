<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // Tampilkan daftar banner
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    // Tampilkan form tambah banner
    public function create()
    {
        return view('admin.banners.create');
    }

    // Simpan banner baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'link' => 'nullable|url',
            'alt_text' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_active' => 'boolean'
        ]);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = basename($imagePath);
        }

        // Set order otomatis
        $validated['order'] = Banner::max('order') + 1;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Banner::create($validated);
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    // Tampilkan form edit banner
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    // Update banner
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'link' => 'nullable|url',
            'alt_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_active' => 'boolean'
        ]);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($banner->image && file_exists(storage_path('app/public/banners/' . $banner->image))) {
                unlink(storage_path('app/public/banners/' . $banner->image));
            }
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = basename($imagePath);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $banner->update($validated);
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui!');
    }

    // Hapus banner
    public function destroy(Banner $banner)
    {
        // Hapus gambar
        if ($banner->image && file_exists(storage_path('app/public/banners/' . $banner->image))) {
            unlink(storage_path('app/public/banners/' . $banner->image));
        }

        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus!');
    }

    // Update urutan banner (untuk drag-drop)
    public function updateOrder(Request $request)
    {
        $order = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer'
        ]);

        foreach ($order['order'] as $index => $bannerId) {
            Banner::where('id', $bannerId)->update(['order' => $index]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan banner berhasil diperbarui!']);
    }

    // Toggle status banner
    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return redirect()->route('admin.banners.index')->with('success', 'Status banner berhasil diubah!');
    }
}

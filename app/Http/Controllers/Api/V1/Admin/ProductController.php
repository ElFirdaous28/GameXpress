<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('images')->get();
        return response()->json([
            'status' => 'success',
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'primary_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'status' => $request->stock > 0 ? 'available' : 'unavailable',
        ]);

        // store primary image
        $primaryImagePath = $request->file('primary_image')->store('products', 'public');
        $product->images()->create([
            'image_path' => $primaryImagePath,
            'is_primary' => true
        ]);

        // store other images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $imagePath,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'product' => $product->load('images')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'product' => $product->load('images')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'primary_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'status' => $request->stock > 0 ? 'available' : 'unavailable'
        ]);

        // Update primary image
        if ($request->hasFile('primary_image')) {
            if ($product->images()->where('is_primary', true)->exists()) {
                $oldPrimaryImage = $product->images()->where('is_primary', true)->first();
                Storage::disk('public')->delete($oldPrimaryImage->image_path);
                $oldPrimaryImage->delete();
            }

            $primaryImagePath = $request->file('primary_image')->store('products', 'public');
            $product->images()->create([
                'image_path' => $primaryImagePath,
                'is_primary' => true
            ]);
        }

        // additional images
        if ($request->hasFile('images')) {
            // Delete all existing images except the primary one
            $product->images()->where('is_primary', false)->delete();

            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $imagePath,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('images')
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product soft deleted successfully.'
        ]);
    }

    public function forceDestroy(string $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->forceDelete();

        return response()->json([
            'message' => 'Product and its images permanently deleted successfully.'
        ]);
    }
}

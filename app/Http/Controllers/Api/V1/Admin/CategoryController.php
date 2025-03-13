<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        if ($request->hasFile('icon_path')) {
            $imagePath = $request->file('icon_path')->store('categories', 'public');
            $category->icon_path = $imagePath;
            $category->save();
        }
        return response()->json([
            'message' => 'category created successfully',
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update name & slug
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        // Handle Image Upload
        if ($request->hasFile('icon_path')) {
            if ($category->icon_path) {
                Storage::disk('public')->delete($category->icon_path);
            }

            // Store new image
            $imagePath = $request->file('icon_path')->store('categories', 'public');
            $category->icon_path = $imagePath;
            $category->save();
        }

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->icon_path) {
            Storage::disk('public')->delete($category->icon_path);
        }
        $category->delete();
        return response()->json([
            'message' => 'category deleted successfully',
        ]);
     }
}

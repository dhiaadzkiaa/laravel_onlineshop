<?php

namespace App\Http\Controllers;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Categories::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        if (!auth()->check()) { //jika user tidak terautentikasi
            return response()->json(['message' => 'Unauthorized'], 401); //maka kembalikan response unauthorized
        }

        $category = Categories::create($validatedData);

        return response()->json(['message' => 'Categorycreated', 'category' => $category],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::find($id);

        if ($category) {
            return $category->load('products');
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        if (!auth()->check()) { //jika user tidak terautentikasi
            return response()->json(['message' => 'Unauthorized'], 401); //maka kembalikan response unauthorized
        }

        $category->update($validatedData);
        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Categories::find($id);
        if (!$product) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        if (!auth()->check()) { //jika user tidak terautentikasi
            return response()->json(['message' => 'Unauthorized'], 401); //maka kembalikan response unauthorized
        }

        $product->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}

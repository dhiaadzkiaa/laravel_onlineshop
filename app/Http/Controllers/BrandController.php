<?php

namespace App\Http\Controllers;
use App\Models\Brands;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Brands::all();
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

        $brand = Brands::create($validatedData);

        return response()->json(['message' => 'Brandcreated', 'brand' => $brand],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brands::find($id);

        if ($brand) {
            return $brand->load('products');
        } else {
            return response()->json(['message' => 'Brand not found'], 404);
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

        $brand = Brands::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        if (!auth()->check()) { //jika user tidak terautentikasi
            return response()->json(['message' => 'Unauthorized'], 401); //maka kembalikan response unauthorized
        }

        $brand->update($validatedData);
        return response()->json(['message' => 'Brand updated successfully', 'brand' => $brand]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brands::find($id);
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully']);
    }
}

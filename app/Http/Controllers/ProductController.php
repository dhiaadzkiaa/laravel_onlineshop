<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //return Product::with(['category', 'brand'])->get();

        $query = Product::with(['category', 'brand']);

        // apply search filter by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // filter by category_id
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // sorting defaultnya by updated_at
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $allowedSorts = ['name', 'price', 'stock', 'created_at', 'updated_at']; //mendefisikan kolom yang dapat diurutkan
            $sortBy = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'updated_at'; // deafult sorting by updated_at
            $sortOrder = $request->sort_order === 'asc' ? 'asc' : 'desc'; // default sorting order

            $query->orderBy($sortBy, $sortOrder); // apply sorting to the query
        }

        // pagination
            $perPage = $request->get('per_page', 10); // set default 10

            return $query->paginate($perPage);
    }


    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
        {
            $validatedData = $request->validate([
                'name' => 'required|string|max:50',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // image validation
            ]);

            //upload image
            $path = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = $image->store('products', 'public'); // store image in
                $validatedData['image'] = $path; // add image path to validated data
            }


            $product = Product::create($validatedData);

            return response()->json(['message' => 'Product created','product' => $product], 201);
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if ($product) {
            return $product->load(['category', 'brand']);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if (!auth()->check()) { //jika user tidak terautentikasi
            return response()->json(['message' => 'Unauthorized'], 401); //maka kembalikan response unauthorized
        }

        $product->update($validateData);
        return response()->json(['message' => 'Product updated', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if (!auth()->check()) { //jika user tidak terautentikasi
            return response()->json(['message' => 'Unauthorized'], 401); //maka kembalikan response unauthorized
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}

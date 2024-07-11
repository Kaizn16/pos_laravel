<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Inventory.products');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        session(['view_title' => 'New Product']);
        return view('Inventory.product-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_code' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,category_id',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
            'uom' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'supplier_id' => 'required|integer|exists:suppliers,supplier_id',
            'status' => 'sometimes|boolean',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 0;
        }

        // Handle the image upload if present
        if ($request->hasFile('product_image')) {
            $filenameWithExtension = $request->file('product_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('product_image')->storeAs('public/product_image', $filenameToStore);
            $validated['product_image'] = $filenameToStore;
        } else {
            $validated['product_image'] = 'Default-Image.png';
        }

        // Create a new product record
        $product = Product::create($validated);

        if ($product) {
            return response()->json(['message' => 'Product saved successfully!'], 200);
        } else {
            return response()->json(['message' => 'Unable to save product. Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

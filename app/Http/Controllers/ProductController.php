<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\UnitOfMeasure;
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
        $categories_query = Category::where('is_deleted', 0)->where('status', '!=', 0);
        $categories = $categories_query->orderBy('category_id', 'desc')->get();

        $uom_query = UnitOfMeasure::where('is_deleted', 0)->where('status', '!=', 0);
        $uoms = $uom_query->orderBy('uom_id', 'desc')->get();

        session(['view_title' => 'New Product']);
        return view('Inventory.product-form', compact("categories", "uoms"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_code' => 'required|string|max:255|unique:product,product_code',
            'product_name' => 'required|string|max:255|unique:product,product_name',
            'category_id' => 'required|integer|exists:category,category_id',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
            'uom_id' => 'required|integer|exists:uom,uom_id',
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
    public function edit($product_id)
    {
        $product = Product::findOrFail($product_id);

        $queryCategories = Category::where('is_deleted', 0);
        $categories = $queryCategories->orderBy('category_id', 'desc')->get();

        $queryUnitOfMeasures = UnitOfMeasure::where('is_deleted', 0);
        $uoms = $queryUnitOfMeasures->orderBy('uom_id', 'desc')->get();

        if(!$product) {
            return redirect()->route('Inventory.products')->with('message', 'Product not Found');
        }
        
        Session(['view_title' => 'Edit Product']);
        return view('Inventory.product-form', compact('product', 'categories', 'uoms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $product_id)
    {
        // Validate the request data
        $validated = $request->validate([
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_code' => 'required|string|max:255|unique:product,product_code,' . $product_id . ',product_id',
            'product_name' => 'required|string|max:255|unique:product,product_name,' . $product_id . ',product_id',
            'category_id' => 'required|integer|exists:category,category_id',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
            'uom_id' => 'required|integer|exists:uom,uom_id',
            'status' => 'sometimes|boolean',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 0;
        }

        // Find the product by ID
        $product = Product::findOrFail($product_id);

        // Handle the image upload if present
        if ($request->hasFile('product_image')) {
            $filenameWithExtension = $request->file('product_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('product_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('product_image')->storeAs('public/product_image', $filenameToStore);
            $validated['product_image'] = $filenameToStore;
        }

        // Update the product record
        $product->update($validated);

        if ($product) {
            return response()->json(['message' => 'Product updated successfully!'], 200);
        } else {
            return response()->json(['message' => 'Unable to update product. Please try again.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy() {

    }

    public function delete($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->is_deleted = 1;
        $product->save();
    
        return response()->json(['message' => 'Product deleted successfully!']);
    }

    public function getProducts(Request $request)
    {
        $query = Product::where('is_deleted', 0);

        try {
            // History Request
            if ($request->has('history')) {
                $products = $query->with('category', 'uom')
                                ->orderBy('product_id', 'desc')
                                ->limit(15)
                                ->get();

                return response()->json(['success' => true, 'data' => $products]);
            }

            // Table Request
            $pageSize = $request->input('pageSize', 10);
            $products = $query->with('category', 'uom')
                            ->orderBy('product_id', 'asc')
                            ->paginate($pageSize);

            return response()->json(['success' => true, 'data' => $products]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}

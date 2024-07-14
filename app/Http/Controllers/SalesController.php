<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Fetching categories
        $categories = Category::where('is_deleted', 0)
                            ->where('status', '!=', 0)
                            ->orderByRaw("SUBSTR(category_name, 1, 1)")
                            ->get();

        // Base product query
        $product_query = Product::where('is_deleted', 0)
                                ->where('status', '!=', 0);

        // Filter by category_id if provided
        $category_id = $request->input('category_id');
        if ($category_id) {
            $product_query->where('category_id', $category_id);
        }

        // Filter by search term if provided
        $search_term = $request->input('search_term');
        if ($search_term) {
            $product_query->where('product_name', 'like', '%' . $search_term . '%');
        }

        // Fetching products with the applied filters
        $products = $product_query->with('category', 'uom')
                                ->orderByRaw("SUBSTR(product_name, 1, 1)")
                                ->get();

        // Fetching stocks
        $stocks = Stock::all();

        // Preparing response data
        $data = [
            'categories' => $categories,
            'products' => $products,
            'stocks' => $stocks,
        ];


        Log::info('Category ID: ' . $request->input('category_id'));
        Log::info('Search Term: ' . $request->input('search_term'));
        // Returning the response as JSON
        return response()->json($data);
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

    public function preview($product_id)
    {
        try {
            $product = Product::with(['category'])->findOrFail($product_id);    
            $stock = Stock::where('product_id', $product_id)->get();

            return response()->json([
                'product' => $product,
                'stock' =>$stock,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Inventory.stocks');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Product::where('is_deleted', 0)
                            ->where('status', '!=', 0)
                            ->whereNotIn('product_id', function ($query) {
                                $query->select('product_id')
                                    ->from('stock');
                            })
                            ->orderBy('product_id', 'desc')
                            ->get();

        $stocks = Stock::orderBy('stock_id', 'desc')->get();

        $supplier_query = Supplier::where('is_deleted', 0)->where('status', '!=', 0);
        $suppliers = $supplier_query->orderBy('supplier_id', 'desc')->get();

        session(['view_title' => 'New Stock']);
        return view('Inventory.stock-form', compact('stocks', 'products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,product_id',
            'supplier_id' => 'required|integer|exists:supplier,supplier_id',
            'stock_amount' => 'required|numeric|min:1',
            'expiration_date' => 'required|date',
        ]);

        $existingStock = Stock::where('product_id', $request->input('product_id'))->first();

        if ($existingStock) {
            $product = Product::find($request->input('product_id'));
            return response()->json(['message' => 'Product ' . $product->product_name . ' already has a stock.'], 422);
        }

        $stock = Stock::create([
            'product_id' => $request->input('product_id'),
            'supplier_id' => $request->input('supplier_id'),
            'stock_amount' => $request->input('stock_amount'),
            'total_stock' => $request->input('stock_amount'),
            'expiration_date' => $request->input('expiration_date'),
        ]);

        if ($stock) {
            return response()->json(['message' => 'Stock saved successfully!'], 200);
        } else {
            return response()->json(['message' => 'Unable to save stock. Please try again.'], 500);
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
    public function edit($stock_id)
    {
        $stock = Stock::findOrFail($stock_id);

        $products_query = Product::where('is_deleted', 0)->where('status', '!=', 0);
        $products = $products_query->orderBy('product_id', 'desc')->get();

        $supplier_query = Supplier::where('is_deleted', 0)->where('status', '!=', 0);
        $suppliers = $supplier_query->orderBy('supplier_id', 'desc')->get();

        if (!$stock) {
            return redirect()->route('Inventory.stocks')->with('message', 'Stock not found.');
        }

        session(['view_title' => 'Edit Product Stock']);
        return view('Inventory.stock-form', compact('stock', 'products', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $stock_id)
    {
        $request->validate([
            'supplier_id' => 'required|integer|exists:supplier,supplier_id',
            'stock_amount' => 'required|numeric|min:1',
            'expiration_date' => 'required|date',
        ]);
    
        $stock = Stock::findOrFail($stock_id);
        $stock->supplier_id = $request->supplier_id;
        $stock->stock_amount = $request->stock_amount;
        $stock->total_stock = $request->stock_amount;
        $stock->expiration_date = $request->expiration_date;  
        $stock->save();
    
        return response()->json(['message' => 'Product Stock updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getStocks(Request $request) 
    {
        $query = Stock::query();

        // History Request
        if ($request->has('history')) {
            $stocks = $query->with('product', 'supplier')->orderBy('stock_id', 'desc')->limit(15)->get();
            return response()->json($stocks);
        }

        // Table Request
        $pageSize = $request->input('pageSize', 10);
        $stocks = $query->with('product', 'supplier')->orderBy('stock_id', 'desc')->paginate($pageSize);

        return response()->json($stocks);
    }
}
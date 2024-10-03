<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Purchases;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        $stockValues = [];
        $stockBorderColor = 'rgba(255, 255, 255, 2)';

        $backgroundColorSet = [
            'rgba(255, 182, 193, 1)',   // Pastel pink
            'rgba(255, 223, 186, 1)',   // Pastel orange
            'rgba(173, 216, 230, 1)',   // Pastel blue
            'rgba(144, 238, 144, 1)',   // Pastel green
            'rgba(221, 160, 221, 1)',   // Pastel purple
            'rgba(255, 255, 224, 1)',   // Pastel yellow
            'rgba(255, 192, 203, 1)',   // Pastel pink (slightly different shade)
            'rgba(255, 224, 189, 1)',   // Pastel peach
            'rgba(204, 204, 255, 1)',   // Pastel lavender
            'rgba(204, 255, 255, 1)',   // Pastel cyan
            'rgba(255, 153, 153, 1)',   // Pastel red RAINBOW
            'rgba(255, 204, 153, 1)',   // Pastel orange RAINBOW
            'rgba(153, 204, 255, 1)',   // Pastel blue RAINBOW
            'rgba(153, 255, 153, 1)',   // Pastel green RAINBOW
            'rgba(255, 153, 255, 1)',   // Pastel purple RAINBOW
            'rgba(255, 255, 153, 1)',   // Pastel yellow RAINBOW
            'rgba(153, 153, 255, 1)',   // Pastel lavender RAINBOW
            'rgba(178, 102, 255, 1)',   // Pastel mauve RAINBOW
            'rgba(153, 255, 255, 1)',   // Pastel cyan RAINBOW
            'rgba(255, 178, 102, 1)',   // Pastel peach RAINBOW
        ];

        shuffle($backgroundColorSet);

        $backgroundColor = [];
        $borderColor = [];

        foreach ($categories as $index => $category) {
            $stockValue = Purchases::whereHas('product', function ($query) use ($category) {
                $query->where('category_id', $category->category_id);
            })->sum(DB::raw('purchase_price * stock_amount'));

            $stockValues['labels'][] = $category->category_name;
            $stockValues['datasets'][0]['data'][] = $stockValue;

            // Use a predefined color (cycling through shuffled colors) for each category
            $backgroundColor[] = $backgroundColorSet[$index % count($backgroundColorSet)];
            $borderColor[] = $stockBorderColor;
        }

        $stockValueData = [
            'labels' => $stockValues['labels'],
            'datasets' => [
                [
                    'label' => 'Stock Value',
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'borderWidth' => 1,
                    'data' => $stockValues['datasets'][0]['data'],
                ],
            ],
        ];


        // Fetch stock data
        $stocks = Stock::select('stock.*', 'product.product_name')->join('product', 'stock.product_id', '=', 'product.product_id')->get();
        $labels = $stocks->pluck('product_name')->toArray();
        $stockLevelBackgroundColor = []; // Separate array for stock level colors

        foreach ($stocks as $stock) {
            $stockLevelBackgroundColor[] = $backgroundColorSet[$stock->product_id % count($backgroundColorSet)];
        }

        $StockLevel = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Stock Level',
                    'backgroundColor' => $stockLevelBackgroundColor,
                    'borderColor' => $borderColor,
                    'borderWidth' => 1,
                    'data' => $stocks->pluck('stock_amount')->toArray(),
                ],
            ],
        ];

        return view('Inventory.index', [
            'productStockValue' => json_encode($stockValueData),
            'StockLevel' => json_encode($StockLevel)
        ]);
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

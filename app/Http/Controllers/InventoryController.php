<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockValueData = [
            'labels' => ['Rice', 'Canned Goods', 'Snacks', 'Beverages', 'Toiletries', 'Household Items', 'Chips', 'Bread', 'Sweets', 'Condiments', 'Medicine', 'Others'],
            'datasets' => [
                [
                    'label' => 'Stock Value',
                    'backgroundColor' => [
                        'rgba(255, 0, 0, 0.4)',    // Rice
                        'rgba(255, 128, 0, 0.4)',    // Canned Goods
                        'rgba(255, 255, 0, 0.4)',    // Snacks
                        'rgba(128, 255, 0, 0.4)',    // Beverages
                        'rgba(0, 255, 0, 0.4)',   // Toiletries
                        'rgba(0, 255, 128, 0.4)',    // Household Items
                        'rgba(0, 255, 255, 0.4)',    // Chips
                        'rgba(0, 128, 255, 0.4)',    // Bread
                        'rgba(0, 0, 255, 0.4)',    // Sweets
                        'rgba(128, 0, 255, 0.4)',    // Condiments
                        'rgba(255, 0, 255, 0.4)',   // Medicine
                        'rgba(255, 0, 128, 0.4)',    // Others
                    ],
                    'borderColor' => [
                        'rgba(255, 0, 0, 1)',    // Rice
                        'rgba(255, 128, 0, 1)',    // Canned Goods
                        'rgba(255, 255, 0, 1)',    // Snacks
                        'rgba(128, 255, 0, 1)',    // Beverages
                        'rgba(0, 255, 0, 1)',   // Toiletries
                        'rgba(0, 255, 128, 1)',    // Household Items
                        'rgba(0, 255, 255, 1)',    // Chips
                        'rgba(0, 128, 255, 1)',    // Bread
                        'rgba(0, 0, 255, 1)',    // Sweets
                        'rgba(128, 0, 255, 1)',    // Condiments
                        'rgba(255, 0, 255, 1)',   // Medicine
                        'rgba(255, 0, 128, 1)',    // Others
                    ],
                    'borderWidth' => 1,
                    'data' => [150, 180, 160, 200, 220, 250, 300, 280, 260, 240, 220, 200],
                ],
            ],
        ];        
        
        $stockMovementData = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'datasets' => [
                [
                    'label' => 'Stock Movement',
                    'backgroundColor' => [
                        'rgba(0, 128, 0, 0.4)',    // January
                        'rgba(0, 128, 128, 0.4)',    // February
                        'rgba(0, 255, 128, 0.4)',    // March
                        'rgba(128, 0, 128, 0.4)',    // April
                        'rgba(128, 128, 0, 0.4)',   // May
                        'rgba(128, 0, 0, 0.4)',    // June
                        'rgba(128, 128, 128, 0.4)',    // July
                        'rgba(0, 0, 0, 0.4)',    // August
                        'rgba(0, 128, 255, 0.4)',    // September
                        'rgba(0, 0, 128, 0.4)',    // October
                        'rgba(255, 128, 255, 0.4)',   // November
                        'rgba(128, 128, 255, 0.4)',    // December
                    ],
                    'borderColor' => [
                        'rgba(0, 128, 0, 1)',    // January
                        'rgba(0, 128, 128, 1)',    // February
                        'rgba(0, 255, 128, 1)',    // March
                        'rgba(128, 0, 128, 1)',    // April
                        'rgba(128, 128, 0, 1)',   // May
                        'rgba(128, 0, 0, 1)',    // June
                        'rgba(128, 128, 128, 1)',    // July
                        'rgba(0, 0, 0, 1)',    // August
                        'rgba(0, 128, 255, 1)',    // September
                        'rgba(0, 0, 128, 1)',    // October
                        'rgba(255, 128, 255, 1)',   // November
                        'rgba(128, 128, 255, 1)',    // December
                    ],
                    'borderWidth' => 1,
                    'data' => [100, 120, 130, 140, 160, 180, 200, 220, 210, 190, 170, 150],
                ],
            ],
        ];
        
        return view('Inventory.index', [
            'productStockValue' => json_encode($stockValueData),
            'productStockMovement' => json_encode($stockMovementData)
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

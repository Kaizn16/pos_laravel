<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Example data for the top 10 trend sales change this later
        $topTrendSalesData = [
            'labels' => ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5', 'Product 6', 'Product 7', 'Product 8', 'Product 9', 'Product 10'],
            'datasets' => [
                [
                    'label' => 'Top 10 Best Seller',
                    'backgroundColor' => [
                        'rgba(254, 227, 39, 0.8)', 
                        'rgba(253, 202, 84, 0.8)', 
                        'rgba(246, 165, 112, 0.8)', 
                        'rgba(241, 150, 155, 0.8)', 
                        'rgba(240, 138, 177, 0.8)', 
                        'rgba(199, 141, 189, 0.8)', 
                        'rgba(146, 125, 182, 0.8)', 
                        'rgba(93, 160, 215, 0.8)', 
                        'rgba(0, 179, 225, 0.8)', 
                        'rgba(101, 189, 165, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(254, 227, 39, 1)', 
                        'rgba(253, 202, 84, 1)', 
                        'rgba(246, 165, 112, 1)', 
                        'rgba(241, 150, 155, 1)', 
                        'rgba(240, 138, 177, 1)', 
                        'rgba(199, 141, 189, 1)', 
                        'rgba(146, 125, 182, 1)', 
                        'rgba(93, 160, 215, 1)', 
                        'rgba(0, 179, 225, 1)', 
                        'rgba(101, 189, 165, 1)',
                    ],
                    'borderWidth' => 1,
                    'data' => [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                ],
            ],
        ];

        $topTrendCategoriesData = [
            'labels' => ['Soft Drinks', 'Snacks', 'Instant Noodles', 'Canned Goods', 'Condiments', 'Bread', 'Personal Care', 'Cleaning Supplies', 'Cigarettes', 'Coffee'],
            'datasets' => [
                [
                    'label' => 'Top 10 Best Categories',
                    'backgroundColor' => [
                        'rgba(254, 227, 39, 0.8)', 
                        'rgba(253, 202, 84, 0.8)', 
                        'rgba(246, 165, 112, 0.8)', 
                        'rgba(241, 150, 155, 0.8)', 
                        'rgba(240, 138, 177, 0.8)', 
                        'rgba(199, 141, 189, 0.8)', 
                        'rgba(146, 125, 182, 0.8)', 
                        'rgba(93, 160, 215, 0.8)', 
                        'rgba(0, 179, 225, 0.8)', 
                        'rgba(101, 189, 165, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(254, 227, 39, 1)', 
                        'rgba(253, 202, 84, 1)', 
                        'rgba(246, 165, 112, 1)', 
                        'rgba(241, 150, 155, 1)', 
                        'rgba(240, 138, 177, 1)', 
                        'rgba(199, 141, 189, 1)', 
                        'rgba(146, 125, 182, 1)', 
                        'rgba(93, 160, 215, 1)', 
                        'rgba(0, 179, 225, 1)', 
                        'rgba(101, 189, 165, 1)',
                    ],
                    'borderWidth' => 1,
                    'data' => [150, 120, 110, 100, 90, 80, 70, 60, 50, 40],
                ],
            ],
        ];
        

        $monthlySalesData = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'datasets' => [
                [
                    'label' => 'Monthly Sales',
                    'backgroundColor' => [
                        'rgba(255, 0, 0, 0.4)',    // January
                        'rgba(255, 128, 0, 0.4)',    // February
                        'rgba(255, 255, 0, 0.4)',    // March
                        'rgba(128, 255, 0, 0.4)',    // April
                        'rgba(0, 255, 0, 0.4)',   // May
                        'rgba(0, 255, 128, 0.4)',    // June
                        'rgba(0, 255, 255, 0.4)',    // July
                        'rgba(0, 128, 255, 0.4)',    // August
                        'rgba(0, 0, 255, 0.4)',    // September
                        'rgba(128, 0, 255, 0.4)',    // October
                        'rgba(255, 0, 255, 0.4)',   // November
                        'rgba(255, 0, 128, 0.4)',    // December
                    ],
                    'borderColor' => [
                        'rgba(255, 0, 0, 1)',    // January
                        'rgba(255, 128, 0, 1)',    // February
                        'rgba(255, 255, 0, 1)',    // March
                        'rgba(128, 255, 0, 1)',    // April
                        'rgba(0, 255, 0, 1)',   // May
                        'rgba(0, 255, 128, 1)',    // June
                        'rgba(0, 255, 255, 1)',    // July
                        'rgba(0, 128, 255, 1)',    // August
                        'rgba(0, 0, 255, 1)',    // September
                        'rgba(128, 0, 255, 1)',    // October
                        'rgba(255, 0, 255, 1)',   // November
                        'rgba(255, 0, 128, 1)',    // December
                    ],

                    'borderWidth' => 1,
                    'data' => [1000, 1200, 900, 1500, 1800, 2000, 2200, 2400, 2100, 1800, 1500, 1300],
                ],
            ],
        ];
        
        return view('Dashboard.index', [
            'topTrendSalesData' => json_encode($topTrendSalesData),
            'topTrendCategoriesData' => json_encode($topTrendCategoriesData),
            'monthlySalesData' => json_encode($monthlySalesData),
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

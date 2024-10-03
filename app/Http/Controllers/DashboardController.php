<?php

namespace App\Http\Controllers;

use App\Models\Purchases;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $dailySales = Transaction::where('status', 'completed')->whereDate('transaction_date', Carbon::today())->sum('grand_total');
        $dailyCustomers = Transaction::where('status', 'completed')->whereDate('transaction_date', Carbon::today())->count();

        $transactionDetails = TransactionDetail::where('is_refunded', 0)->with('product', 'transaction')->whereDate('created_at', Carbon::today())->get();
        $totalSalesValue = 0;
        $totalPurchaseValue = 0;

        foreach ($transactionDetails as $detail) {
            // Calculate the sales value for each transaction detail
            $salesValue = ($detail->product_price * $detail->quantity) - $detail->transaction->discount_amount;
            $totalSalesValue += $salesValue;
    
            // Fetch purchase price from related product
            $purchasePrice = $detail->product->purchase_price;
    
            // Calculate the purchase value for each transaction detail
            $purchaseValue = $purchasePrice * $detail->quantity;
            $totalPurchaseValue += $purchaseValue;
        }
    
        // Calculate daily profits
        $dailyProfits = $totalSalesValue - $totalPurchaseValue;

        $totalItemSold = TransactionDetail::where('is_refunded', 0)->whereDate('created_at', Carbon::today())->sum('quantity');

        $recentlySold = TransactionDetail::with('product')->latest('created_at')->orderBy('created_at', 'desc')->take(20)->get();
        $transactions = Transaction::where('status', '!=', 'pending')->with('customer')->latest('transaction_date')->take(15)->get();


        // CHARTS
        $currentYear = Carbon::now()->year;

        $transactionsByMonth = Transaction::whereYear('transaction_date', $currentYear)
            ->where('status', 'completed')
            ->selectRaw('MONTH(transaction_date) as month, SUM(grand_total) as total_sales')
            ->groupByRaw('MONTH(transaction_date)')
            ->orderByRaw('MONTH(transaction_date)')
            ->get();

        $monthlySalesData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Monthly Sales',
                    'backgroundColor' => [],
                    'borderColor' => [],
                    'borderWidth' => 1,
                    'data' => [],
                ],
            ],
        ];

        foreach(range(1, 12) as $month) {
            $monthName = Carbon::create()->month($month)->monthName;
            $monthlySalesData['labels'][] = $monthName;

            $found = $transactionsByMonth->where('month', $month)->first();
            if ($found) {
                $monthlySalesData['datasets'][0]['data'][] = $found->total_sales;
            } else {
                $monthlySalesData['datasets'][0]['data'][] = 0;
            }

            // Define RGBA colors for each month (you can adjust colors as needed)
            $monthlySalesData['datasets'][0]['backgroundColor'][] = "rgba(0, 0, 255, 0.4)";
            $monthlySalesData['datasets'][0]['borderColor'][] = "rgba(0, 0, 255, 1)";
        }
        
        // TREND PRODUCTS
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        $labels = [];
        $data = [];

        foreach ($topProducts as $index => $product) {
            $labels[] = $product->product->product_name;
            $data[] = $product->total_quantity;
        }

        $topTrendSalesData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Top 5 Best Seller',
                    'backgroundColor' => [
                        'rgba(254, 227, 39, 0.8)', 
                        'rgba(253, 202, 84, 0.8)',
                        'rgba(246, 165, 112, 0.8)', 
                        'rgba(241, 150, 155, 0.8)', 
                        'rgba(240, 138, 177, 0.8)', 
                    ],
                    'borderColor' => [
                        'rgba(254, 227, 39, 1)', 
                        'rgba(253, 202, 84, 1)', 
                        'rgba(246, 165, 112, 1)', 
                        'rgba(241, 150, 155, 1)', 
                        'rgba(240, 138, 177, 1)',
                    ],
                    'borderWidth' => 1,
                    'data' => $data,
                ],
            ],
        ];

        return view('Dashboard.index', [
            'topTrendSalesData' => json_encode($topTrendSalesData),
            'monthlySalesData' => json_encode($monthlySalesData),
        ], compact('transactions', 'recentlySold', 'dailySales', 'dailyCustomers', 'dailyProfits', 'totalItemSold'));

    }
}

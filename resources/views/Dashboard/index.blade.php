@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-apps"></i>
            <span class="text">Dashboard</span>
        </div>

        <div class="boxes boxes1">
            <div class="box box1">
                <i class="uil uil-calendar-alt"></i>
                <span class="text">Daily Sales</span>
                <span class="number">
                    @isset($dailySales)
                        ₱ {{ number_format($dailySales, 2) }}
                    @endisset
                </span>
            </div>
            <div class="box box2">
                <i class="uil uil-users-alt"></i>
                <span class="text">Daily Customers</span>
                <span class="number">
                    @isset($dailyCustomers)
                        {{ $dailyCustomers }}
                    @endisset
                </span>
            </div>
            <div class="box box3">
                <i class="uil uil-money-insert"></i>
                <span class="text">Daily Profits</span>
                <span class="number">
                    @isset($dailyProfits)
                        ₱ {{ number_format($dailyProfits, 2) }}
                    @endisset
                </span>
            </div>
            <div class="box box4">
                <i class="uil uil-box"></i>
                <span class="text">Daily Item Sold</span>
                <span class="number">
                    @isset($totalItemSold)
                        {{ $totalItemSold }}
                    @endisset
                </span>
            </div>
        </div>

        {{-- <div class="boxes boxes2">
            <div class="box box4">
                <i class="uil uil-box"></i>
                <span class="text">Total Item Sold</span>
                <span class="number">
                    @isset($totalItemSold)
                        {{ $totalItemSold }}
                    @endisset
                </span>
            </div>
        </div> --}}
        
    </div>

    <div class="dashboard-chart">
        <div class="dashboard-chart-data">
            <div class="chart chart1">
                <strong class="text"><i class="uil uil-chart-bar"></i> Mothly Sales</strong>
                <canvas id="salesChart" height="300"></canvas>
            </div>
            
            <div class="chart chart2">
                <strong class="text"><i class="uil uil-arrow-growth"></i> Sales Trend</strong>
                <canvas id="trendSalesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="dashboard-transactions-report-content">
        <div class="dashboard-report">
            <div class="transactions-report report1">
                <strong class="text"><i class="uil uil-history"></i> Recent Item Sold</strong>
                <ul class="recentItemSoldList">
                    @forelse ($recentlySold as $recentSold)
                        <li>
                            <img src="{{ asset('storage/product_image/' . $recentSold->product->product_image) }}">
                            <p>Product Name: {{ $recentSold->product->product_name }} <span>Quantity Sold: {{ $recentSold->quantity}}</span></p>
                        </li>
                        <hr>
                    @empty
                        <strong style="text-align: center">No Data</strong>
                    @endforelse
                </ul>
            </div>

            <div class="transactions-report report2">
                <strong class="text"><i class="uil uil-transaction"></i> Recent Transactions</strong>
                <div class="dashboard-table-content">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Transaction Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaction->customer->customer_name ?? 'N/A' }}</td>
                                    <td>₱ {{ $transaction->grand_total }}</td>
                                    <td><span class="status {{ $transaction->status === 'completed' ? 'completed' : '' }}">{{ $transaction->status }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('F j, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td style="text-align: center" colspan="5">No Data.</tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var trendSalesCanvas = document.getElementById('trendSalesChart');
        var salesChartCanvas = document.getElementById('salesChart');
        
        var ctxTrendSales = trendSalesCanvas.getContext('2d');
        var ctxSalesChart = salesChartCanvas.getContext('2d');

        var topTrendSalesData = JSON.parse(@json($topTrendSalesData));
        var monthlySalesData = JSON.parse(@json($monthlySalesData));
        
        if (topTrendSalesData.datasets[0].data.length === 0) {
            var trendSalesChart = new Chart(ctxTrendSales, {
                type: 'doughnut',
                data: {
                    labels: ['No Data'],
                    datasets: [{
                        data: [1],
                        backgroundColor: ['rgba(128, 128, 128, 0.8)'],
                        borderColor: ['rgba(128, 128, 128, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Top 5 Trend Sales'
                        }
                    }
                }
            });
        } else {
            // Data exists, create the chart
            var trendSalesChart = new Chart(ctxTrendSales, {
                type: 'doughnut',
                data: topTrendSalesData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Top 5 Trend Sales'
                        }
                    }
                }
            });
        }

        var salesChart = new Chart(ctxSalesChart, {
            type: 'bar',
            data: monthlySalesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Sales'
                    }
                }
            }
        });

    });
</script>
@endsection
@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-apps"></i>
            <span class="text">Dashboard</span>
        </div>

        <div class="boxes">
            <div class="box box1">
                <i>₱</i>
                <span class="text">Daily Sales</span>
                <span class="number">50,120</span>
            </div>
            <div class="box box2">
                <i class="uil uil-users-alt"></i>
                <span class="text">Total Customers</span>
                <span class="number">50</span>
            </div>
            <div class="box box3">
                <i class="uil uil-money-insert"></i>
                <span class="text">Total Profits</span>
                <span class="number">50,120</span>
            </div>
            <div class="box box4">
                <i class="uil uil-box"></i>
                <span class="text">Total Item Sold</span>
                <span class="number">120</span>
            </div>
        </div>
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
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
                    <hr>
                    <li>
                        <img src="{{ asset('storage/product_image/milo.JPG') }}">
                        <p>Item Name: Milo <span>Quantity Sold: 2</span></p>
                    </li>
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
                                <th>Transaction Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Mark</td>
                                <td>$300</td>
                                <td>June 23, 2024</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Lisa</td>
                                <td>$450</td>
                                <td>June 24, 2024</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>John</td>
                                <td>$500</td>
                                <td>June 25, 2024</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>Jane</td>
                                <td>$350</td>
                                <td>June 26, 2024</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>Peter</td>
                                <td>$600</td>
                                <td>June 27, 2024</td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>Mary</td>
                                <td>$400</td>
                                <td>June 28, 2024</td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>Paul</td>
                                <td>$250</td>
                                <td>June 29, 2024</td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td>Karen</td>
                                <td>$700</td>
                                <td>June 30, 2024</td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>Chris</td>
                                <td>$550</td>
                                <td>July 1, 2024</td>
                            </tr>
                            <tr>
                                <td>10.</td>
                                <td>Emma</td>
                                <td>$650</td>
                                <td>July 2, 2024</td>
                            </tr>
                            <tr>
                                <td>11.</td>
                                <td>Emma</td>
                                <td>$650</td>
                                <td>July 2, 2024</td>
                            </tr>
                            <tr>
                                <td>12.</td>
                                <td>Emma</td>
                                <td>$650</td>
                                <td>July 2, 2024</td>
                            </tr>
                            <tr>
                                <td>13.</td>
                                <td>Emma</td>
                                <td>$650</td>
                                <td>July 2, 2024</td>
                            </tr>
                            <tr>
                                <td>14.</td>
                                <td>Emma</td>
                                <td>$650</td>
                                <td>July 2, 2024</td>
                            </tr>
                            <tr>
                                <td>15.</td>
                                <td>Emma</td>
                                <td>$650</td>
                                <td>July 2, 2024</td>
                            </tr>
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
                        text: 'Top 10 Trend Sales'
                    }
                }
            }
        });

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
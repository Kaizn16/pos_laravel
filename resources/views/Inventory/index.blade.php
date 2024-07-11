@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a></span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="false">Products</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="false">Stock</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="Settings" aria-selected="false">Settings</a>
    </div>

    <div class="inventory-charts">
        <div class="chart box1">
            <strong>Inventory Summary</strong>
            <canvas id="productStockValue" height="280"></canvas>
        </div>

        <div class="chart box2">
            <strong>Stock Movement Report</strong>
            <canvas id="stockMovementReport" height="280"></canvas>
        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var stockValue = document.getElementById('productStockValue');
        var stockMovementReport = document.getElementById('stockMovementReport');
        
        var ctxStockValue = stockValue.getContext('2d');
        var ctxstockMovementReport = stockMovementReport.getContext('2d');

        var productStockValue = JSON.parse(@json($productStockValue));
        var productstockMovementReport = JSON.parse(@json($productStockMovement));
        
        var productStockValue1 = new Chart(ctxStockValue, {
            type: 'bar',
            data: productStockValue,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stock Value'
                    }
                }
            }
        });

        var productstockMovementReport = new Chart(ctxstockMovementReport, {
            type: 'line',
            data: productstockMovementReport,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stock Value'
                    }
                }
            }
        });


    });
</script>
@endsection
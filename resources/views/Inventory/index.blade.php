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
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="Settings" aria-selected="false">Settings</a>
    </div>

    <div class="inventory-charts">
        <div class="chart box1">
            <strong>Stock Value</strong>
            <canvas id="productStockValue" height="280"></canvas>
        </div>

        <div class="chart box2">
            <strong>Stock Level</strong>
            <canvas id="StockLevel" height="280"></canvas>
        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var stockValue = document.getElementById('productStockValue');
        var StockLevel = document.getElementById('StockLevel');
        
        var ctxStockValue = stockValue.getContext('2d');
        var ctxStockLevel = StockLevel.getContext('2d');

        var productStockValue = JSON.parse(@json($productStockValue));
        var productStockLevel = JSON.parse(@json($StockLevel));
        
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

        var productStockLevel = new Chart(ctxStockLevel, {
            type: 'bar',
            data: productStockLevel,
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stock Level'
                    }
                }
            }
        });


    });
</script>
@endsection
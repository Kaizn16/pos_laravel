@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a>>Stocks</span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="false">Products</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="true">Stocks</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
    </div>
</div>
@endsection
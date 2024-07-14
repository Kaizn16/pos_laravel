@extends('layouts.layout')
@section('content')
@php
    $view_title = session('view_title', 'Product Form');
@endphp
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory </a>><a href="{{ route('Inventory.products') }}"> Products ></a><a href="{{ route('Inventory.stocks') }}"> Stocks </a>> {{ $view_title }}</span>
        </div>
    </div>

    <div class="form-content">
        <div class="form-container box">
            <img alt="Image Preview" src="{{ asset('assets/Images/Stock.png') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            @isset($stock)
            <form id="stockForm" method="post" action="{{ route('stocks.update', $stock->stock_id) }}" enctype="multipart/form-data">
                @method('put')
                <input type="hidden" name="stock_id" id="stockId" value="{{ $stock->stock_id }}">
                @else
                <form id="stockForm" method="post" action="{{ route('stocks.store') }}" enctype="multipart/form-data">
            @endisset
                @csrf
                
                <div class="form-group">
                    <select name="product_id" id="productSelect" required>
                        <option selected disabled>Select Product</option>
                        
                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}" 
                                @if (isset($stock) && $stock->product_id == $product->product_id) selected @endif>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                
                        <option value="NewProduct">+ New Product</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <select name="supplier_id" id="supplierSelect" required>
                        <option selected disabled>Select Supplier</option>
                        
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}" 
                                @if (isset($stock) && $stock->supplier_id == $supplier->supplier_id) selected @endif>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                
                        <option value="NewSupplier">+ New Supplier</option>
                    </select>
                </div>                

                <div class="form-group">
                    <input type="number" id="stockName" name="stock_amount" value="{{ old('stock_amount', isset($stock) ? $stock->stock_amount : '') }}">
                    <label class="form-label" for="stockName">Stock Amount</label>
                </div>

                <div class="form-group">
                    <input type="date" id="expirationDate" name="expiration_date" value="{{ old('expiration_date', isset($stock) ? $stock->expiration_date : '') }}">
                    <label class="form-label" for="expirationDate">Expiration Date</label>
                </div>

                <div class="form-footer">
                    @isset($stock)
                        <button type="button" id="updateForm">Save</button>
                    @else
                        <button type="button" id="saveForm">Save</button>
                    @endisset
                    <button type="button" id="cancelForm">Cancel</button>
                </div>
            </form>
        </div>

        <div class="form-history">
            <strong class="form-history-title"><i class="uil uil-history"></i> Recent</strong>
            <ul id="stocksList">
                <!-- Unit Of Measures Data -->
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
     // Set up CSRF token for all AJAX requests
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    @if(isset($stock))
        // Disable the select visually
        $('#productSelect').addClass('disabled').attr('disabled', 'disabled');
    @endif

    document.getElementById('cancelForm').addEventListener('click', function() {
        window.location.href = "{{ route('Inventory.stocks') }}";
    });

    document.getElementById('productSelect').addEventListener('change', function() {
        if (this.value === 'NewProduct') {
            window.location.href = "{{ route('products.create') }}";
        }
    });

    document.getElementById('supplierSelect').addEventListener('change', function() {
        if (this.value === 'NewSupplier') {
            window.location.href = "{{ route('suppliers.create') }}";
        }
    });

    // Function to handle form submission for saving a new stocks
    $('#saveForm').on('click', function(e) {
        e.preventDefault();
        
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#stockForm')[0]); // Get form data
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0); // Set status based on checkbox

        $.ajax({
            url: `/Inventory/Products/Stocks/Stock-Form/Create`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.message === 'Stock saved successfully!') {
                    toastr.success("Stock saved successfully!");
                    $('#stockForm')[0].reset(); // Reset form
                    fetchStocks(); // Fetch and display updated stocks list
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.stocks') }}";
                    }, 1500);
                } else {
                    toastr.warning("Unable To Save Stock!");
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    try {
                        let errorMessage = xhr.responseJSON.message;
                        toastr.warning(errorMessage);
                    } catch (e) {
                        toastr.error('Validation Error!');
                    }
                } else {
                    toastr.error("Something went wrong!");
                }
            }
        });
    });

    // Function to handle form submission for updating an existing stocks
    $('#updateForm').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#stockForm')[0]);
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        let stockId = $('#stockId').val();

        $.ajax({
            url: `/Inventory/Products/Stocks/Stock-Form/${stockId}/Update`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                if (response.message === 'Product Stock updated successfully!') {
                    toastr.success(response.message);
                    fetchStocks();
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.stocks') }}";
                    }, 1500);
                } else {
                    toastr.warning(response.message);
                }
            },
            error: function(xhr, status, error) {
                try {
                    let errorMessage = JSON.parse(xhr.responseText).message;
                    toastr.warning(errorMessage);
                } catch (e) {
                    toastr.error("Something went wrong!");
                }
            }
        });
    });

    // Function to fetch and display unit of measures recenlty added
    function fetchStocks() {
        $.ajax({
            url: "{{ route('stocks.get', ['history' => 1]) }}", // Route for fetching unit of measures
            type: 'GET',
            success: function(stocks) {
                $('#stocksList').empty();
                stocks.forEach((stock, index) => {
                    $('#stocksList').append(`
                        <li>
                            <strong>${index + 1}. ${stock.product.product_name}</strong>
                            <strong>Supplier: ${stock.supplier.supplier_name}</strong>
                            <strong>Stock Amount: ${stock.stock_amount}</strong>
                            <strong>Date Create: ${new Date(stock.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</strong>
                            <strong>Last Update: ${stock.updated_at ? new Date(stock.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</strong>
                        </li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch unit of measures!");
            }
        });
    }
    
    fetchStocks(); // Initial fetch of unit of measure upon page load
</script>
@endsection
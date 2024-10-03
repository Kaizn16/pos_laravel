@extends('layouts.layout')
@section('content')
@php
    $view_title = session('view_title', 'Product Form');
@endphp
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory </a>><a href="{{ route('Inventory.products') }}"> Products </a>> {{ $view_title }}</span>
        </div>
    </div>
     
    <div class="form-content">

        <div class="form-container">
            @isset($product)
            <img id="productImagePreview" alt="Image Preview" src="{{ asset('storage/product_image/' . $product->product_image) }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            <form id="productForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="productId" value="{{ $product->product_id }}">    
            @method('put')
            @else
            <img id="productImagePreview" alt="Image Preview" src="{{ asset('storage/product_image/Default-Image.png') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            <form id="productForm" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="form-group">
                    <input type="file" name="product_image" id="productImage" class="productImage" placeholder="Product Image" onchange="previewImage(event)">
                    <label class="form-label" for="productImage">Product Image</label>
                </div>
    
                <div class="form-group">
                    <input type="text" name="product_code" id="productCode" placeholder="Product Code" value="{{ old('product_code', isset($product) ? $product->product_code : '') }}">
                    <label class="form-label" for="productCode">Product Code</label>
                </div>
    
                <div class="form-group">
                    <input type="text" name="product_name" id="productName" placeholder="Product Name" value="{{ old('product_name', isset($product) ? $product->product_name : '') }}">
                    <label class="form-label" for="productName">Product Name</label>
                </div>
    
                <div class="form-group">
                    <select name="category_id" id="categorySelect" required>
                        <option selected disabled>Select Category</option>
                        
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}" 
                                @if (isset($product) && $product->category_id == $category->category_id) selected @endif>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                
                        <option value="NewCategory">+ New Category</option>
                    </select>
                </div>                
    
                <div class="form-group">
                    <input type="number" name="purchase_price" min="1" id="purchasePrice" placeholder="Purchase Price" value="{{ old('purchase_price', isset($product) ? $product->purchase_price : '') }}" onkeypress="return isNumberKey(event)">
                    <label class="form-label" for="purchasePrice">Purchase Price</label>
                </div>
    
                <div class="form-group">
                    <input type="number" name="selling_price" min="1" id="sellingPrice" placeholder="Selling Price" value="{{ old('selling_price', isset($product) ? $product->selling_price : '') }}" onkeypress="return isNumberKey(event)">
                    <label class="form-label" for="sellingPrice">Selling Price</label>
                </div>
    
                <div class="form-group">
                    <select name="uom_id" id="uomSelect" required>
                        <option selected disabled>Select Unit Of Measure</option>
                        
                        @foreach ($uoms as $uom)
                            <option value="{{ $uom->uom_id }}" 
                                @if (isset($product) && $product->uom_id == $uom->uom_id) selected @endif>
                                {{ $uom->uom_name }}
                            </option>
                        @endforeach
                
                        <option value="NewUOM">+ New Unit Of Measure</option>
                    </select>
                </div>
    
                <div class="form-group">
                    <strong>Status</strong>
                    <label class="switch" for="checkbox">
                        <input type="checkbox" id="checkbox" name="status" {{ isset($product) && $product->status ? 'checked' : '' }} />
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="form-footer">
                    @isset($product)
                        <button type="button" id="updateProduct">Save</button>
                        @else
                        <button type="button" id="saveProduct">Save</button>
                    @endisset
                    <button type="button" id="cancelProduct">Cancel</button>
                </div>
            </form>
        </div>

        <div class="form-history">
            <strong class="form-history-title"><i class="uil uil-history"></i> Recently Added</strong>
            <ul id="productList">
                <!-- RECENTLY ADDED PRODUCT -->
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

    document.getElementById('categorySelect').addEventListener('change', function() {
        if (this.value === 'NewCategory') {
            window.location.href = "{{ route('category.create') }}";
        }
    });

    document.getElementById('uomSelect').addEventListener('change', function() {
        if (this.value === 'NewUOM') {
            window.location.href = "{{ route('uom.create') }}";
        }
    });

    document.getElementById('cancelProduct').addEventListener('click', function() {
        window.location.href = "{{ route('Inventory.products') }}";
    });

    function previewImage(event) {
        const reader = new FileReader();
        const imgElement = document.getElementById('productImagePreview');

        reader.onload = function() {
            imgElement.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    const imagePreview = document.getElementById('productImagePreview');
    const defaultImageSrc = imagePreview.src;

    $('#saveProduct').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }
        
        let formData = new FormData($('#productForm')[0]);

        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        
        $.ajax({
            url: "{{ route('products.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.message === 'Product saved successfully!') {
                    $('#productForm')[0].reset();
                    imagePreview.src = defaultImageSrc;
                    toastr.success("Product Saved Successfully!");
                    fetchProducts();
                } else {
                    toastr.warning("Unable To Saved Product!");
                }
            },
            error: function(response) {
                toastr.error("Something went wrong!");
            }
        });
    });


    // Function to handle form submission for updating selected product
    $('#updateProduct').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#productForm')[0]);
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        let product_id = $('#productId').val();

        $.ajax({
            url: `/Inventory/Products/Product-Form/${product_id}/Update`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                if (response.message === 'Product updated successfully!') {
                    toastr.success(response.message);
                    fetchProducts();
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.products') }}";
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

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // Allow: backspace, delete, left arrow, right arrow, tab, and decimal point
        if (charCode === 8 || charCode === 46 || charCode === 37 || charCode === 39 || charCode === 9) {
            return true;
        }
        // Ensure that it is a number and stop the keypress if it is not
        if (charCode < 48 || charCode > 57) {
            return false;
        }
        return true;
    }

    // Function to fetch and display products recenlty added
    function fetchProducts() {
        $.ajax({
            url: "{{ route('products.get', ['history' => 1]) }}", // Route for fetching products
            type: 'GET',
            success: function(response) {
                let products = response.data;

                $('#productList').empty();
                products.forEach((product, index) => {
                    $('#productList').append(`
                        <li>
                            <strong>${index + 1}. ${product.product_name}</strong>
                            <strong>Category: ${product.category.category_name}</strong>
                            <strong>Purchase Price: ₱${product.purchase_price}</strong>
                            <strong>Selling Price: ₱${product.selling_price} / ${product.uom.uom_name}</strong>
                            <strong>Date: ${new Date(product.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</strong>
                        </li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch products!");
            }
        });
    }
    
    fetchProducts(); // Initial fetch of products upon page load
</script>
@endsection
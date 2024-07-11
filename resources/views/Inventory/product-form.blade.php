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
            <img id="productImagePreview" alt="Image Preview" src="{{ asset('storage/product_image/Default-Image.png') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            <form id="productForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="product_image" id="productImage" class="productImage" placeholder="Product Image" onchange="previewImage(event)">
                    <label class="form-label" for="productImage">Product Image</label>
                </div>
    
                <div class="form-group">
                    <input type="text" name="product_code" id="productCode" placeholder="Product Code" required>
                    <label class="form-label" for="productCode">Product Code</label>
                </div>
    
                <div class="form-group">
                    <input type="text" name="product_name" id="productName" placeholder="Product Name" required>
                    <label class="form-label" for="productName">Product Name</label>
                </div>
    
                <div class="form-group">
                    <select name="category_id" id="categorySelect" required>
                        <option selected disabled>Select Category</option>
                        <option value="1">Beverages</option>
                        <option value="NewCategory">+ New Category</option>
                    </select>
                </div>
    
                <div class="form-group">
                    <input type="number" name="purchase_price" min="1" id="purchasePrice" placeholder="Purchase Price" required>
                    <label class="form-label" for="purchasePrice">Purchase Price</label>
                </div>
    
                <div class="form-group">
                    <input type="number" name="selling_price" min="1" id="sellingPrice" placeholder="Selling Price" required>
                    <label class="form-label" for="sellingPrice">Selling Price</label>
                </div>
    
                <div class="form-group">
                    <input type="text" name="uom" id="uomSell" placeholder="Unit of Measure" required> 
                    <label class="form-label" for="uomSell">Unit of Measure</label>
                </div>
    
                <div class="form-group">
                    <select name="supplier_id" id="supplierSelect" required>
                        <option selected disabled>Select Supplier</option>
                        <option value="1">Nestlé Philippines INC</option>
                        <option value="NewSupplier">+ New Supplier</option>
                    </select>
                </div>
    
                <div class="form-group">
                    <input type="date" name="expiration_date" id="expirationDate" placeholder="Expiration Date" required>
                    <label class="form-label" for="expirationDate">Expiration Date</label>
                </div>
    
                <div class="form-group">
                    <strong>Status</strong>
                    <label class="switch" for="checkbox">
                        <input type="checkbox" id="checkbox" name="status" />
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="button" id="cancelProduct">Cancel</button>
                    <button type="button" id="saveProduct">Save</button>
                </div>
            </form>
        </div>

        <div class="form-history">
            <strong class="form-history-title"><i class="uil uil-history"></i> Recently Added</strong>
            <ul>
                <li>
                    <strong>1.</strong>
                    <strong>Product Name:</strong>
                    <strong>Category: Beverages</strong>
                </li>
                <li>
                    <strong>2.</strong>
                    <strong>Product Name:</strong>
                    <strong>Category: Snacks</strong>
                </li>
                <li>
                    <strong>3.</strong>
                    <strong>Product Name:</strong>
                    <strong>Category: Alcohol</strong>
                </li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('categorySelect').addEventListener('change', function() {
        if (this.value === 'NewCategory') {
            window.location.href = "{{ route('Inventory.products') }}";
        }
    });

    document.getElementById('supplierSelect').addEventListener('change', function() {
        if (this.value === 'NewSupplier') {
            window.location.href = "{{ route('Inventory.products') }}";
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
                    toastr.success("Product Saved Successfully!");
                } else {
                    toastr.warning("Unable To Saved Product!");
                }
            },
            error: function(response) {
                toastr.error("Something went wrong!");
            }
        });
    });
</script>
@endsection
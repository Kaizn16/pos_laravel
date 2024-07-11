@extends('layouts.layout')
@section('content')
@php
    $view_title = session('view_title', 'Product Form');
@endphp
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a>><a href="{{ route('Inventory.suppliers') }}">Suppliers</a>>{{ $view_title }}</span>
        </div>
    </div>

    <div class="form-content">
        <div class="form-container">
            <img alt="Image Preview" src="{{ asset('assets/Images/Supplier.png') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            <form id="supplierForm" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <input type="text" name="supplier_name" id="supplierName" placeholder="Supplier Name" required>
                    <label class="form-label" for="supplierName">Supplier Name</label>
                </div>

                <div class="form-group">
                    <input type="text" name="address" id="address" placeholder="Address" required>
                    <label class="form-label" for="address">Address</label>
                </div>

                <div class="form-group">
                    <input type="text" name="email" id="email" placeholder="Email" required>
                    <label class="form-label" for="email">Email</label>
                </div>

                <div class="form-group">
                    <input type="text" name="contact_number" id="contactNumber" placeholder="Contact Number" required>
                    <label class="form-label" for="contactNumber">Contact Number</label>
                </div>

                <div class="form-group">
                    <strong>Status</strong>
                    <label class="switch" for="checkbox">
                        <input type="checkbox" id="checkbox" name="status" />
                        <div class="slider round"></div>
                    </label>
                </div>

                <div class="form-footer">
                    <button type="button" id="cancelSupplier">Cancel</button>
                    <button type="button" id="saveSupplier">Save</button>
                </div>
            </form>
        </div>
        <div class="form-history">
            <strong class="form-history-title"><i class="uil uil-history"></i> Recently Added</strong>
            <ul>
                <li>
                    <strong>1.</strong>
                    <strong>Supplier Name:</strong>
                    <strong>Address:</strong>
                    <strong>Email:</strong>
                    <strong>Contact No.:</strong>
                </li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('cancelSupplier').addEventListener('click', function() {
        window.location.href = "{{ route('Inventory.suppliers') }}";
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#saveSupplier').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }
        
        let formData = new FormData($('#supplierForm')[0]);

        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        
        $.ajax({
            url: "{{ route('suppliers.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.message === 'Supplier saved successfully!') {
                    toastr.success("Supplier Saved Successfully!");
                    $('#supplierForm')[0].reset();
                } else {
                    toastr.warning("Unable To Saved Supplier!");
                }
            },
            error: function(response) {
                toastr.error("Something went wrong!");
            }
        });
    });
</script>
@endsection
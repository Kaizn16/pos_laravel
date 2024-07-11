@extends('layouts.layout')
@section('content')
@php
    $view_title = session('view_title', 'Product Form');
@endphp
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory </a>><a href="{{ route('Inventory.products') }}"> Products ><a href="{{ route('Inventory.categories') }}"> Categories </a>> {{ $view_title }}</span>
        </div>
    </div>

    <div class="form-content">

        <div class="form-container box">
            <img alt="Image Preview" src="{{ asset('assets/Images/Category.jpg') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            <form id="categoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <input type="text" name="category_name" id="categoryName" placeholder="Category Name" required>
                    <label class="form-label" for="categoryName">Category Name</label>
                </div>
    
                <div class="form-group">
                    <textarea name="description" id="description" placeholder="Description (OPTIONAL)"></textarea>
                    <label class="form-label" for="description">Description(OPTIONAL)</label>
                </div>
    
                <div class="form-group">
                    <strong>Status</strong>
                    <label class="switch" for="checkbox">
                        <input type="checkbox" id="checkbox" name="status" />
                        <div class="slider round"></div>
                    </label>
                </div>
    
                <div class="form-footer">
                    <button type="button" id="cancelForm">Cancel</button>
                    <button type="button" id="saveForm">Save</button>
                </div>
            </form>
        </div>

        <div class="form-history">
            <strong class="form-history-title"><i class="uil uil-history"></i> Recently Added</strong>
            <ul id="categoryList">
                <!-- Categories Data -->
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('cancelForm').addEventListener('click', function() {
        window.location.href = "{{ route('Inventory.categories') }}";
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#saveForm').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }
        
        let formData = new FormData($('#categoryForm')[0]);

        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        
        $.ajax({
            url: "{{ route('category.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.message === 'Category saved successfully!') {
                    toastr.success("Category Saved Successfully!");
                    $('#categoryForm')[0].reset();
                    fetchCategories();

                } else {
                    toastr.warning("Unable To Save Category!");
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

    function fetchCategories() {
        $.ajax({
            url: "{{ route('category.get', ['history' => 1]) }}",
            type: 'GET',
            success: function(categories) {
                $('#categoryList').empty();
                categories.forEach((category, index) => {
                    $('#categoryList').append(`
                        <li>
                            <strong>${index + 1}. ${category.category_name}</strong>
                            <strong>Date: ${new Date(category.created_at).toLocaleString()}</strong>
                            <p>
                                ${category.description ? `Description: ${category.description}` : 'Description: N/A'}
                            </p>
                        </li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch categories!");
            }
        });
    }
    
    fetchCategories();
</script>
@endsection
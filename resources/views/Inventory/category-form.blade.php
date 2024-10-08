@extends('layouts.layout')
@section('content')
@php
    $view_title = session('view_title', 'Product Form');
@endphp
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory </a>><a href="{{ route('Inventory.products') }}"> Products ></a><a href="{{ route('Inventory.categories') }}"> Categories </a>> {{ $view_title }}</span>
        </div>
    </div>

    <div class="form-content">
        <div class="form-container box">
            <img alt="Image Preview" src="{{ asset('assets/Images/Category.jpg') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            @isset($category)
            <form id="categoryForm" method="post" action="{{ route('category.update', $category->category_id) }}" enctype="multipart/form-data">
                @method('put')
                <input type="hidden" name="category_id" id="categoryId" value="{{ $category->category_id }}">
                @else
                <form id="categoryForm" method="post" action="{{ route('category.store') }}" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="form-group">
                    <input type="text" name="category_name" value="{{ old('category_name', isset($category) ? $category->category_name : '') }}">
                    <label class="form-label" for="categoryName">Category Name</label>
                </div>
                <div class="form-group">
                    <textarea name="description" id="description" placeholder="Description (OPTIONAL)">{{ $category->description ?? '' }}</textarea>
                    <label class="form-label" for="description">Description(OPTIONAL)</label>
                </div>
                <div class="form-group">
                    <strong>Status</strong>
                    <label class="switch" for="checkbox">
                        <input type="checkbox" id="checkbox" name="status" {{ isset($category) && $category->status ? 'checked' : '' }} />
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="form-footer">
                    @isset($category)
                        <button type="button" id="updateForm">Save</button>
                    @else
                        <button type="button" id="saveForm">Save</button>
                    @endisset
                    <button type="button" id="cancelForm">Cancel</button>
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

    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Function to handle form submission for saving a new category
    $('#saveForm').on('click', function(e) {
        e.preventDefault();
        
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#categoryForm')[0]); // Get form data
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0); // Set status based on checkbox

        $.ajax({
            url: `/Inventory/Products/Categories/Category-Form/Create`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.message === 'Category saved successfully!') {
                    toastr.success("Category Saved Successfully!");
                    $('#categoryForm')[0].reset(); // Reset form
                    fetchCategories(); // Fetch and display updated category list
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.categories') }}";
                    }, 1500);
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

    // Function to handle form submission for updating an existing category
    $('#updateForm').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#categoryForm')[0]);
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        let categoryId = $('#categoryId').val();

        $.ajax({
            url: `/Inventory/Products/Categories/Category-Form/${categoryId}/Update`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                if (response.message === 'Category updated successfully!') {
                    toastr.success(response.message);
                    fetchCategories();
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.categories') }}";
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

    // Function to fetch and display categories recenlty added
    function fetchCategories() {
        $.ajax({
            url: "{{ route('category.get', ['history' => 1]) }}", // Route for fetching categories
            type: 'GET',
            success: function(categories) {
                $('#categoryList').empty();
                categories.forEach((category, index) => {
                    $('#categoryList').append(`
                        <li>
                            <strong>${index + 1}. ${category.category_name}</strong>
                            <strong>Date: ${new Date(category.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</strong>
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
    
    fetchCategories(); // Initial fetch of categories upon page load
</script>
@endsection
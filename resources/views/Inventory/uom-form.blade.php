@extends('layouts.layout')
@section('content')
@php
    $view_title = session('view_title', 'Product Form');
@endphp
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory </a>><a href="{{ route('Inventory.products') }}"> Products ></a><a href="{{ route('Inventory.uom') }}"> Unit Of Measure </a>> {{ $view_title }}</span>
        </div>
    </div>

    <div class="form-content">
        <div class="form-container box">
            <img alt="Image Preview" src="{{ asset('assets/Images/UOM.png') }}" style="width: 64px; height: 64px; border-radius: 50px; background-color: #fff;">
            @isset($uom)
            <form id="uomForm" method="post" action="{{ route('uom.update', $uom->uom_id) }}" enctype="multipart/form-data">
                @method('put')
                <input type="hidden" name="uom_id" id="uomId" value="{{ $uom->uom_id }}">
                @else
                <form id="uomForm" method="post" action="{{ route('uom.store') }}" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="form-group">
                    <input type="text" name="uom_name" value="{{ old('uom_name', isset($uom) ? $uom->uom_name : '') }}">
                    <label class="form-label" for="uomName">Unit Of Measure</label>
                </div>
                <div class="form-group">
                    <textarea name="description" id="description" placeholder="Description (OPTIONAL)">{{ $uom->description ?? '' }}</textarea>
                    <label class="form-label" for="description">Description(OPTIONAL)</label>
                </div>
                <div class="form-group">
                    <strong>Status</strong>
                    <label class="switch" for="checkbox">
                        <input type="checkbox" id="checkbox" name="status" {{ isset($uom) && $uom->status ? 'checked' : '' }} />
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="form-footer">
                    @isset($uom)
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
            <ul id="uomList">
                <!-- Unit Of Measures Data -->
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('cancelForm').addEventListener('click', function() {
        window.location.href = "{{ route('Inventory.uom') }}";
    });

    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Function to handle form submission for saving a new uom
    $('#saveForm').on('click', function(e) {
        e.preventDefault();
        
        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#uomForm')[0]); // Get form data
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0); // Set status based on checkbox

        $.ajax({
            url: `/Inventory/Products/UOM/Uom-Form/Create`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.message === 'Unit Of Measure saved successfully!') {
                    toastr.success("Unit Of Measure Saved Successfully!");
                    $('#uomForm')[0].reset(); // Reset form
                    fetchUnitOfMeasures(); // Fetch and display updated uom list
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.uom') }}";
                    }, 1500);
                } else {
                    toastr.warning("Unable To Save Unit Of Measure!");
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

    // Function to handle form submission for updating an existing uom
    $('#updateForm').on('click', function(e) {
        e.preventDefault();

        toastr.options = {
            "progressBar": true,
            "closeButton": true,
        }

        let formData = new FormData($('#uomForm')[0]);
        formData.set('status', $('#checkbox').is(':checked') ? 1 : 0);
        let uomId = $('#uomId').val();

        $.ajax({
            url: `/Inventory/Products/UOM/Uom-Form/${uomId}/Update`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                if (response.message === 'Unit Of Measure updated successfully!') {
                    toastr.success(response.message);
                    fetchUnitOfMeasures();
                    setTimeout(() => {
                        window.location.href = "{{ route('Inventory.uom') }}";
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
    function fetchUnitOfMeasures() {
        $.ajax({
            url: "{{ route('uom.get', ['history' => 1]) }}", // Route for fetching unit of measures
            type: 'GET',
            success: function(uoms) {
                $('#uomList').empty();
                uoms.forEach((uom, index) => {
                    $('#uomList').append(`
                        <li>
                            <strong>${index + 1}. ${uom.uom_name}</strong>
                            <strong>Date: ${new Date(uom.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</strong>
                            <p>
                                ${uom.description ? `Description: ${uom.description}` : 'Description: N/A'}
                            </p>
                        </li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch unit of measures!");
            }
        });
    }
    
    fetchUnitOfMeasures(); // Initial fetch of unit of measure upon page load
</script>
@endsection
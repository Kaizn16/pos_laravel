@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a> > <a href="{{ route('Inventory.products') }}">Products</a> > Unit of Measure</span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="false">Products</a>
        <a href="{{ route('Inventory.categories') }}" role="tab" aria-controls="category" aria-selected="false">Categories</a>
        <a href="{{ route('Inventory.uom') }}" role="tab" aria-controls="uom" aria-selected="true">Unit of Measure</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="false">Stocks</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
    </div>
    
    <div class="table-container">

        <header>

            <div class="tabbleAddButton">
                <button type="button" onclick="window.location.href='{{ route('uom.create') }}'">New Unit of Measure</button>
            </div>

            <div class="header-table">
                <div class="filterEntries">
                    <div class="entries">
                        Show
                        <select name="table_size" id="table_size">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> 
                        entries
                    </div>
    
                    <div class="filter">
                        <label for="search">Search:</label>
                        <input type="search" name="search_uom" id="search" placeholder="Search U.O.M">
                    </div> 
                </div>
    
                <div class="printTable">
                    <i class="uil uil-print"></i>
                    <select name="" id="printSelection">
                        <option value="" disabled selected>Print</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
            </div>
        </header>

        <table>

            <thead>
                <tr class="heading">
                    <th>#</th>
                    <th>Unit Of Measure</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="tableData">
                <!-- Table Data Will Display Here -->
            </tbody>

        </table>


        <footer>
            <span class="showEntries"><!-- Show Entries Data will display here --></span>
            <div class="pagination">
                <!-- Pagination Will Display here -->
            </div>
        </footer>
    </div>

</div>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function fetchUnitOfMeasures(page = 1, pageSize = 10) {
        $.ajax({
            url: "{{ route('uom.get') }}",
            type: 'GET',
            data: {
                page: page,
                pageSize: pageSize
            },
            success: function(response) {
                let uoms = response.data;
                let totalItems = response.total;
                let currentPage = response.current_page;
                let lastPage = response.last_page;

                // Update table body
                let uomListHtml = '';
                uoms.forEach((uom, index) => {
                    uomListHtml += `
                        <tr>
                            <td>${(currentPage - 1) * pageSize + index + 1}</td>
                            <td>${uom.uom_name}</td>
                            <td>${uom.description || 'N/A'}</td>
                            <td>
                                <span class="${uom.status == 1 ? 'status-enabled' : 'status-disabled'}">
                                    ${uom.status == 1 ? 'ENABLED' : 'DISABLED'}
                                </span>
                            </td>
                            <td>
                                <button onclick="EditForm(${uom.uom_id})"><i class="uil uil-edit" title="Edit"></i></button>
                                <button onclick="DeleteForm(${uom.uom_id}, '${escapeJsString(uom.uom_name)}')"><i class="uil uil-trash-alt" title="Delete"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('.tableData').html(uomListHtml);

                // Update pagination
                let paginationHtml = '';
                if (currentPage > 1) {
                    paginationHtml += `<button style="pointer-events: auto"  onclick="fetchUnitOfMeasures(${currentPage - 1}, ${pageSize})">Prev</button>`;
                }
                for (let i = 1; i <= lastPage; i++) {
                    paginationHtml += `<button onclick="fetchUnitOfMeasures(${i}, ${pageSize})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
                }
                if (currentPage < lastPage) {
                    paginationHtml += `<button style="pointer-events: auto" onclick="fetchUnitOfMeasures(${currentPage + 1}, ${pageSize})">Next</button>`;
                }
                $('.pagination').html(paginationHtml);

                // Update showing entries info
                $('.showEntries').text(`Showing ${(currentPage - 1) * pageSize + 1} to ${(currentPage - 1) * pageSize + uoms.length} of ${totalItems} entries`);
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch categories!");
            }
        });
    }

    function EditForm(uom_id) {
        window.location.href = '/Inventory/Products/UOM/Uom-Form/' + uom_id + '/Edit';
    }

    function DeleteForm(uom_id, uom_name) {
        Swal.fire({
            title: `Are you sure you want to delete ${uom_name} U.O.M?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/Inventory/Products/UOM/Uom-Form/' + uom_id + '/Delete',
                    type: 'POST',
                    headers: {
                        'X-HTTP-Method-Override': 'PATCH'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your U.O.M has been deleted.',
                            'success'
                        );
                        fetchUnitOfMeasures();
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Failed!',
                            'There was a problem deleting your U.O.M.',
                            'error'
                        );
                    }
                });
            }
        })
    }

    function escapeJsString(str) {
        return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    // Initial fetch
    fetchUnitOfMeasures();

    // Handle table size change
    $('#table_size').on('change', function() {
        fetchUnitOfMeasures(1, $(this).val());
    });
</script>
@endsection
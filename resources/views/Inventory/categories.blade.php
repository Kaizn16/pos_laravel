@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a> > <a href="{{ route('Inventory.products') }}">Products</a> > Categories</span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="false">Products</a>
        <a href="{{ route('Inventory.categories') }}" role="tab" aria-controls="category" aria-selected="true">Categories</a>
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="uom" aria-selected="false">Unit of Measure</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="false">Stocks</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
    </div>

    <div class="table-container">

        <header>

            <div class="tabbleAddButton">
                <button type="button" onclick="window.location.href='{{ route('category.create') }}'">New Category</button>
            </div>

            <div class="header-table">
                <div class="filterEntries">
                    <div class="entries">
                        Show
                        <select name="" id="table_size">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> 
                        entries
                    </div>
    
                    <div class="filter">
                        <label for="search">Search:</label>
                        <input type="search" name="" id="search" placeholder="Search Category">
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
                    <th>Categories</th>
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
    function fetchCategories(page = 1, pageSize = 10) {
        $.ajax({
            url: "{{ route('category.get') }}",
            type: 'GET',
            data: {
                page: page,
                pageSize: pageSize
            },
            success: function(response) {
                let categories = response.data;
                let totalItems = response.total;
                let currentPage = response.current_page;
                let lastPage = response.last_page;

                // Update table body
                let categoryListHtml = '';
                categories.forEach((category, index) => {
                    categoryListHtml += `
                        <tr>
                            <td>${(currentPage - 1) * pageSize + index + 1}</td>
                            <td>${category.category_name}</td>
                            <td>${category.description || 'N/A'}</td>
                            <td>
                                <span class="${category.status == 1 ? 'status-enabled' : 'status-disabled'}">
                                    ${category.status == 1 ? 'ENABLED' : 'DISABLED'}
                                </span>
                            </td>
                            <td>
                                <button onclick="EditForm(${category.category_id})"><i class="uil uil-edit" title="Edit"></i></button>
                                <button onclick="DeleteForm(${category.category_id})"><i class="uil uil-trash-alt" title="Delete"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('.tableData').html(categoryListHtml);

                // Update pagination
                let paginationHtml = '';
                if (currentPage > 1) {
                    paginationHtml += `<button style="pointer-events: auto"  onclick="fetchCategories(${currentPage - 1}, ${pageSize})">Prev</button>`;
                }
                for (let i = 1; i <= lastPage; i++) {
                    paginationHtml += `<button onclick="fetchCategories(${i}, ${pageSize})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
                }
                if (currentPage < lastPage) {
                    paginationHtml += `<button style="pointer-events: auto" onclick="fetchCategories(${currentPage + 1}, ${pageSize})">Next</button>`;
                }
                $('.pagination').html(paginationHtml);

                // Update showing entries info
                $('.showEntries').text(`Showing ${(currentPage - 1) * pageSize + 1} to ${(currentPage - 1) * pageSize + categories.length} of ${totalItems} entries`);
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch categories!");
            }
        });
    }


    function EditForm(category_id) {
        toastr.success(category_id);
    }

    function DeleteForm(category_id) {
        toastr.error(category_id);
    }

    // Initial fetch
    fetchCategories();

    // Handle table size change
    $('#table_size').on('change', function() {
        fetchCategories(1, $(this).val());
    });
</script>
@endsection
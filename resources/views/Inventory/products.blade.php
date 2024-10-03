@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a> > Products</span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="true">Products</a>
        <a href="{{ route('Inventory.categories') }}" role="tab" aria-controls="category" aria-selected="false">Categories</a>
        <a href="{{ route('Inventory.uom') }}" role="tab" aria-controls="uom" aria-selected="false">Unit of Measure</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="false">Stocks</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
    </div>

    <div class="table-container">

        <header>

            <div class="tabbleAddButton">
                <button type="button" onclick="window.location.href='{{ route('products.create') }}'">New Product</button>
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
                        <input type="search" name="" id="search" placeholder="Search Product">
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
                    <th>Picture</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="tableData">
                       
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

    function fetchProducts(page = 1, pageSize = 10) {
        $.ajax({
            url: "{{ route('products.get') }}",
            type: 'GET',
            data: {
                page: page,
                pageSize: pageSize
            },
            success: function(response) {
                let products = response.data.data;
                let totalItems = response.data.total;
                let currentPage = response.data.current_page;
                let lastPage = response.data.last_page;
                
                // Update table body
                let productListHtml = '';
                products.forEach((product, index) => {
                    productListHtml += `
                        <tr>
                            <td>${(currentPage - 1) * pageSize + index + 1}</td>
                            <td><img src="{{ asset('storage/product_image/') }}/${product.product_image}" style="max-width: 54px; max-height: 54px" alt="Product Image"></td>
                            <td>${product.product_code}</td>
                            <td>${product.product_name}</td>
                            <td>${product.category.category_name}</td>
                            <td>₱ ${product.purchase_price} | ${product.uom.uom_name}</td>
                            <td>₱ ${product.selling_price} | ${product.uom.uom_name}</td>   
                            <td>
                                <span class="${product.status == 1 ? 'status-enabled' : 'status-disabled'}">
                                    ${product.status == 1 ? 'ENABLED' : 'DISABLED'}
                                </span>
                            </td>
                            <td>
                                <button onclick="EditForm(${product.product_id})"><i class="uil uil-edit" title="Edit"></i></button>
                                <button onclick="DeleteForm(${product.product_id}, '${escapeJsString(product.product_name)}')"><i class="uil uil-trash-alt" title="Delete"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('.tableData').html(productListHtml);

                // Update pagination
                let paginationHtml = '';
                if (currentPage > 1) {
                    paginationHtml += `<button style="pointer-events: auto"  onclick="fetchProducts(${currentPage - 1}, ${pageSize})">Prev</button>`;
                }
                for (let i = 1; i <= lastPage; i++) {
                    paginationHtml += `<button onclick="fetchProducts(${i}, ${pageSize})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
                }
                if (currentPage < lastPage) {
                    paginationHtml += `<button style="pointer-events: auto" onclick="fetchProducts(${currentPage + 1}, ${pageSize})">Next</button>`;
                }
                $('.pagination').html(paginationHtml);

                // Update showing entries info
                $('.showEntries').text(`Showing ${(currentPage - 1) * pageSize + 1} to ${(currentPage - 1) * pageSize + products.length} of ${totalItems} entries`);
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch products!");
            }
        });
    }

    function EditForm(product_id) {
        window.location.href = '/Inventory/Products/Product-Form/' + product_id + '/Edit';
    }

    function DeleteForm(product_id, product_name) {
        Swal.fire({
            title: `Are you sure you want to delete ${product_name} product?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/Inventory/Products/Product-Form/' + product_id + '/Delete',
                    type: 'POST',
                    headers: {
                        'X-HTTP-Method-Override': 'PATCH'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your product has been deleted.',
                            'success'
                        );
                        fetchProducts();
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Failed!',
                            'There was a problem deleting your product.',
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

    fetchProducts(); // Initialized when page load
</script>
@endsection
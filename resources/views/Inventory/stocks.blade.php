@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a>>Stocks</span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="false">Products</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="true">Stocks</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="false">Suppliers</a>
    </div>

    <div class="table-container">

        <header>

            <div class="tabbleAddButton">
                <button type="button" onclick="window.location.href='{{ route('stocks.create') }}'">New Stock</button>
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
                        <input type="search" name="search_product" id="search" placeholder="Search Product">
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
                    <th>Product Name</th>
                    <th>Supplier</th>
                    <th>Stock Amount</th>
                    <th>Expiration Date</th>
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

    function fetchStocks(page = 1, pageSize = 10) {
        $.ajax({
            url: "{{ route('stocks.get') }}",
            type: 'GET',
            data: {
                page: page,
                pageSize: pageSize
            },
            success: function(response) {
                let stocks = response.data;
                let totalItems = response.total;
                let currentPage = response.current_page;
                let lastPage = response.last_page;

                // Update table body
                let stockListHtml = '';
                stocks.forEach((stock, index) => {
                    const stockPercentage = (stock.stock_amount / stock.total_stock) * 100;
                    let backgroundColor;

                    if (stockPercentage > 50) {
                        backgroundColor = 'rgb(39, 180, 39)'; // green
                    } else if (stockPercentage > 25) {
                        backgroundColor = 'rgb(255, 165, 0)'; // orange
                    } else {
                        backgroundColor = 'rgb(255, 0, 0)'; // red
                    }

                    let displayStockText = stock.stock_amount <= 1 ? 'Stock' : 'Stocks';

                    stockListHtml += `
                        <tr>
                            <td>${(currentPage - 1) * pageSize + index + 1}</td>
                            <td>${stock.product.product_name}</td>
                            <td>${stock.supplier.supplier_name}</td>
                            <td>
                                ${displayStockText}: ${stock.stock_amount}
                                <div class="stock-bar-container">
                                    <div class="stock-bar" style="background-color: ${backgroundColor}; width: ${stockPercentage}%">
                                    ${stockPercentage}%
                                    </div>
                                </div>
                            </td>
                            <td>${new Date(stock.expiration_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
                            <td>
                                <button onclick="EditForm(${stock.stock_id})"><i class="uil uil-edit" title="Edit"></i></button>
                            </td>
                        </tr>
                    `;
                });
                $('.tableData').html(stockListHtml);

                // Update pagination
                let paginationHtml = '';
                if (currentPage > 1) {
                    paginationHtml += `<button style="pointer-events: auto"  onclick="fetchStocks(${currentPage - 1}, ${pageSize})">Prev</button>`;
                }
                for (let i = 1; i <= lastPage; i++) {
                    paginationHtml += `<button onclick="fetchStocks(${i}, ${pageSize})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
                }
                if (currentPage < lastPage) {
                    paginationHtml += `<button style="pointer-events: auto" onclick="fetchStocks(${currentPage + 1}, ${pageSize})">Next</button>`;
                }
                $('.pagination').html(paginationHtml);

                // Update showing entries info
                $('.showEntries').text(`Showing ${(currentPage - 1) * pageSize + 1} to ${(currentPage - 1) * pageSize + stocks.length} of ${totalItems} entries`);
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch stocks!");
            }
        });
    }

    function EditForm(stock_id) {
        window.location.href = '/Inventory/Products/Stocks/Stock-Form/' + stock_id + '/Edit';
    }

    function escapeJsString(str) {
        return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    // Initial fetch
    fetchStocks();

    // Handle table size change
    $('#table_size').on('change', function() {
        fetchStocks(1, $(this).val());
    });
</script>
@endsection
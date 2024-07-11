@extends('layouts.layout')

@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-box"></i>
            <span class="text"><a href="/Inventory">Inventory</a> > Suppliers</span>
        </div>
    </div>

    <div class="inventory-tab-links">
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="product" aria-selected="false">Products</a>
        <a href="{{ route('Inventory.stocks') }}" role="tab" aria-controls="stock" aria-selected="false">Stocks</a>
        <a href="{{ route('Inventory.suppliers') }}" role="tab" aria-controls="supplier" aria-selected="true">Suppliers</a>
    </div>

    <div class="table-container">
        <header>

            <div class="tabbleAddButton">
                <button type="button" onclick="window.location.href='{{ route('suppliers.create') }}'">New Supplier</button>
            </div>

            <div class="header-table">
                <!-- Filter and search options -->
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
                        <input type="search" name="search" id="search" placeholder="Search Supplier">
                    </div> 
                </div>

                <!-- Print and add new supplier buttons -->
                <div class="printTable">
                    <i class="uil uil-print"></i>
                    <select name="printSelection" id="printSelection">
                        <option value="" disabled selected>Print</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
            </div>

        </header>
        <!-- Table -->
        <table>
            <thead>
                <tr class="heading">
                    <th>#</th>
                    <th>Supplier Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Action</th>
                </tr>
            </thead>

            <!-- Table body with supplier data -->
            <tbody class="tableData">
                @foreach($suppliers as $index => $supplier)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $supplier->supplier_name }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->contact_number }}</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Table footer with pagination -->
        <footer>
            <span class="showEntries">Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} entries</span>
            <div class="pagination">
                <button>Prev</button>
                <button class="active">1</button>
                <button>Next</button>
            </div>
        </footer>
    </div>    

</div>
<script>
    // AJAX search functionality
    $('#search').on('keyup', function() {
        var query = $(this).val();
        var tableSize = $('#table_size').val();

        $.ajax({
            url: "{{ route('suppliers.search') }}",
            type: "GET",
            data: {
                query: query,
                table_size: tableSize
            },
            success: function(data) {
                updateTableData(data);
            }
        });
    });

    function updateTableData(suppliers) {
        var tableData = $('.tableData');
        tableData.empty();

        suppliers.data.forEach(function(supplier, index) {
            var row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${supplier.supplier_name}</td>
                    <td>${supplier.address}</td>
                    <td>${supplier.email}</td>
                    <td>${supplier.contact_number}</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>
            `;
            tableData.append(row);
        });
    }
</script>
@endsection
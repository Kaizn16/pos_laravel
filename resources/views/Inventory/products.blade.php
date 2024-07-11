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
        <a href="{{ route('Inventory.products') }}" role="tab" aria-controls="uom" aria-selected="false">Unit of Measure</a>
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
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>UOM</th>
                    <th>Supplier</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>


            <tbody class="tableData">
                <tr>
                    <td>1.</td>
                    <td><img src="{{ asset('storage/product_image/Milo.jpg') }}" alt="Product Image" width="64" height="64"></td>
                    <td>Milo</td>
                    <td>Beverages</td>
                    <td>₱8.00</td>
                    <td>₱10.00</td>
                    <td>sachet</td>
                    <td>BV&R Commodities Corp.</td>
                    <td>September 22, 2024</td>
                    <td>Enabled</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><img src="{{ asset('storage/product_image/Nescafe.jpg') }}" alt="Product Image" width="64" height="64"></td>
                    <td>Nescafe 3n1 Original</td>
                    <td>Beverages</td>
                    <td>₱8.00</td>
                    <td>₱10.00</td>
                    <td>sachet</td>
                    <td>Nestle Philippines, Inc.</td>
                    <td>August 15, 2024</td>
                    <td>Enabled</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td><img src="{{ asset('storage/product_image/SkyFlakes.jpg') }}" alt="Product Image" width="64" height="64"></td>
                    <td>SkyFlakes 10pcs</td>
                    <td>Snacks</td>
                    <td>₱52.00</td>
                    <td>₱62.00</td>
                    <td>pack</td>
                    <td>Monde M.Y. San Corporation</td>
                    <td>July 30, 2024</td>
                    <td>Enabled</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td><img src="{{ asset('storage/product_image/Colgate.jpg') }}" alt="Product Image" width="64" height="64"></td>
                    <td>Colgate</td>
                    <td>Personal Care</td>
                    <td>₱25.00</td>
                    <td>₱30.00</td>
                    <td>tube</td>
                    <td>Colgate-Palmolive Philippines, Inc.</td>
                    <td>October 10, 2024</td>
                    <td>Enabled</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td><img src="{{ asset('storage/product_image/Pancit Canton.jpg') }}" alt="Product Image" width="64" height="64"></td>
                    <td>Lucky Me Pancit Canton (Chili Mansi) </td>
                    <td>Instant Noodles</td>
                    <td>₱60.00</td>
                    <td>₱90.00</td>
                    <td>pack</td>
                    <td>Monde Nissin Corporation</td>
                    <td>June 25, 2024</td>
                    <td>Enabled</td>
                    <td>
                        <button><i class="uil uil-edit"></i></button>
                        <button><i class="uil uil-trash-alt"></i></button>
                    </td>
                </tr>                
            </tbody>

        </table>


        <footer>
            <span class="showEntries">Showing 1 to 10 of 5 entries</span>
            <div class="pagination">
                <button>Prev</button>
                <button class="active">1</button>
             {{--    <button>2</button>
                <button>3</button>
                <button>4</button>
                <button>5</button> --}}
                <button>Next</button>
            </div>
        </footer>
    </div>
</div>
@endsection
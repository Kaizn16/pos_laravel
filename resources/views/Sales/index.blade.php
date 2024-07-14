@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-money-withdrawal"></i>
            <span class="text">Company Name</span>
        </div>
    </div>

    <section class="sales-container">

        <div class="box ProductsContainer">
            <div class="header">
                
                <div class="searchFilter">
                    <input type="text" id="search_product" name="search_product" placeholder="Search Product">
                </div>

                <div class="categoryFilter">
                    <strong>Filter By Categories:</strong>
                    <div class="filterList">
                        <!-- LIST OF CATEGORIES WILL DISPLAY HERE -->
                    </div>
                </div>

            </div>

            <div class="ProductList">
                <!-- LIST OF PRODUCTS WILL DISPLAY HERE -->
            </div>
        </div>

        <div class="box CartContainer">
            2
        </div>

    </section>

</div>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function fetchProducts(category_id = null, search_term = null) {
        let url = "{{ route('sales.get') }}";
        let data = {
            category_id: category_id
        };

        if (search_term !== null) {
            data.search_term = search_term;
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function(response) {
                let categories = response.categories;
                let products = response.products;
                let stocks = response.stocks;

                // Create a map of product_id to stock amount
                let stockMap = {};
                stocks.forEach(stock => {
                    stockMap[stock.product_id] = {
                        stockAmount: stock.stock_amount,
                        totalStock: stock.total_stock
                    };
                });

                // Update category list
                let categoryListHtml = '<button class="categoryItem" id="defaultCategory" data-category_id="">All Categories</button>';
                    categories.forEach(category => {
                        let activeClass = (category.category_id == category_id || (!category_id && category.category_id === null)) ? 'active' : '';
                        categoryListHtml += `
                            <button class="categoryItem ${activeClass}" data-category_id="${category.category_id}">${category.category_name}</button>
                        `;
                    });
                    $('.filterList').html(categoryListHtml);

                // Update product list
                let productListHtml = '';
                products.forEach(product => {
                    let stockAmount = (stockMap[product.product_id] && stockMap[product.product_id].stockAmount) || 0;
                    let totalStockAmount = (stockMap[product.product_id] && stockMap[product.product_id].totalStock) || 0;
                    
                    const stockPercentage = (stockAmount / totalStockAmount) * 100;
                    
                    let stockStatusColor = '';
                    if (stockPercentage > 50) {
                        stockStatusColor = '#56F000';
                    } else if (stockPercentage > 25 ) {
                        stockStatusColor = '#FFB302';
                    } else {
                        stockStatusColor = '#FF3838';
                    }

                    productListHtml += `
                        <div class="productBox" data-product_id="${product.product_id}">
                            <div class="product-detail-header">
                                <strong><span style="background-color: ${stockStatusColor}"></span> ${stockAmount}</strong>
                                <img src="{{ asset('storage/product_image/') }}/${product.product_image}" alt="Product Image">
                            </div>
                            <div class="product-details">
                                <strong>Product: ${product.product_name}</strong>
                                <p>Category: ${product.category.category_name}</p>
                                <p>Price: ₱ ${product.selling_price} / ${product.uom.uom_name}</p>
                            </div>
                        </div>
                    `;
                });
                $('.ProductList').html(productListHtml);
            },
            error: function(xhr, status, error) {
                toastr.error("Failed to fetch products!");
            }
        });
    }

    $(document).ready(function() {
        // Handle category filter click
        $(document).on('click', '.categoryItem', function() {
            $('.categoryItem').removeClass('active');
            $(this).addClass('active');
            
            let category_id = $(this).data('category_id');
            let searchTerm = $('#search_product').val().trim();
            fetchProducts(category_id, searchTerm);
        });

        // Handle search filter
        let typingTimer;
        let doneTypingInterval = 500;

        $('#search_product').keyup(function() {
            clearTimeout(typingTimer);
            let searchTerm = $(this).val().trim();

            typingTimer = setTimeout(function() {
                let category_id = $('.categoryItem.active').data('category_id');
                fetchProducts(category_id, searchTerm);
            }, doneTypingInterval);
        });

        // WHEN SELECTING PRODUCT
        $(document).on('click', '.productBox', function() {
            let product_id = $(this).data('product_id');

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }

            // Ajax request to fetch product details
            $.ajax({
                url: '/Sales/SelectedProduct/' + product_id + '/View',
                type: 'GET',
                success: function(response) {
                    let product = response.product;
                    let stock = response.stock;
                    let hasStock = stock.some(item => item.product_id === product.product_id);
                    let hasStockWithZeroAmount = stock.some(item => item.product_id === product.product_id && item.stock_amount === 0);

                    if (hasStockWithZeroAmount) {

                        toastr.warning(`${product.product_name} has no available stock!`);
                        return;

                    } else if(hasStock) {

                        let htmlContent = `
                            <div style="display: flex; flex-direction: column; gap: 4px; align-items: center">
                                <img src="{{ asset('storage/product_image/') }}/${product.product_image}" alt="Product Image" style="width: 120px; height: 120px;">
                                <h3>${product.product_name}</h3>
                                <p>Category: ${product.category.category_name}</p>
                                <p>Price: ₱ ${product.selling_price}</p>
                                <div>
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" style="border-radius: 6px; padding: 6px; border: 1px solid #000" onkeypress="return event.keyCode === 8 || event.charCode >= 48 && event.charCode <= 57">
                                </div>
                            </div>
                        `;

                        Swal.fire({
                            html: htmlContent,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '<i class="uil uil-shopping-cart-alt"></i> Add to Cart',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {

                                let quantity = parseInt($('#quantity').val(), 10);
                                let total = product.selling_price * quantity;


                                if(stock.some(item => item.product_id === product.product_id && quantity > item.stock_amount)) {
                                    toastr.warning(`The Quantity is higher than the stock available!`);
                                    return;
                                }

                                console.log('Added to Cart Product ID: ' + product_id + ', Quantity: ' + quantity + ', Price: ' + product.selling_price + ', Total: ' + total);
                            }
                        });

                    } else {
                        toastr.warning(`${product.product_name} does not have a stock!`);
                        return;
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Failed to fetch product details.',
                        'error'
                    );
                }
            });
        });


        fetchProducts(); // INITIALIZE DISPLAY
    });
</script>
@endsection
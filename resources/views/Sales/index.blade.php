@extends('layouts.layout')
@section('content')
<div class="main-content">
    <div class="overview">
        <div class="title">
            <i class="uil uil-store"></i>
            <span class="text">LONA'S BEAUTY STORE</span>
        </div>
    </div>

    <section class="sales-container">

        <div class="box ProductsContainer">
            <div class="header">
                
                <div class="searchFilter">
                    <i class="uil uil-search searchIcon" title="Search..."></i>
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
            <div class="CartProductList">
                <div class="cartHeader">
                    <strong><i class="uil uil-shopping-cart"></i>Cart</strong>
                    <input type="hidden" name="transaction_id" id="transaction_id" value="">
                    <strong>Transaction: <span id="transaction_number" data-transaction_number=""></span></strong>
                </div>
                <div class="cartBody">
                    <table class="CartTable">

                        <thead>
                            <tr class="heading">
                                <th>IMG</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th><i class="uil uil-setting"></i></th>
                            </tr>
                        </thead>
            
                        <tbody class="cartData">
                
                        </tbody>
            
                    </table>
                </div>
                <footer class="cartFooter">
                    <div class="cartFooterDetail">
                        <strong>Total Items in Cart: <span id="cartTotalItem">0</span></strong>
                        <strong>Total: 	₱ <span id="CartTotalAmount"> 00.00</span></strong>
                    </div>
                    <div class="cartBTN">
                        <button class="resetCartBTN"><i class="uil uil-trash-alt cart"></i><span>Clear Cart</span><i class="uil uil-check clear"></i></button>
                        <button class="checkoutBTN">
                            <strong class="cart">
                                <i class="uil uil-list-ui-alt"></i><i class="uil uil-shopping-cart"></i>
                            </strong>
                            <span>Checkout</span>
                            <i class="uil uil-check check"></i>
                        </button>
                    </div>
                </footer>
            </div>
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

        // WHEN SELECTING PRODUCT TO ADD IN THE CART
        $(document).on('click', '.productBox', function() {
            let product_id = $(this).data('product_id');
            let transaction_id = $('#transaction_id').val();

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

                                let selling_price = product.selling_price;
                                let quantity = parseInt($('#quantity').val(), 10);
                                let total = selling_price * quantity;


                                if(stock.some(item => item.product_id === product.product_id && quantity > item.stock_amount)) {
                                    toastr.warning(`The Quantity is higher than the stock available!`);
                                    return;
                                }

                                $.ajax({
                                    url: '{{ route('cart.store') }}',
                                    type: 'POST',
                                    data: {
                                        transaction_id: transaction_id,
                                        product_id: product_id,
                                        quantity: quantity,
                                        product_price: selling_price,
                                        total: total
                                    },
                                    success: function(response) {
                                        fetchCart(transaction_id);
                                    },
                                    error: function(xhr) {
                                        toastr.error('Error adding product to cart:', xhr.statusText);
                                    }
                                });
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

    // FETCHING THE CART
    function fetchCart(transaction_id) {
        $.ajax({
            url: '/Sales/Transaction/' + transaction_id + '/Cart',
            type: 'GET',
            success: function(response) {
                var cartBodyContent = '';
                let cartTotalAmount = 0.00;

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(cart => {

                        cartTotalAmount += parseFloat(cart.total);

                        cartBodyContent += 
                        `<tr>
                            <td><img src="{{ asset('storage/product_image/') }}/${cart.product.product_image}" style="width: 54px; height: 54px"></td>
                            <td><span class='truncated-text' title="${cart.product.product_name}">${cart.product.product_name}</span></td>
                            <td>₱ ${parseFloat(cart.product_price).toFixed(2)}</td>
                            <td>
                                <i class="uil uil-minus minusQuantity" title="Decrement" onclick="adjustQuantity(${cart.product_id}, 'decrease')"></i>
                                <span>  ${cart.quantity}  </span>
                                <i class="uil uil-plus addQuantity" title="Increment" onclick="adjustQuantity(${cart.product_id}, 'increase')"></i>
                            </td>
                            <td>₱ ${parseFloat(cart.total).toFixed(2)}</td>
                            <td><i class="uil uil-times-square removeCart" title="Remove" onclick="RemoveProduct(${cart.product_id})"></td>
                        </tr>`;
                    });

                    $('.cartData').html(cartBodyContent);
                    $('#cartTotalItem').text(response.length);
                    $('#CartTotalAmount').text(cartTotalAmount.toFixed(2));
                    
                } else {

                    cartBodyContent = `<tr><td style="padding-top: 16rem; border: none" colspan="6"><strong>${response.message}</strong></td></tr>`;
                    $('.cartData').html(cartBodyContent);
                    $('#cartTotalItem').text('0');
                    $('#CartTotalAmount').text(cartTotalAmount.toFixed(2));

                }
            },
            error: function(xhr) {
                console.log(xhr);
                Swal.fire(
                    'Error!',
                    'Failed to fetch cart.',
                    'error'
                );
            }
        });
    }

    function adjustQuantity(product_id, action) {
        let transaction_id = $('#transaction_id').val();

        $.ajax({
            url: `/Sales/Transaction/Cart/${product_id}/AdjustQuantity`,
            type: 'PUT',
            data: {
                transaction_id: transaction_id,
                action: action
            },
            success: function(response) {
                fetchCart(transaction_id);
            },
            error: function(xhr, status, error) {
                toastr.error('Error updating quantity');
            }
        });
    }


    // REMOVE PRODUCT
    function RemoveProduct(product_id) {
        let transaction_id = $('#transaction_id').val();

        $.ajax({
            url: '/Sales/Transaction/Cart/' + product_id + '/Remove',
            type: 'POST',
            headers: {
                'X-HTTP-Method-Override': 'PATCH'
            },
            success: function(response) {
                if(response.message == 'Product removed from cart successfully.') 
                {
                    toastr.success(response.message);
                    fetchCart(transaction_id);
                } 
                else 
                {
                    toastr.warning(response.message);
                }
            },
            error: function(xhr) {
                Swal.fire(
                    'Error!',
                    'Failed to remove product from the cart.',
                    'error'
                );
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        
        const searchIcon = document.querySelector('.searchIcon');
        const searchFilter = document.querySelector('.searchFilter');

        searchIcon.addEventListener('click', () => {
            searchFilter.classList.toggle('expanded');
        });

        // CHECKOUT
        document.querySelector('.checkoutBTN').addEventListener('click', function() {

            var cartRows = document.querySelectorAll('.cartData tr');
            var transaction_id = $('#transaction_id').val();
            var transaction_number = $('#transaction_number').attr('data-transaction_number');
            this.disabled = true;

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }

            if (cartRows.length === 0) {
                toastr.warning('The Cart is empty!');
                return;
            }

            this.classList.toggle('activated');

            setTimeout(() => {
                this.disabled = false;
                this.classList.remove('activated');
            }, 3500);

        });

        // CLEAR CART
        document.querySelector('.resetCartBTN').addEventListener('click', function() {

            var transaction_id = $('#transaction_id').val();

            this.disabled = true;
            this.classList.add('activated');

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }

            
            $.ajax({
                url: '/Sales/Transaction/Cart/' + transaction_id + '/Clear',
                type: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PATCH'
                },
                success: function(response) {
                    if(response.message == 'Cart cleared successfully.') 
                    {
                        toastr.success(response.message);
                        fetchCart(transaction_id);
                    } 
                    else 
                    {
                        toastr.info(response.message);
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Failed to clear cart.',
                        'error'
                    );
                }
            });

            setTimeout(() => {
                this.disabled = false;
                this.classList.remove('activated');
            }, 3500);

        });
    });

    // GENERATE/LOAD Transaction
    function GenerateNewTransaction() {
        $.ajax({
            url: '{{ route('sales.new_transaction') }}',
            type: 'GET',
            success: function(response) {
                if(response.transaction_number) {
                    let transaction_id = response.transaction_id;
                    let transaction_number = response.transaction_number;

                    $('#transaction_number').text(transaction_number);
                    $('#transaction_id').val(transaction_id);
                    $('#transaction_number').attr('data-transaction_number', transaction_number);

                    fetchCart(transaction_id);

                } else {
                    console.error('Failed to generate transaction number');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    GenerateNewTransaction(); // Generate New Transaction Number
</script>
@endsection
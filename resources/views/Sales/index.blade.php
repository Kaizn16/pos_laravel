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
<script src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>
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
                let cartBodyContent = '';
                let totalItem = 0;
                let cartTotalAmount = 0.00;

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(cart => {

                        totalItem += parseInt(cart.quantity);
                        cartTotalAmount += parseFloat(cart.total);

                        cartBodyContent += 
                        `<tr>
                            <td><img src="{{ asset('storage/product_image/') }}/${cart.product.product_image}" style="width: 54px; height: 54px"></td>
                            <td><span class='truncated-text' title="${cart.product.product_name}">${cart.product.product_name}</span></td>
                            <td>₱ ${parseFloat(cart.product_price).toFixed(2)}</td>
                            <td>
                                <i class="uil uil-minus minusQuantity" title="Decrement" onclick="adjustQuantity(${cart.product_id}, 'decrease')"></i>
                                <span>  ${parseInt(cart.quantity)}  </span>
                                <i class="uil uil-plus addQuantity" title="Increment" onclick="adjustQuantity(${cart.product_id}, 'increase')"></i>
                            </td>
                            <td>₱ ${parseFloat(cart.total).toFixed(2)}</td>
                            <td>
                                <i class="uil uil-times-square removeCart" title="Remove" onclick="RemoveProduct(${cart.product_id}, '${escapeJsString(cart.product.product_name)}')">
                                </i>
                            </td>
                        </tr>`;
                    });

                    $('.cartData').html(cartBodyContent);
                    $('#cartTotalItem').text(totalItem);
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
            url: `/Sales/Transaction/Cart/Product/${product_id}/AdjustQuantity`,
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
    function RemoveProduct(product_id, product_name) {
        let transaction_id = $('#transaction_id').val();

        Swal.fire({
            title: `Cart Remove Product`,
            text: `Are you sure you want to remove ${product_name} from the cart?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/Sales/Transaction/Cart/Product/' + product_id + '/Remove',
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
        })
    }

    function escapeJsString(str) {
        return str.replace(/\\/g, '\\\\') // Escape backslashes
                .replace(/'/g, "\\'")   // Escape single quotes
                .replace(/"/g, '\\"')   // Escape double quotes
                .replace(/\n/g, '\\n')  // Escape newlines
                .replace(/\r/g, '\\r')  // Escape carriage returns
                .replace(/\t/g, '\\t')  // Escape tabs
                .replace(/\f/g, '\\f')  // Escape form feeds
                .replace(/\v/g, '\\v'); // Escape vertical tabs
    }

    document.addEventListener('DOMContentLoaded', () => {
        
        const searchIcon = document.querySelector('.searchIcon');
        const searchFilter = document.querySelector('.searchFilter');

        searchIcon.addEventListener('click', () => {
            searchFilter.classList.toggle('expanded');
        });

        // CHECKOUT
        document.querySelector('.checkoutBTN').addEventListener('click', function() {

            let transaction_id = $('#transaction_id').val();
            let transaction_number = $('#transaction_number').attr('data-transaction_number');
            let totalCartItems = $('#cartTotalItem').text();
            let subtotal = $('#CartTotalAmount').text();
            this.disabled = true;

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }

            if (totalCartItems == 0) {
                this.disabled = false;
                toastr.warning('The Cart is empty!');
                return;
            }

            this.classList.toggle('activated');

            setTimeout(() => {
                this.disabled = false;
                this.classList.remove('activated');
            }, 2500);


            setTimeout(() => {
                // CHECKOUT FORM
                let formContent = `
                    <form action="{{ route('transaction.pay') }}" method="POST" id="checkoutForm" class="checkoutForm" enctype="multipart/form-data"> 
                        @csrf
                        <input type="hidden" name="transaction_id" value="${transaction_id}">
                        <input type="hidden" name="discount_id" id="discount_id" value="">
                        <input type="hidden" name="discount_percentage" id="discount_percentage" value="">
                        <input type="hidden" name="total_item" value="${totalCartItems}">

                        <div class="logo-image">
                            <img src="{{ asset('assets/Images/Store Logo.JPG') }}">
                        </div>
                        <strong>CHECKOUT</span></strong>
                        <strong><i class="uil uil-transaction"></i> <span>Transaction: ${transaction_number}</span></strong>
                        <div class="form-group-row">
                             <div class="form-group">
                                <select name="customer" id="customer">
                                    <option selected value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->customer_id }}" >{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="discount" id="discount">
                                    <option selected value="">Select Discount</option>
                                    @foreach ($discounts as $discount)
                                        <option value="{{ $discount->discount_percentage }}" data-discount_id="{{ $discount->discount_id }}" >{{ $discount->discount_type }} - {{ $discount->discount_percentage }}%</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group">
                                <select name="payment_method" id="payment_method">
                                    <option selected value="">Select Payment Method</option>
                                    @foreach ($payment_methods as $mop)
                                        <option value="{{ $mop->payment_method_id }}" >{{ $mop->payment_method_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="number" id="subtotal" name="subtotal" placeholder="Subtotal" value="${subtotal}" readonly>
                                <label for="subtotal" class="form-label" >Subtotal: ₱</label>
                            </div>
                            <div class="form-group">
                                <input type="number" id="discount_amount" name="discount_amount" placeholder="discount_amount" value="0" readonly>
                                <label for="discount_amount" class="form-label" >Discount Amount: ₱</label>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group">
                                <input type="number" id="grandtotal" name="grandtotal" placeholder="Grand Total" min="1" value="${subtotal}" readonly>
                                <label for="grandtotal" class="form-label" >Grand Total: ₱</label>
                            </div> 
                            <div class="form-group">
                                <input type="number" id="pay_amount" name="pay_amount" placeholder="Pay Amount" autocomplete="off" min="1" value="0" onkeypress="return isNumberKey(event)">
                                <label for="pay_amount" class="form-label" >Pay Amount: ₱</label>
                            </div>
                            <div class="form-group">
                                <input type="number" id="change" name="change" placeholder="Change" value="0" readonly>
                                <label for="change" class="form-label" >Change: ₱</label>
                            </div>
                        </div>

                    </form>
                `;
                
                // REQUEST FOR PAYMENT
                Swal.fire({
                    html: formContent,
                    width: '800px',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'PAY',
                    reverseButtons: true,
                    customClass: { popup: 'custom-swal-background' },
                    preConfirm: () => {
                        
                        var payAmount = parseFloat(document.getElementById('pay_amount').value) || 0;
                        var grandTotal = parseFloat(document.getElementById('grandtotal').value) || 0;

                        if (payAmount < grandTotal) {
                            Swal.showValidationMessage('Enter a valid amount');
                            return false;
                        } else {
                            return true;
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var customerSelect = document.getElementById("customer");
                        var selectedCustomer = customerSelect.options[customerSelect.selectedIndex].text;

                        if (selectedCustomer === "Select Customer") {
                            selectedCustomer = "N/A";
                        }

                        
                        var paymentMethodSelect = document.getElementById("payment_method");
                        var selectedPaymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex].text;

                        if (selectedPaymentMethod === "Select Payment Method" ) {
                            selectedPaymentMethod = "N/A";
                        }

                        var discount_percent = parseInt(document.getElementById('discount_percentage')) || 0;
                        var discount_amount = parseFloat(document.getElementById('discount_amount').value).toFixed(2) || 0;
                        var subtotal = parseFloat(document.getElementById('subtotal').value).toFixed(2) || 0;
                        var grandTotal = parseFloat(document.getElementById('grandtotal').value).toFixed(2) || 0;
                        var payAmount = parseFloat(document.getElementById('pay_amount').value).toFixed(2) || 0;
                        var changeAmount = parseFloat(document.getElementById('change').value).toFixed(2) || 0;

                        let dateNow = new Date();
                        let options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true};
                        let formattedDate = dateNow.toLocaleDateString('en-US', options);
    
                        let form = document.getElementById('checkoutForm');
                        let formData = new FormData(form);

                        // SAVE PAYMENT
                        $.ajax({
                            url: '{{ route('transaction.pay') }}',
                            type: 'POST',
                            data: formData,
                            headers: {
                                'X-HTTP-Method-Override': 'POST'
                            },
                            processData: false,
                            contentType: false,
                            success: function(response) {
                        
                                toastr.success('Payment Success!');
                                var transaction = response.transaction;
                                var transactionDetails = response.transaction_receipt;

                                function isNumberKey(evt) {
                                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                                    // Allow: backspace, delete, left arrow, right arrow, tab, and decimal point
                                    if (charCode === 8 || charCode === 46 || charCode === 37 || charCode === 39 || charCode === 9) {
                                        return true;
                                    }
                                    // Ensure that it is a number and stop the keypress if it is not
                                    if (charCode < 48 || charCode > 57) {
                                        return false;
                                    }
                                    return true;
                                }
                                // Create receipt table
                                let receiptTable = `
                                    <div class="checkout-receipt" id="receipt" style="display: flex; flex-direction: column; gap: 2px; justify-content: flex-start; align-items: start; padding: 12px;">
                                        <div style="display: flex; flex-direction: column; gap: 4px; justify-content: center; align-items: center; width: 100%">
                                            <img style="width 64px; height: 64px; border-radius: 50%;" src="{{ asset('assets/Images/Store Logo.JPG') }}">
                                            <strong>RECEIPT</strong>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 2px; justify-content: flex-start; align-items: start; font-size: 14px">
                                            <strong>Transaction: ${transaction.transaction_number}</strong>
                                            <strong>Transaction Date: ${transaction.transaction_date}</strong>
                                            <strong>Cashier: </strong>
                                            <strong>Customer: ${selectedCustomer || "N/A"}</strong>
                                            <strong>Payment Method: ${selectedPaymentMethod} </strong>
                                        </div>
                                        <table border="1" cellpadding="10" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>IMG</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="receiptBody">
                                                ${transactionDetails.map(detail => `
                                                    <tr>
                                                        <td><img src="{{ asset('storage/product_image/') }}/${detail.product.product_image}" alt="${detail.product.product_name}" style="width: 50px; height: 50px;"></td>
                                                        <td>${detail.product.product_name}</td>
                                                        <td>${detail.quantity}</td>
                                                        <td>₱ ${detail.product_price}</td>
                                                        <td>₱ ${detail.total}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><strong>Discount %</strong></td>
                                                    <td>${discount_percent}%</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><strong>Subtotal</strong></td>
                                                    <td>₱ ${subtotal}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><strong>Discount Amount</strong></td>
                                                    <td>₱ ${discount_amount}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><strong>Grand Total</strong></td>
                                                    <td>₱ ${grandTotal}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><strong>Paid Amount</strong></td>
                                                    <td>₱ ${payAmount}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right"><strong>Change Amount</strong></td>
                                                    <td>₱ ${changeAmount}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                `;
                                
                                fetchProducts();
                                GenerateNewTransaction();
                                fetchCart(transaction_id);

                                // Display the receipt in a new SweetAlert2 modal
                                Swal.fire({
                                    html: receiptTable,
                                    width: '800px',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: '<i class="uil uil-print"></i> PRINT',
                                    cancelButtonText: 'CLOSE',
                                    reverseButtons: true,
                                    customClass: { popup: 'custom-swal-background' },
                                    preConfirm: () => {
                                        return new Promise((resolve) => {
                                            // Generate PDF from receiptTable HTML content
                                            const element = document.createElement('div');
                                            element.innerHTML = receiptTable;
                                            html2pdf(element, {
                                                margin:       1,
                                                filename:     `receipt-${transaction_number}.pdf`,
                                                image:        { type: 'jpeg', quality: 0.98 },
                                                html2canvas:  { scale: 4 },
                                                jsPDF:        { unit: 'cm', format: 'a4', orientation: 'portrait' }
                                            }).then(() => {
                                                resolve();
                                            });
                                        });
                                    }
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.cancel || result.isConfirmed) {
                                        fetchProducts();
                                        GenerateNewTransaction();
                                        let transaction_id = $('#transaction_id').val();
                                        fetchCart(transaction_id);
                                    }
                                });

                            },
                            error: function(xhr) {
                                toastr.error('Error adding product to cart:', xhr.statusText);
                            }
                        });
                    }
                });

                // CHANGED CALCULATION
                function calculateChange() {
                    var payAmount = parseFloat(document.getElementById('pay_amount').value) || 0;
                    var grandTotal = parseFloat(document.getElementById('grandtotal').value) || 0;
                    
                    var change = payAmount - grandTotal;
                    if (change < 0) {
                        change = 0;
                    }
                    
                    document.getElementById('change').value = change.toFixed(2);
                }

                // CHECKOUT GRAND TOTAL CALCULATION
                // Add event listener for discount change
                document.getElementById('discount').addEventListener('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var discountId = selectedOption.data('discount_id');

                    var discountPercentage = parseFloat(this.value) || 0;
                    var subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
                    
                    var discountAmount = subtotal * (discountPercentage / 100);
                    var grandTotal = subtotal - discountAmount;
                    
                    document.getElementById('discount_amount').value = discountAmount.toFixed(2) || 0;
                    document.getElementById('discount_id').value = discountId;
                    document.getElementById('discount_percentage').value = discountPercentage;
                    document.getElementById('grandtotal').value = grandTotal.toFixed(2);
                    

                    calculateChange();
                });

                // Add event listener for pay amount change
                document.getElementById('pay_amount').addEventListener('input', function() {
                    calculateChange();
                });

            }, 2500);
        });

        // CLEAR CART
        document.querySelector('.resetCartBTN').addEventListener('click', function() {

            var transaction_id = $('#transaction_id').val();

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }

            Swal.fire({
                title: `Clear Cart`,
                text: `Are you sure you want to clear the cart? you won't be able to revert is after.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.disabled = true;
                    this.classList.add('activated');

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
                }
            })
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
    GenerateNewTransaction(); // Intialize Generate New Transaction Number
</script>
@endsection
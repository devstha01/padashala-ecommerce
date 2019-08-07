$(function () {
    //get cart dropdown
    function cartCheckoutView() {
        $.get(serverCustom.base_url + '/api/get-cart-session', function (data) {
            // console.log(data.list);
            var net_total = 0;
            var checkoutAppend = '';
            var checkoutProducts = '';
            var checkoutProductsAddress = '';
            var sn = 0;
            var sn1 = 0;
            $.each(data.list, function (index, value) {
                var out_of_stock = '';
                var variant_name = '';
                if (value.options.variant_name !== null) {
                    variant_name = '<br> [ ' + value.options.variant_name + ' ]';
                }
                if (!value.options.status) {
                    out_of_stock = "<span class='text-danger'>Out of Stock !</span>"
                    checkoutProducts += '<tr><td>' + (++sn) + '</td><td>' + value.name + variant_name + '</td><td>  $' + value.price + '</td><td>' + value.qty + '<i class="fa fa-times text-danger" style="font-size: 10px;"> Stock!</i></td><td style=\'color:red;text-decoration:line-through\'><span style=\'color:black\'>  $' + value.price * value.qty + '</span></td></tr>';
                } else {
                    checkoutProducts += '<tr><td>' + (++sn) + '</td><td>' + value.name + variant_name + '</td><td>  $' + value.price + '</td><td style="margin-left:7px">    X ' + value.qty + '</td><td>  $' + value.price * value.qty + '</td></tr>';
                    net_total += value.price * value.qty;

                    checkoutProductsAddress += '<tr><td>' + (++sn1) + '</td><td>' + value.name + variant_name + '</td><td>  $' + value.price + '</td><td style="margin-left:7px">  X ' + value.qty + '</td><td>  $' + value.price * value.qty + '</td></tr>';

                }

                checkoutAppend += '<tr class="product-row">\n' +
                    '                                    <td class="product-col">\n' +
                    '                                        <figure class="product-image-container">\n' +
                    '                                            <a href="' + serverCustom.base_url + '/product/' + value.options.slug + '" class="product-image">\n' +
                    '                                                <img src="' + serverCustom.base_url + '/image/products/' + value.options.image + '" alt="product">\n' +
                    '                                            </a>\n' +
                    '                                        </figure>\n' +
                    '                                        <h2 class="product-title">\n' +
                    '                                            <a href="' + serverCustom.base_url + '/product/' + value.options.slug + '">' + value.name + variant_name + '</a>\n' +
                    '                                        </h2>\n' +
                    '                                    </td>\n' +
                    '                                    <td>$' + value.price + '</td>\n' +
                    '                                    <td>\n' +
                    '                                        <div class="input-group  bootstrap-touchspin bootstrap-touchspin-injected">' +
                    '                                        <input class="vertical-quantity form-control" type="text" value="' + value.qty + '">\n' +
                    '<span class="input-group-btn-vertical">' +
                    '<button class="btn btn-outline bootstrap-touchspin-up icon-up-dir checkout-item-up" data-qty="' + value.qty + '" data-rowid="' + value.rowId + '" type="button"></button>' +
                    '<button class="btn btn-outline bootstrap-touchspin-down icon-down-dir checkout-item-down" data-qty="' + value.qty + '" data-rowid="' + value.rowId + '" type="button"></button>' +
                    '</span>' +
                    '</div>' + out_of_stock + '</td>\n' +
                    '                                    <td>$' + value.price * value.qty + '</td>\n' +
                    // '                                </tr>\n' +
                    // '                                <tr class="product-action-row">\n' +
                    '                                    <td>\n' +
                    // '                                        <div class="float-left">\n' +
                    // '                                            <a href="#" class="btn-move">Move to Wishlist</a>\n' +
                    // '                                        </div>\n' +
                    // '                                        <div class="float-right">\n' +
                    '                                            <a href="#" title="Remove product" class="btn-remove remove-checkout-product" data-row="' + value.rowId + '"><span class="sr-only">Remove</span></a>\n' +
                    // '                                        </div>\n' +
                    '                                    </td>\n' +
                    '                                </tr>';


            });

            var cartTable = $('#checkout-table-list');
            cartTable.empty();
            cartTable.append(checkoutAppend);

            var cartSummary = $('#cart-checkout-summary');
            cartSummary.empty();
            cartSummary.append(checkoutProducts);

            var cartSummaryAddress = $('#cart-checkout-summary-address');
            cartSummaryAddress.empty();
            cartSummaryAddress.append(checkoutProductsAddress);

            $('#checkout-net_total').html('$' + net_total);

            checkoutItemUpDown();
            removeCheckoutProduct();
        });
        cartDropdownCount();
    }

    function checkoutItemUpDown() {
        $('.checkout-item-up').on('click', function () {
            var up_row_id = $(this).data('rowid');
            var up_quantity = $(this).data('qty');
            $.get(serverCustom.base_url + '/checkout-item-up', {
                'row_id': up_row_id,
                'qty': up_quantity
            }, function (data) {
                if (data.status !== false)
                    cartCheckoutView();

                // console.log(data);
            });
        });

        $('.checkout-item-down').on('click', function () {
            var down_row_id = $(this).data('rowid');
            var down_quantity = $(this).data('qty');
            $.get(serverCustom.base_url + '/checkout-item-down', {
                'row_id': down_row_id,
                'qty': down_quantity
            }, function (data) {
                cartCheckoutView();
                // console.log(data);
            });
        });
    }

    //remove cart item on checkout times button
    function removeCheckoutProduct() {
        $('.remove-checkout-product').on('click', function (e) {
            e.preventDefault();
            var rowId = $(this).data('row');

            swal({
                    title: "",
                    text: 'Remove this item from the cart ?',
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonText: "ok",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {

                        $.post(serverCustom.base_url + '/api/cart-remove-product', {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            row_id: rowId
                        }, function (data) {
                            window.location.reload();
                        });
                        swal.close();
                    }
                });
        });
    }


    //call cart-checkout list on load
    //call cart-checkout list on load
    cartCheckoutView();

});
//homepage top categories
// $.get(serverCustom.base_url + '/api/get-home', function (data) {
//     // console.log(data.categories);
//     var side_menu_body = $('#side-menu-body');
//     side_menu_body.empty();
//     var liAppend = '';
//     $.each(data.categories.data, function (index, value) {
//         liAppend += '<li><a href="' + serverCustom.base_url + '/product-category?type=category&slug=' + value.slug + '">\n' +
//             '<i><img class="category-img rounded-circle" src="' + value.image + '" alt="_"></i>\n' +
//             value.name + '</a></li>\n';
//     });
//     side_menu_body.append(liAppend);
// });

$(document).on('click', '#addCart', function (e) {
    e.preventDefault();
    var request_data = {};
    request_data['_token'] = $(this)
        .find('input[name=_token]')
        .val();
    request_data['quantity'] = $(this)
        .parent()
        .find('input[name="qty"]')
        .val();
    var obj = $('.variant:checked').data('obj');
    if (obj !== undefined) {
        request_data['variant'] = obj;
    }
    $.post($(this).attr('action'), request_data, function (data) {
        var cart_message = $('#cart-message');
        if (data.success === true) {
            cart_message.removeClass('alert-warning');
            cart_message.addClass('alert-info');
        }
        else {
            cart_message.removeClass('alert-info');
            cart_message.addClass('alert-warning');
        }
        cart_message.hide();
        cart_message.slideDown("slow", function () {
            // Animation complete.
        });
        // console.log(data);
        cart_message.html(data.message);
        cart_message.show();
        setTimeout(function () {
            cart_message.hide();
        }, 5000);
        cartDropdownCount();
    });
});

function highlightChecked() {
    $('input.variant').parent().removeClass('highlight-checked');
    $('input.variant:checked').parent().addClass('highlight-checked');
}

//color and size changes
$('.color-select').on('click', function () {
    var colorId = $(this).data('color_id');

    $('.color-select').removeClass('color-highlight');
    $(this).addClass('color-highlight');

    $('.color-options').addClass('hide-size');
    $('.color-option-' + colorId).removeClass('hide-size');
    var colorOptions = $.find('.color-option-' + colorId);
    colorOptions[0].click();
    highlightChecked();
});

//Variant price changes
var obj = $('.variant:checked').data('obj');
if (obj !== undefined) {
    $('.old-price-cart').html('$ ' + obj.marked_price);
    $('.product-price-cart').html('$ ' + obj.sell_price);
    $('.max-value').val(obj.quantity);
    if (obj.quantity == 0)
        $('#low-stock-warning').html('Out of stock!');
    else if (obj.quantity < 10)
        $('#low-stock-warning').html('Only ' + obj.quantity + ' available in stock. Order soon!');
    else
        $('#low-stock-warning').html('');
}
$('.variant').on('change', function () {
    var obj = $(this).data('obj');
    $('.old-price-cart').html('$ ' + obj.marked_price);
    $('.product-price-cart').html('$ ' + obj.sell_price);
    $('.max-value').val(obj.quantity);
    if (obj.quantity == 0)
        $('#low-stock-warning').html('Out of stock!');
    else if (obj.quantity < 10)
        $('#low-stock-warning').html('Only ' + obj.quantity + ' available in stock. Order soon!');
    else
        $('#low-stock-warning').html('');
    highlightChecked();
});


//get cart dropdown
function cartDropdownCount() {
    $.get(serverCustom.base_url + '/api/get-cart-session', function (data) {
        // console.log(data);
        $('.cart-count').html(data.count);
        $('.cart-total-price').html('$ ' + data.total);
        var listAppend = '';
        $.each(data.list, function (index, value) {
            var out_of_stock = '';
            if (!value.options.status) {
                out_of_stock = "<span class='text-danger'>Out of Stock !</span>"
            }
            var variant_name = '';
            if (value.options.variant_name !== null) {
                variant_name = '<br> [ ' + value.options.variant_name + ' ]';
            }

            listAppend += '<div class="product">\n' +
                '                    <div class="product-details">\n' +
                '                        <h4 class="product-title"><a href="' + serverCustom.base_url + '/product/' + value.options.slug + '">' + value.name + variant_name + '</a></h4>\n' +
                '                        <span class="cart-product-info">\n' +
                '                            <span class="cart-product-qty">' + value.qty + '</span>x $' + value.price + '\n' +
                '                        </span><br>' + out_of_stock +
                '                    </div>\n' +
                '                    <figure class="product-image-container">\n' +
                '                        <a href="' + serverCustom.base_url + '/product/' + value.options.slug + '" class="product-image">\n' +
                '                            <img src="' + serverCustom.base_url + '/image/products/' + value.options.image + '" alt="product">\n' +
                '                        </a>\n' +
                '                        <a href="#" data-row="' + value.rowId + '" class="btn-remove remove-cart-product" title="Remove Product"><i class="icon-cancel"></i></a>\n' +
                '                    </figure>\n' +
                '                </div>';
        });
        var dropdown_cart_products = $('.dropdown-cart-products');
        dropdown_cart_products.empty();
        dropdown_cart_products.append(listAppend);
        removeCartProduct();
    });
}

//remove cart item on dropdown times button
function removeCartProduct() {
    $('.remove-cart-product').on('click', function (e) {
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
                        cartDropdownCount();

                        if (serverCustom.current_uri === 'cart-view')
                            window.location.reload();

                        if (serverCustom.current_uri === 'order/address')
                            window.location.reload();

                    });
                    swal.close();
                }
            });
    });
}

//swal custom confirm

// function customConfirm(message) {
//     e.preventDefault();
//     swal({
//             title: "Confirm",
//             text: message,
//             icon: "success",
//             showCancelButton: true,
//             confirmButtonText: "ok",
//             closeOnConfirm: false
//         },
//         function (isConfirm) {
//             if (isConfirm) {
//                 swal.close();
//                 callback();
//             }
//         });
// }


//call dropdown on load
//call dropdown on load
cartDropdownCount();

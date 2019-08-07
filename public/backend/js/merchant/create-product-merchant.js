$(function () {

    // function categoryShare() {
    //     var share_percentage = $('#product_category').find('option:checked').data('share');
    //     $('#category_share').val(share_percentage);
    // }
    //
    // function netShare() {
    //     var net_share = parseFloat($('#category_share').val() || 0) + parseFloat($('#product_share').val() || 0);
    //     $('#net_share').val(net_share);
    // }
    //
    // categoryShare();
    // netShare();
    // $('#product_share').on('change', function () {
    //     netShare()
    // });

    var base_url = serverCustom.base_url;
    $('#product_category').on('change', function () {
        var category_id = $(this).val();
        categoryShare();
        netShare();
        $.ajax({
            // type: "POST",
            url: base_url + '/merchant/product/get-sub-category',
            data: {'category_id': category_id},
            success: function (data) {
                $('#product_sub_category').empty();
                var options = '';
                if (data.sub.length !== 0) {
                    options = "<option value=''>Select a sub-category</option>"
                    for (var i = 0; i < data.sub.length; i++) {
                        options += "<option value='" + data.sub[i].id + "'>" + data.sub[i].name + "</option>";
                    }
                } else {
                    options = "<option value=''>No sub-category available.</option>"
                }
                $('#product_sub_category').append(options);
                $('#product_sub_child_category').empty();
                $('#product_sub_child_category').append("<option value=''>No sub-child-category available.</option>");
            }
        });
    });


    $('#product_sub_category').on('change', function () {
        var sub_category_id = $(this).val();
        $.ajax({
            // type: "POST",
            url: base_url + '/merchant/product/get-sub-child-category',
            data: {'sub_category_id': sub_category_id},
            success: function (data) {
                $('#product_sub_child_category').empty();
                var options = '';
                if (data.sub.length !== 0) {
                    options = "<option value=''>Select a sub-child-category</option>"
                    for (var i = 0; i < data.sub.length; i++) {
                        options += "<option value='" + data.sub[i].id + "'>" + data.sub[i].name + "</option>";
                    }
                } else {
                    options = "<option value=''>No sub-child-category available.</option>"
                }
                $('#product_sub_child_category').append(options);
            }
        });
    });

    // $('.edit-modal-btn').on('click', function () {
    //     var data = $(this).data('variant');
    //     $('#edit-modal-id').val(data.id);
    //     $('#edit-modal-name').val(data.eng_name);
    //     $('#edit-modal-marked_price').val(data.marked_price);
    //     $('#edit-modal-sell_price').val(data.sell_price);
    //     $('#edit-modal-discount').val(data.discount);
    //     $('#edit-modal-quantity').val(data.quantity);
    // });
    //
    //
    // $('#search-product').on('keyup', function () {
    //     var input = $(this).val();
    //
    //     if (input.length < 2) {
    //         $('#search-product-list').empty().css({'height': 0});
    //         $("#search-product-form").bind('submit', function (e) {
    //             e.preventDefault();
    //         });
    //     } else {
    //         $('#search-product-list').css({'height': '100px'});
    //         $('#search-product-form').unbind('submit');
    //         $.ajax({
    //             url: base_url + '/merchant/product/get-search-list',
    //             data: {'term': input},
    //             success: function (data) {
    //                 $('#search-product-list').empty();
    //                 $.each(data.products, function (index, value) {
    //                     var appendData = "<a href='" + base_url + "/merchant/product/edit/" + value.slug + "'>" + value.name + "</a><br>";
    //                     $('#search-product-list').append(appendData);
    //                 });
    //             }
    //         });
    //     }
    // });

    $('.checkbox-stock').on('change', function () {
        var stock_checkbox_value = $(this).is(':checked');
        if (stock_checkbox_value === true) {
            $(this).val('true');
            $(this).parent().parent().parent().find('.quantity').html('Total quantity');
        } else {
            $(this).parent().parent().parent().find('.quantity').html('Max Purchase Limit');
            $(this).val('false');
        }
    });

    function removeOption() {
        $('.remove-option').on('click', function (e) {
            e.preventDefault();
            var optionCount = $(this).data('option');
            // console.log(optionCount);
            $('.option-' + optionCount).remove();
            return false;
        });
    }

    function fillColor(optionCount) {
        performAjaxCall('/colors', 'GET', '', function (response) {
            var options_list = "";
            $.each(response, function (index, value) {
                options_list += "<option style='background: " + value.color_code + "' value='" + value.id + "'>" + value.name + "</option>>";
            });
            $('.color-options-' + optionCount).append(options_list);
        })
        $('.stock-option-input-' + optionCount).on('change', function () {

            var stock_option_value = $(this).is(':checked');
            if (stock_option_value === true) {
                $(this).parent().parent().parent().find('.quantity').html('Total quantity');
                $(this).parent().parent().find('.stock-option-value').val('true');
            } else {
                $(this).parent().parent().parent().find('.quantity').html('Max Purchase Limit');
                $(this).parent().parent().find('.stock-option-value').val('false');
            }
        });
    }

    var optionCount = 0;
    fillColor(0);
    removeOption();
    $('.options-add-btn').on('click', function (e) {
        e.preventDefault();
        $('.options-table-show').show();
        ++optionCount;
        var appendHtml = "<tr class=\"option-" + optionCount + "\">\n" +
            "                                            <td><select name=\"color[]\" class=\"form-control color-options-" + optionCount + "\" required></select>\n" +
            "                                        </td>\n" +
            "                                        <td><input type=\"text\" name=\"size[]\" class=\"form-control\"></td>\n" +
            "                                        <td><input type=\"text\" min='0' name=\"marked_price[]\" class=\"form-control marked-price\" ></td>\n" +
            "                                        <td><input type=\"text\" min='0' name=\"sell_price[]\" class=\"form-control sell-price\" required></td>\n" +
            "                                        <td><input type=\"text\" min='0' max=\"99\" name=\"discount_price[]\" class=\"form-control discount\" ></td>\n" +
            "                                        <td class='check-delivery-option'><label>ManageStock<input type=\"checkbox\" class=\"stock-option-input-" + optionCount + "\" checked></label>" +
            "                                            <input type=\"hidden\" class=\"stock-option-value\" name=\"stock_option[]\" value=\"true\">\n</td>\n" +
            "                                        <td class='check-delivery-option'><span class=\"quantity\">Total quantity</span><input type=\"number\" min='0' name=\"quantity[]\" class=\"form-control\"></td>\n" +
            "                                        <td>\n" +
            "                                            <a class=\"btn red remove-option \" data-option='" + optionCount + "'><i class=\"fa fa-trash\"></i></a>\n" +
            "                                        </td>\n" +
            "                                    </tr>\n" +
            "                                    ";
        $('.options-table-body').append(appendHtml);
        fillColor(optionCount);
        removeOption();
        optionMpDiscountSell();
        purchaseOptionQuantityManage();
        return false;
    });

    $('.options-form-update').on('click', function () {
        var inputs = $(this).parent().parent().find('input');
        var optionsData = {'_token': getCSRFToken()};
        $.each(inputs, function (index, item) {
            optionsData[item.name] = item.value;
        });
        var main = $(this);
        performAjaxCall('/merchant/product/update-product-variant', 'POST', optionsData, function (response) {
            console.log(response);
            var options_message_update = main.parent().parent().find('.options-message-update');
            if (response.status === true)
                options_message_update.html(response.message).css({color: 'green'}).show();
            else
                options_message_update.html(response.message).css({color: 'red'}).show();
            setTimeout(function () {
                options_message_update.html('').hide();
            }, 3000);
        });
    });

    $('.admin-options-form-update').on('click', function () {
        var inputs = $(this).parent().parent().find('input');
        var optionsData = {'_token': getCSRFToken()};
        $.each(inputs, function (index, item) {
            optionsData[item.name] = item.value;
        });
        var main = $(this);
        performAjaxCall('/admin/merchant/update-product-variant', 'POST', optionsData, function (response) {
            // console.log(response);
            var options_message_update = main.parent().parent().find('.options-message-update');
            if (response.status === true)
                options_message_update.html(response.message).css({color: 'green'}).show();
            else
                options_message_update.html(response.message).css({color: 'red'}).show();
            setTimeout(function () {
                options_message_update.html('').hide();
            }, 3000);
        });
    });

    optionMpDiscountSell();

    function optionMpDiscountSell() {


        $('.marked-price').on('keyup', function () {
            var parentClass = $(this).parent().parent();

            var marked_price = parentClass.find('.marked-price');
            var sell_price = parentClass.find('.sell-price');
            var discount = parentClass.find('.discount');

            if (checkNumber(marked_price.val(), sell_price.val())) {
                var caulatedPrice = calculatePricing(marked_price.val(), sell_price.val(), 0);
                discount.val(caulatedPrice.ds);
            }
        });
        $('.discount').on('keyup', function () {
            var parentClass = $(this).parent().parent();

            var marked_price = parentClass.find('.marked-price');
            var sell_price = parentClass.find('.sell-price');
            var discount = parentClass.find('.discount');
            if (discount.val() >= 0 && discount.val() < 99.999) {
                if (checkNumber(discount.val(), sell_price.val())) {
                    var caulatedPrice = calculatePricing(0, sell_price.val(), discount.val());
                    marked_price.val(caulatedPrice.mp);
                }
            }
        });

        $('.sell-price').on('keyup', function () {
            var parentClass = $(this).parent().parent();

            var marked_price = parentClass.find('.marked-price');
            var sell_price = parentClass.find('.sell-price');
            var discount = parentClass.find('.discount');

            if (marked_price.val() === '') {
                if (checkNumber(discount.val(), sell_price.val())) {
                    var caulatedPrice = calculatePricing(0, sell_price.val(), discount.val());
                    marked_price.val(caulatedPrice.mp);
                }
            } else if (discount.val() === '') {
                if (checkNumber(marked_price.val(), sell_price.val())) {
                    var caulatedPrice1 = calculatePricing(marked_price.val(), sell_price.val(), 0);
                    discount.val(caulatedPrice1.ds);
                }
            } else {
                if (checkNumber(marked_price.val(), sell_price.val())) {
                    var caulatedPrice2 = calculatePricing(marked_price.val(), sell_price.val(), 0);
                    discount.val(caulatedPrice2.ds);
                }
            }

        });

        function checkNumber(first, second) {
            if (first === '') return false;
            if (second === '') return false;
            if (!$.isNumeric(first)) return false;
            if (!$.isNumeric(second)) return false;

            return true;
        }

        function calculatePricing(marked_price, sell_price, discount) {
            var mp = marked_price;
            var sp = sell_price;
            var ds = discount;
            var calculate = 'ds';
            if (mp === 0)
                calculate = 'mp';
            else if (sp === 0)
                calculate = 'sp';
            else
                calculate = 'ds';

            switch (calculate) {
                case'mp':
                    mp = sell_price /(1 - (discount / 100));
                    break;

                case'sp':
                    sp = marked_price - ((discount / 100) * marked_price);
                    break;

                case 'ds':
                    if (marked_price !== 0)
                        ds = (marked_price - sell_price) * (100 / marked_price);
                    if (ds < 0 || ds > 100)
                        ds = '';
                    break;
            }
            return {
                'mp': mp,
                'sp': sp,
                'ds': ds
            };
        }
    }

    // purchaseOptionQuantityManage();
    //
    // function purchaseOptionQuantityManage() {
    //     var purchase_option = $('#merchant-delivery-option').val();
    //     if (purchase_option != '1') {
    //         $('.check-delivery-option').css({display: 'none'});
    //     }
    // }
});

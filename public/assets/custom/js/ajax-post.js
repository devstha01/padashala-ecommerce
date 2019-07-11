//Min MAX config for wallet transfer
function checkMinMax(wallet, amount, callbackValid) {
    performAjaxCall('/minmax-check', 'GET', {
        'wallet': wallet,
        'amount': amount
    }, function (response) {
        if (response.status === false) {
            callbackValid(false, response.error);
        } else callbackValid(true, response.message);
    });
}


jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');

        // var datatype='json';
        // console.log(request_data);

        var actionFirst = APP_URL + '/member/add-new-member-check';


        var secondaryCall = confirmSecondaryPin;
        if (request_data['role'] == 'admin') {
            secondaryCall = confirmSecondaryPinAdmin;
        }


        $.post(
            actionFirst,
            request_data,

            function (data) {

                if (data.status == 'success') {
                    secondaryCall(function () {
                        areYouSure(function () {

                            showFullPageLoader();

                            $.post(
                                action,
                                request_data,

                                function (data) {

                                    if (data.status == 'success') {
                                        console.log('true');
                                        hideFullPageLoader();
                                        $('.scroll-top-profile-page').hide();
                                        console.log('success');

                                        window.location.href = data.url;
                                        // location.reload();
                                        hideFullPageLoader();

                                    } else if (data.status === 'fails') {

                                        for (var key in data.errors) {

                                            // skip loop if the property is from prototype
                                            if (!data.errors.hasOwnProperty(key)) continue;

                                            var error_message = data.errors[key];

                                            current_form.find("[name=" + key + "]").addClass('errors');

                                            var parent = current_form.find("[name=" + key + "]").parent();
                                            parent.find('.error-message').addClass('make-visible').html(error_message);
                                            hideFullPageLoader();

                                            current_form.find('.scroll-top-profile-page').show();


                                        }

                                        if ($('.scroll-top-profile-page').length > 0) {
                                            //var ele = $("span.error-message.make-visible")[0];
                                            //var pos = ele.offset().top;
                                            $('html, body').animate({
                                                scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                            }, 500);
                                        }

                                    }

                                }
                                , "json")
                                .done(function (data) {
                                    // if ( console && console.log ) {
                                    //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                                    // }
                                    hideFullPageLoader();

                                });
                        });

                    });

                } else if (data.status === 'fails') {

                    for (var key in data.errors) {

                        // skip loop if the property is from prototype
                        if (!data.errors.hasOwnProperty(key)) continue;

                        var error_message = data.errors[key];

                        current_form.find("[name=" + key + "]").addClass('errors');

                        var parent = current_form.find("[name=" + key + "]").parent();
                        parent.find('.error-message').addClass('make-visible').html(error_message);
                        hideFullPageLoader();

                        current_form.find('.scroll-top-profile-page').show();


                    }

                    if ($('.scroll-top-profile-page').length > 0) {
                        //var ele = $("span.error-message.make-visible")[0];
                        //var pos = ele.offset().top;
                        $('html, body').animate({
                            scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                        }, 500);
                    }

                }

            }
            , "json")
            .done(function (data) {
                // if ( console && console.log ) {
                //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                // }
                hideFullPageLoader();

            });


        return false;

    });

});

jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-merchant', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        console.log(request_data);

        confirmSecondaryPinMerchant(function () {
            areYouSure(function () {

                showFullPageLoader();

                $.post(
                    action,
                    request_data,

                    function (data) {

                        if (data.status === 'success') {
                            console.log('true');

                            $('.scroll-top-profile-page').hide();
                            console.log('success');
                            window.location.href = data.url;
                            hideFullPageLoader();

                        } else if (data.status == 'fails') {

                            for (var key in data.errors) {

                                // skip loop if the property is from prototype
                                if (!data.errors.hasOwnProperty(key)) continue;

                                var error_message = data.errors[key];

                                current_form.find("[name=" + key + "]").addClass('errors');

                                var parent = current_form.find("[name=" + key + "]").parent();
                                parent.find('.error-message').addClass('make-visible').html(error_message);
                                hideFullPageLoader();

                                current_form.find('.scroll-top-profile-page').show();


                            }

                            if ($('.scroll-top-profile-page').length > 0) {
                                //var ele = $("span.error-message.make-visible")[0];
                                //var pos = ele.offset().top;
                                $('html, body').animate({
                                    scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                }, 500);
                            }

                        }

                    }
                    , "json")
                    .done(function (data) {
                        // if ( console && console.log ) {
                        //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                        // }
                        hideFullPageLoader();

                    });
            });

        });

        return false;

    });

});

jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-grant', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();

        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();

        });


        var displayMsg = 'Grant ' + request_data['wallet_type'].replace("_", " ") + ' For ' + request_data['memberName'] + ' Value ' + request_data['wallet_value'];


        var action = $(this).attr('action');
        // var datatype='json';
        console.log(request_data);

        confirmSecondaryPinAdmin(function () {
            areYouSureGrantRetain(function () {

                showFullPageLoader();

                $.post(
                    action,
                    request_data,

                    function (data) {

                        if (data.status === 'success') {
                            console.log('true');

                            $('.scroll-top-profile-page').hide();
                            console.log('success');
                            location.reload();
                            hideFullPageLoader();

                        } else if (data.status == 'fails') {

                            for (var key in data.errors) {

                                // skip loop if the property is from prototype
                                if (!data.errors.hasOwnProperty(key)) continue;

                                var error_message = data.errors[key];

                                current_form.find("[name=" + key + "]").addClass('errors');

                                var parent = current_form.find("[name=" + key + "]").parent();
                                parent.find('.error-message').addClass('make-visible').html(error_message);
                                hideFullPageLoader();

                                current_form.find('.scroll-top-profile-page').show();


                            }

                            if ($('.scroll-top-profile-page').length > 0) {
                                //var ele = $("span.error-message.make-visible")[0];
                                //var pos = ele.offset().top;
                                $('html, body').animate({
                                    scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                }, 500);
                            }

                        }

                    }
                    , "json")
                    .done(function (data) {
                        // if ( console && console.log ) {
                        //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                        // }
                        hideFullPageLoader();

                    });
            }, displayMsg);

        });

        return false;

    });

});

jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-retain', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();

        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();

        });

        var displayMsgRetain = 'Retain ' + request_data['wallet_type'].replace("_", " ") + ' For ' + request_data['memberName'] + ' Value ' + request_data['wallet_value'];


        var action = $(this).attr('action');
        // var datatype='json';
        console.log(request_data);

        confirmSecondaryPinAdmin(function () {
            areYouSureGrantRetain(function () {

                showFullPageLoader();

                $.post(
                    action,
                    request_data,

                    function (data) {

                        if (data.status === 'success') {
                            console.log('true');

                            $('.scroll-top-profile-page').hide();
                            console.log('success');
                            location.reload();
                            hideFullPageLoader();

                        } else if (data.status == 'fails') {

                            for (var key in data.errors) {

                                // skip loop if the property is from prototype
                                if (!data.errors.hasOwnProperty(key)) continue;

                                var error_message = data.errors[key];

                                current_form.find("[name=" + key + "]").addClass('errors');

                                var parent = current_form.find("[name=" + key + "]").parent();
                                parent.find('.error-message').addClass('make-visible').html(error_message);
                                hideFullPageLoader();

                                current_form.find('.scroll-top-profile-page').show();


                            }

                            if ($('.scroll-top-profile-page').length > 0) {
                                //var ele = $("span.error-message.make-visible")[0];
                                //var pos = ele.offset().top;
                                $('html, body').animate({
                                    scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                }, 500);
                            }

                        }

                    }
                    , "json")
                    .done(function (data) {
                        // if ( console && console.log ) {
                        //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                        // }
                        hideFullPageLoader();

                    });
            }, displayMsgRetain);

        });

        return false;

    });

});

jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-wallet-withdraw', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);

        var valid = true;

        var min_amount = $('#min_amount').val();
        if (request_data.amount < min_amount) {
            var parent1 = current_form.find("[name='amount']").parent();
            parent1.find('.error-message').addClass('make-visible').html('Withdrawal amount must be minimum $' + min_amount);
            valid = false;
        }
        var max_amount = $('#max_amount').val();
        if (request_data.amount > max_amount) {
            var parent2 = current_form.find("[name='amount']").parent();
            parent2.find('.error-message').addClass('make-visible').html('Withdrawal amount exceeds the maximum limit of $' + max_amount);
            valid = false;
        }

        if (request_data.amount === '') {
            var parent3 = current_form.find("[name='amount']").parent();
            parent3.find('.error-message').addClass('make-visible').html('Amount field is required');
            valid = false;
        }

        if (valid === true) {
            confirmSecondaryPin(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status == 'success') {
                                console.log('true');

                                $('.scroll-top-profile-page').hide();
                                console.log('success');
                                location.reload();
                                hideFullPageLoader();

                            } else if (data.status === 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
            // } else {
            //     swal({
            //         title: "Invalid Withdrawal Amount!",
            //         text: "The withdrawal amount limitation: minimum $" + request_data.min_amount + ' - maximum $' + request_data.max_amount,
            //         type: "warning",
            //         timer: 10000
            //     });
        }
        return false;
    });

});

//wallet convert validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-convert', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);

        var valid = true;
        if (request_data.wallet === '') {
            var parent1 = current_form.find("[name='wallet']").parent();
            parent1.find('.error-message').addClass('make-visible').html('Wallet field is required');
            valid = false;
        }
        if (request_data.amount === '') {
            var parent2 = current_form.find("[name='amount']").parent();
            parent2.find('.error-message').addClass('make-visible').html('Amount field is required');
            valid = false;
        }

        switch (request_data.transferto) {
            case 'ecash_wallet':
                var ecash_wallet_validation = parseInt($('#ecash_wallet_validation').html());
                if (request_data.amount !== '') {
                    if (request_data.amount > ecash_wallet_validation) {
                        var parent3 = current_form.find("[name='amount']").parent();
                        parent3.find('.error-message').addClass('make-visible').html('the amount you are trying to convert is more than your balance');
                        valid = false;
                    }
                }
                break;
            case 'evoucher_wallet':
                var evoucher_wallet_validation = parseInt($('#evoucher_wallet_validation').html());
                if (request_data.amount !== '') {
                    if (request_data.amount > evoucher_wallet_validation) {
                        var parent4 = current_form.find("[name='amount']").parent();
                        parent4.find('.error-message').addClass('make-visible').html('the amount you are trying to convert is more than your balance');
                        valid = false;
                    }
                }
                break;
            default:
                var parent5 = current_form.find("[name='transferto']").parent();
                parent5.find('.error-message').addClass('make-visible').html('Transfer wallet field is required');
                valid = false;
                break;
        }

        if (request_data.amount < 100) {
            var parent6 = current_form.find("[name='amount']").parent();
            parent6.find('.error-message').addClass('make-visible').html('The amount must be atleast 100');
            valid = false;
        }

        if (request_data.amount > 5000) {
            var parent7 = current_form.find("[name='amount']").parent();
            parent7.find('.error-message').addClass('make-visible').html('The amount may not be greater than 5000');
            valid = false;
        }

        if (valid === true) {
            confirmSecondaryPin(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status == 'success') {
                                console.log('true');
                                hideFullPageLoader();
                                $('.scroll-top-profile-page').hide();
                                console.log('success');
                                window.location.href = data.url;
                                // location.reload();
                                hideFullPageLoader();

                            } else if (data.status === 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
        }
        return false;

    });

});

//shopping withdraw validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-shopping', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);

        var valid = true;


        if (request_data.amount <= 0) {
            var parent4 = current_form.find("[name='amount']").parent();
            parent4.find('.error-message').addClass('make-visible').html('Amount should be greater than 0');
            valid = false;
        }

        if (request_data.amount === '') {
            var parent2 = current_form.find("[name='amount']").parent();
            parent2.find('.error-message').addClass('make-visible').html('Amount field is required');
            valid = false;
        }


        var shop_point_validation = parseInt($('#shop_point_validation').html());
        if (request_data.amount !== '') {
            if (request_data.amount > shop_point_validation) {
                var parent3 = current_form.find("[name='amount']").parent();
                parent3.find('.error-message').addClass('make-visible').html('the amount you are trying to withdraw is more than your balance');
                valid = false;
            }
        }


        if (valid === true) {
            confirmSecondaryPin(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status == 'success') {
                                console.log('true');
                                hideFullPageLoader();
                                $('.scroll-top-profile-page').hide();
                                console.log('success');
                                window.location.href = data.url;
                                // location.reload();
                                hideFullPageLoader();

                            } else if (data.status === 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
        }
        return false;

    });

});


//wallet transfer validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-transfer', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);
        checkMinMax(request_data.wallet, request_data.amount, function (minmaxResponse, minmaxError) {
            if (minmaxResponse !== true) {
                var amountminmax = current_form.find("[name='amount']").parent();
                amountminmax.find('.error-message').addClass('make-visible').html(minmaxError);
            } else {

                var valid = true;

                if (request_data.wallet === '') {
                    var parent1 = current_form.find("[name='wallet']").parent();
                    parent1.find('.error-message').addClass('make-visible').html('Wallet field is required');
                    valid = false;
                }

                if (request_data.amount <= 0) {
                    var parent2 = current_form.find("[name='amount']").parent();
                    parent2.find('.error-message').addClass('make-visible').html('Amount should be greater than 0');
                    valid = false;
                }

                if (request_data.amount === '') {
                    var parent3 = current_form.find("[name='amount']").parent();
                    parent3.find('.error-message').addClass('make-visible').html('Amount field is required');
                    valid = false;
                }

                if (request_data.member_id === '') {
                    var parent4 = current_form.find("[name='member_id']").parent();
                    parent4.find('.error-message').addClass('make-visible').html('Login Id field is required');
                    valid = false;
                }

                switch (request_data.wallet) {
                    case'ecash_wallet':
                        var ecash_wallet_validation = parseInt($('#ecash_wallet_validation').html());
                        if (request_data.amount !== '') {
                            if (request_data.amount > ecash_wallet_validation) {
                                var parent5 = current_form.find("[name='amount']").parent();
                                parent5.find('.error-message').addClass('make-visible').html('the amount you are trying to transfer is more than your balance');
                                valid = false;
                            }
                        }
                        break;

                    case'evoucher_wallet':
                        var evoucher_wallet_validation = parseInt($('#evoucher_wallet_validation').html());
                        if (request_data.amount !== '') {
                            if (request_data.amount > evoucher_wallet_validation) {
                                var parent6 = current_form.find("[name='amount']").parent();
                                parent6.find('.error-message').addClass('make-visible').html('the amount you are trying to transfer is more than your balance');
                                valid = false;
                            }
                        }
                        break;
                    case'chip':
                        var chip_validation = parseInt($('#chip_validation').html());
                        if (request_data.amount !== '') {
                            if (request_data.amount > chip_validation) {
                                var parent7 = current_form.find("[name='amount']").parent();
                                parent7.find('.error-message').addClass('make-visible').html('the amount you are trying to transfer is more than your balance');
                                valid = false;
                            }
                        }
                        break;
                    case'r_point':
                        var r_point_validation = parseInt($('#r_point_validation').html());
                        if (request_data.amount !== '') {
                            if (request_data.amount > r_point_validation) {
                                var parent8 = current_form.find("[name='amount']").parent();
                                parent8.find('.error-message').addClass('make-visible').html('the amount you are trying to transfer is more than your balance');
                                valid = false;
                            }
                        }
                        break;
                }


                if (valid === true) {
                    confirmSecondaryPin(function () {
                        areYouSure(function () {

                            showFullPageLoader();

                            $.post(
                                action,
                                request_data,

                                function (data) {

                                    if (data.status == 'success') {
                                        console.log('true');
                                        hideFullPageLoader();
                                        $('.scroll-top-profile-page').hide();
                                        console.log('success');
                                        window.location.href = data.url;
                                        // location.reload();
                                        hideFullPageLoader();

                                    } else if (data.status === 'fails') {

                                        for (var key in data.errors) {

                                            // skip loop if the property is from prototype
                                            if (!data.errors.hasOwnProperty(key)) continue;

                                            var error_message = data.errors[key];

                                            current_form.find("[name=" + key + "]").addClass('errors');

                                            var parent = current_form.find("[name=" + key + "]").parent();
                                            parent.find('.error-message').addClass('make-visible').html(error_message);
                                            hideFullPageLoader();

                                            current_form.find('.scroll-top-profile-page').show();


                                        }

                                        if ($('.scroll-top-profile-page').length > 0) {
                                            //var ele = $("span.error-message.make-visible")[0];
                                            //var pos = ele.offset().top;
                                            $('html, body').animate({
                                                scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                            }, 500);
                                        }

                                    }

                                }
                                , "json")
                                .done(function (data) {
                                    // if ( console && console.log ) {
                                    //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                                    // }
                                    hideFullPageLoader();

                                });
                        });

                    });
                }
                return false;
            }
        });
    });

});

//wallet withdraw validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-withdraw', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);


        checkMinMax('wallet_withdraw', request_data.amount, function (minmaxResponse, minmaxError) {
            if (minmaxResponse !== true) {
                var amountminmax = current_form.find("[name='amount']").parent();
                amountminmax.find('.error-message').addClass('make-visible').html(minmaxError);
            } else {
                var valid = true;


                if (request_data.amount === '') {
                    var parent3 = current_form.find("[name='amount']").parent();
                    parent3.find('.error-message').addClass('make-visible').html('Amount field is required');
                    valid = false;
                }

                if (valid === true) {
                    confirmSecondaryPin(function () {
                        areYouSure(function () {

                            showFullPageLoader();

                            $.post(
                                action,
                                request_data,

                                function (data) {

                                    if (data.status == 'success') {
                                        console.log('true');
                                        hideFullPageLoader();
                                        $('.scroll-top-profile-page').hide();
                                        console.log('success');
                                        window.location.href = data.url;
                                        // location.reload();
                                        hideFullPageLoader();

                                    } else if (data.status === 'fails') {

                                        for (var key in data.errors) {

                                            // skip loop if the property is from prototype
                                            if (!data.errors.hasOwnProperty(key)) continue;

                                            var error_message = data.errors[key];

                                            current_form.find("[name=" + key + "]").addClass('errors');

                                            var parent = current_form.find("[name=" + key + "]").parent();
                                            parent.find('.error-message').addClass('make-visible').html(error_message);
                                            hideFullPageLoader();

                                            current_form.find('.scroll-top-profile-page').show();


                                        }

                                        if ($('.scroll-top-profile-page').length > 0) {
                                            //var ele = $("span.error-message.make-visible")[0];
                                            //var pos = ele.offset().top;
                                            $('html, body').animate({
                                                scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                            }, 500);
                                        }

                                    }

                                }
                                , "json")
                                .done(function (data) {
                                    // if ( console && console.log ) {
                                    //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                                    // }
                                    hideFullPageLoader();

                                });
                        });

                    });
                }
                return false;
            }
        });

    });

});


//wallet withdraw validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-transfer-approve', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);

        var valid = true;

        var min_amount = $('#min_amount').val();
        if (request_data.amount < min_amount) {
            var parent1 = current_form.find("[name='amount']").parent();
            parent1.find('.error-message').addClass('make-visible').html('Withdrawal amount must be minimum $' + min_amount);
            valid = false;
        }

        if (valid === true) {
            confirmSecondaryPin(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status == 'success') {
                                // console.log('true');
                                hideFullPageLoader();
                                // $('.scroll-top-profile-page').hide();
                                // console.log('success');
                                window.location.href = data.url;
                                // location.reload();
                                hideFullPageLoader();

                            } else if (data.status === 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
        }
        return false;

    });

});


//dividned withdraw validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-dividend-transform', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);

        var valid = true;

        var min_amount = $('#dividend_validation').html();
        if (request_data.amount > min_amount) {
            var parent1 = current_form.find("[name='amount']").parent();
            parent1.find('.error-message').addClass('make-visible').html('Dividend amount is greater than your balance');
            valid = false;
        }

        if (request_data.amount === '') {
            var parent3 = current_form.find("[name='amount']").parent();
            parent3.find('.error-message').addClass('make-visible').html('Amount field is required');
            valid = false;
        }

        if (valid === true) {
            confirmSecondaryPin(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status == 'success') {
                                // console.log('true');
                                hideFullPageLoader();
                                // $('.scroll-top-profile-page').hide();
                                // console.log('success');
                                window.location.href = data.url;
                                // location.reload();
                                hideFullPageLoader();

                            } else if (data.status === 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
        }
        return false;

    });

});


//merchant wallet payment
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-merchant-payment', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);


        var valid = true;

        var ecash_wallet_validation = $('#ecash_wallet_validation').html();
        if (request_data.amount > ecash_wallet_validation) {
            var parent1 = current_form.find("[name='amount']").parent();
            parent1.find('.error-message').addClass('make-visible').html('the amount you are trying to transfer is more than your balance');
            valid = false;
        }

        if (request_data.amount === '') {
            var parent2 = current_form.find("[name='amount']").parent();
            parent2.find('.error-message').addClass('make-visible').html('Amount field is required');
            valid = false;
        }
        if (request_data.member_id === '') {
            var parent3 = current_form.find("[name='member_id']").parent();
            parent3.find('.error-message').addClass('make-visible').html('LoginId field is required');
            valid = false;
        }


        if (valid === true) {
            confirmSecondaryPinMerchant(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status === 'success') {
                                console.log('true');

                                $('.scroll-top-profile-page').hide();
                                console.log('success');
                                window.location.href = data.url;
                                hideFullPageLoader();

                            } else if (data.status == 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
        }
        return false;

    });

});


//merchant wallet transfer
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-merchant-transfer', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);


        var valid = true;

        var ecash_wallet_validation = $('#ecash_wallet_validation').html();
        if (request_data.amount > ecash_wallet_validation) {
            var parent1 = current_form.find("[name='amount']").parent();
            parent1.find('.error-message').addClass('make-visible').html('the amount you are trying to transfer is more than your balance');
            valid = false;
        }

        if (request_data.amount === '') {
            var parent2 = current_form.find("[name='amount']").parent();
            parent2.find('.error-message').addClass('make-visible').html('Amount field is required');
            valid = false;
        }
        if (request_data.member_id === '') {
            var parent3 = current_form.find("[name='member_id']").parent();
            parent3.find('.error-message').addClass('make-visible').html('LoginId field is required');
            valid = false;
        }


        if (valid === true) {
            confirmSecondaryPinMerchant(function () {
                areYouSure(function () {

                    showFullPageLoader();

                    $.post(
                        action,
                        request_data,

                        function (data) {

                            if (data.status === 'success') {
                                console.log('true');

                                $('.scroll-top-profile-page').hide();
                                console.log('success');
                                window.location.href = data.url;
                                hideFullPageLoader();

                            } else if (data.status == 'fails') {

                                for (var key in data.errors) {

                                    // skip loop if the property is from prototype
                                    if (!data.errors.hasOwnProperty(key)) continue;

                                    var error_message = data.errors[key];

                                    current_form.find("[name=" + key + "]").addClass('errors');

                                    var parent = current_form.find("[name=" + key + "]").parent();
                                    parent.find('.error-message').addClass('make-visible').html(error_message);
                                    hideFullPageLoader();

                                    current_form.find('.scroll-top-profile-page').show();


                                }

                                if ($('.scroll-top-profile-page').length > 0) {
                                    //var ele = $("span.error-message.make-visible")[0];
                                    //var pos = ele.offset().top;
                                    $('html, body').animate({
                                        scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                    }, 500);
                                }

                            }

                        }
                        , "json")
                        .done(function (data) {
                            // if ( console && console.log ) {
                            //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                            // }
                            hideFullPageLoader();

                        });
                });

            });
        }
        return false;

    });

});


//merchant withdrawal request

jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-withdraw-merchant', function (e) {
        e.preventDefault();
        $('.error-message').each(function () {
            $(this).removeClass('make-visible');
            $(this).html('');
        });

        $('input').each(function () {
            $(this).removeClass('errors');
        });

        // current form under process
        var current_form = $(this);

        // === Dynamically get all the values of input data
        var request_data = {};

        request_data['_token'] = $(this).find('input[name=_token]').val();


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
            if ($('#male').is(":checked")) {
                request_data['gender'] = 'male';
            }
            if ($('#female').is(":checked")) {
                request_data['gender'] = 'female';
            }
            if ($('#position_id_1').is(":checked")) {
                request_data['position_id'] = '1';
            }
            if ($('#position_id_2').is(":checked")) {
                request_data['position_id'] = '2';
            }
            if ($('#position_id_3').is(":checked")) {
                request_data['position_id'] = '3';
            }
            if ($('#position_id_4').is(":checked")) {
                request_data['position_id'] = '4';
            }
            if ($('#position_id_5').is(":checked")) {
                request_data['position_id'] = '5';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        // console.log(request_data);


        checkMinMax('wallet_withdraw', request_data.amount, function (minmaxResponse, minmaxError) {
            if (minmaxResponse !== true) {
                var amountminmax = current_form.find("[name='amount']").parent();
                amountminmax.find('.error-message').addClass('make-visible').html(minmaxError);
            } else {
                var valid = true;


                if (request_data.amount === '') {
                    var parent3 = current_form.find("[name='amount']").parent();
                    parent3.find('.error-message').addClass('make-visible').html('Amount field is required');
                    valid = false;
                }

                if (valid === true) {
                    confirmSecondaryPinMerchant(function () {
                        areYouSure(function () {

                            showFullPageLoader();

                            $.post(
                                action,
                                request_data,

                                function (data) {

                                    if (data.status === 'success') {
                                        console.log('true');

                                        $('.scroll-top-profile-page').hide();
                                        console.log('success');
                                        window.location.href = data.url;
                                        hideFullPageLoader();

                                    } else if (data.status == 'fails') {

                                        for (var key in data.errors) {

                                            // skip loop if the property is from prototype
                                            if (!data.errors.hasOwnProperty(key)) continue;

                                            var error_message = data.errors[key];

                                            current_form.find("[name=" + key + "]").addClass('errors');

                                            var parent = current_form.find("[name=" + key + "]").parent();
                                            parent.find('.error-message').addClass('make-visible').html(error_message);
                                            hideFullPageLoader();

                                            current_form.find('.scroll-top-profile-page').show();


                                        }

                                        if ($('.scroll-top-profile-page').length > 0) {
                                            //var ele = $("span.error-message.make-visible")[0];
                                            //var pos = ele.offset().top;
                                            $('html, body').animate({
                                                scrollTop: ($('span.error-message.make-visible').first().offset().top - 120)
                                            }, 500);
                                        }

                                    }

                                }
                                , "json")
                                .done(function (data) {
                                    // if ( console && console.log ) {
                                    //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                                    // }
                                    hideFullPageLoader();

                                });
                        });

                    });
                }
                return false;
            }
        });

    });

});

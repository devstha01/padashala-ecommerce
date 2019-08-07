var ecash_payment = parseInt($('#ecash_payment').html());
var evoucher_payment = parseInt($('#evoucher_payment').html());

//payment validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-payment', function (e) {
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

        var valid = true;
        if (request_data.payment_to === '') {
            var parent1 = current_form.find("[name='payment_to']").parent();
            parent1.find('.error-message').addClass('make-visible').html('Username is required');
            valid = false;
        }
        switch (request_data.payment_method) {
            case 'ecash_wallet':
                if (request_data.amount > ecash_payment) {
                    var parent4 = current_form.find("[name='amount']").parent();
                    parent4.find('.error-message').addClass('make-visible').html('the amount is more than your balance');
                    valid = false;
                }
                break;
            case 'evoucher_wallet':
                if (request_data.amount > evoucher_payment) {
                    var parent5 = current_form.find("[name='amount']").parent();
                    parent5.find('.error-message').addClass('make-visible').html('the amount is more than your balance');
                    valid = false;
                }
                break;
            default:
                var parent2 = current_form.find("[name='payment_method']").parent();
                parent2.find('.error-message').addClass('make-visible').html('Method is required');
                valid = false;
                break;
        }


        if (request_data.amount === '') {
            var parent3 = current_form.find("[name='amount']").parent();
            parent3.find('.error-message').addClass('make-visible').html('Amount is required');
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

$('#are-you-sure-btn').on('click', function () {
    var wallet = $('select[name="payment_method"]').children("option:selected").html();
    var amount = $('input[name="amount"]').val();
    var merchant = $('input[name="payment_to"]').val();
    $('#are-you-sure-message').html('Payment amount $' + amount + ' in ' + wallet + ' to ' + merchant);
});

$('#are-you-sure-btn-transfer').on('click', function () {
    var wallet = $('select[name="payment_method"]').children("option:selected").html();
    var amount = $('input[name="amount"]').val();
    var merchant = $('input[name="payment_to"]').val();
    $('#are-you-sure-message').html('Transfer amount $' + amount + ' in ' + wallet + ' to ' + merchant);
});


//QR payment validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-qr-payment', function (e) {
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
        if (request_data.qr_payment_to === '') {
            var parent1 = current_form.find("[name='qr_payment_to']").parent();
            parent1.find('.error-message').addClass('make-visible').html('Username is required');
            valid = false;
        }

        switch (request_data.qr_payment_method) {
            case 'ecash_wallet':
                if (request_data.amount > ecash_payment) {
                    var parent4 = current_form.find("[name='qr_amount']").parent();
                    parent4.find('.error-message').addClass('make-visible').html('the amount is more than your balance');
                    valid = false;
                }
                break;
            case 'evoucher_wallet':
                if (request_data.amount > evoucher_payment) {
                    var parent5 = current_form.find("[name='qr_amount']").parent();
                    parent5.find('.error-message').addClass('make-visible').html('the amount is more than your balance');
                    valid = false;
                }
                break;
            default:
                var parent2 = current_form.find("[name='qr_payment_method']").parent();
                parent2.find('.error-message').addClass('make-visible').html('Method is required');
                valid = false;
                break
        }


        if (request_data.qr_amount === '') {
            var parent3 = current_form.find("[name='qr_amount']").parent();
            parent3.find('.error-message').addClass('make-visible').html('Amount is required');
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

$('#are-you-sure-qr-btn').on('click', function () {
    var qrwallet = $('select[name="qr_payment_method"]').children("option:selected").html();
    var qramount = $('input[name="qr_amount"]').val();
    var qrmerchant = $('input[name="qr_payment_to"]').val();
    $('#are-you-sure-message').html('Payment amount $' + qramount + ' in ' + qrwallet + ' to ' + qrmerchant);
});


$('#are-you-sure-qr-btn-transfer').on('click', function () {
    var qrwallet = $('select[name="qr_payment_method"]').children("option:selected").html();
    var qramount = $('input[name="qr_amount"]').val();
    var qrmerchant = $('input[name="qr_payment_to"]').val();
    $('#are-you-sure-message').html('Payment amount $' + qramount + ' in ' + qrwallet + ' to ' + qrmerchant);
});


//QR payment validation changes
jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-approve', function (e) {
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

$('#are-you-sure-approve-btn').on('click', function () {
    var to = $(this).data('user');
    var wallet = $(this).data('wallet');
    var amount = $(this).data('amount');
    $('#are-you-sure-message').html('Payment amount $' + amount + ' in ' + wallet + ' to ' + to);
});
$('#are-you-sure-decline-btn').on('click', function () {
    var to = $(this).data('user');
    var wallet = $(this).data('wallet');
    var amount = $(this).data('amount');
    $('#are-you-sure-message').html('Decline payment amount $' + amount + ' in ' + wallet + ' to ' + to);
});


//ORDER ADDRESS


jQuery(document).ready(function ($) {
    $(document).on('submit', '.ajax-post-order-address', function (e) {
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

            if ($('#cash_payment_method').is(":checked")) {
                request_data['payment_method'] = 'cash';
            }
            if ($('#ecash_payment_method').is(":checked")) {
                request_data['payment_method'] = 'ecash_wallet';
            }

        });

        var action = $(this).attr('action');
        // var datatype='json';
        console.log(request_data);
        //
        var valid = true;

        if (request_data.old_address === "false") {
            if (request_data.address === '') {
                var parent1 = current_form.find("[name='address']").parent();
                parent1.find('.error-message').addClass('make-visible').html('Address is required');
                valid = false;
            }
            if (request_data.country_id === '') {
                var parent2 = current_form.find("[name='country_id']").parent();
                parent2.find('.error-message').addClass('make-visible').html('Country is required');
                valid = false;
            }
        }

        var user_type = $('#user-type').html();
        switch (user_type) {
            case 'member':

                if (request_data.ecash_wallet === "") {
                    $('#ecash_method_error').html('Cash Wallet is required');
                    valid = false;
                } else {
                    // var ecash_payment = $('#ecash_payment').html();
                    if (parseInt(request_data.ecash_wallet) > ecash_payment) {
                        $('#ecash_method_error').html('Not enough Cash Wallet in your balance');
                        valid = false;
                    }
                }
                if (request_data.evoucher_wallet === "") {
                    $('#evoucher_method_error').html('Voucher Wallet is required');
                    valid = false;
                } else {
                    // var evoucher_payment = $('#evoucher_payment').html();
                    if (parseInt(request_data.evoucher_wallet) > evoucher_payment) {
                        $('#evoucher_method_error').html('Not enough Voucher Wallet in your balance');
                        valid = false;
                    }
                }

                break;
            case 'customer':
                if (request_data.payment_method === 'ecash_wallet') {
                    var ecash_payment_customer = $('#ecash_payment').html();
                    if (parseInt($('#total_checkout').val()) > ecash_payment_customer) {
                        $('#payment_method_error').html('Not enough Cash Wallet in your balance');
                        valid = false;
                    }
                }
                break;
            default:
                break;
        }

        if (request_data.old_contact === "false") {
            if (request_data.email === '') {
                var parent3 = current_form.find("[name='email']").parent();
                parent3.find('.error-message').addClass('make-visible').html('Email is required');
                valid = false;
            }
            if (request_data.contact_number === '') {
                var parent4 = current_form.find("[name='contact_number']").parent();
                parent4.find('.error-message').addClass('make-visible').html('Contact is required');
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
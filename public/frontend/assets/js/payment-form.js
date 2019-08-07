$(function () {
    $('#check-merchant').on('click', function (e) {
        e.preventDefault();
        var user_name = $('#payment-to').val();
        $.ajax({
            // type: "POST",
            url: serverCustom.base_url + '/merchant-exist',
            data: {'payment_to': user_name},
            success: function (data) {
                if (data.status === true) {
                    $('#merchant-name').html(data.data.name + ' ' + data.data.surname);
                    $('#merchant-user_name').html(data.data.user_name);
                    $('#check-merchant').html('<i class="fa fa-check text-success"></i> Check Merchant');
                } else {
                    $('#merchant-name').html(' - ');
                    $('#merchant-user_name').html(' No match found ');
                    $('#check-merchant').html('<i class="fa fa-times text-danger"></i> Check Merchant');
                }
            }
        });
    });

    $('#check-user').on('click', function (e) {
        e.preventDefault();
        var user_name = $('#payment-to').val();
        $.ajax({
            // type: "POST",
            url: serverCustom.base_url + '/customer-exist',
            data: {'payment_to': user_name},
            success: function (data) {
                console.log(data);
                if (data.status === true) {
                    $('#user-name').html(data.data.name + ' ' + data.data.surname);
                    $('#user-user_name').html(data.data.user_name);
                    $('#check-user').html('<i class="fa fa-check text-success"></i> Check User');
                } else {
                    $('#user-name').html(' - ');
                    $('#user-user_name').html(' No match found ');
                    $('#check-user').html('<i class="fa fa-times text-danger"></i> Check User');
                }
            }
        });
    });

});
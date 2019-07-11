$(function () {
    $('#edit-shopping').on('click', function () {
        $('.shopping-content').attr('contentEditable', true).addClass('bg-info');
        $(this).addClass('fade');
        $('#save-shopping').removeClass('fade');
    });
    $('#edit-package').on('click', function () {
        $('.shopping-content').attr('contentEditable', true).addClass('bg-info');
        $(this).addClass('fade');
        $('#save-package').removeClass('fade');
    });

    $('#save-package').on('click', function () {
        $('.shopping-content').attr('contentEditable', false).removeClass('bg-info');
        $(this).addClass('fade');
        $('#edit-package').removeClass('fade');

        var inputs = [];
        $('span.shopping-content').each(function () {
            inputs.push({'name': $(this).data('key'), 'value': $(this).html()});
        });

        $.ajax({
            url: serverCustom.base_url + '/admin/config/edit-package',
            method: 'POST',
            data: {'_token': $('meta[name=csrf-token]').attr("content"), 'input': inputs},
            success: function (response) {
                var message_shopping = $('#message-shopping');
                message_shopping.html(response.message);
                if (response.status === true) {
                    message_shopping.addClass('alert-success');
                    message_shopping.removeClass('alert-warning');
                } else {
                    message_shopping.addClass('alert-warning');
                    // message_shopping.html('Unauthorized action');
                    message_shopping.removeClass('alert-success');
                }
                message_shopping.removeClass('fade');
                setTimeout(function () {
                    message_shopping.addClass('fade');
                }, 3000);
            }
        });
    });

    $('#save-shopping').on('click', function () {
        $('.shopping-content').attr('contentEditable', false).removeClass('bg-info');
        $(this).addClass('fade');
        $('#edit-shopping').removeClass('fade');

        var inputs = [];
        $('span.shopping-content').each(function () {
            inputs.push({'name': $(this).data('key'), 'value': $(this).html()});
        });

        $.ajax({
            url: serverCustom.base_url + '/admin/shopping/edit-shopping',
            method: 'POST',
            data: {'_token': $('meta[name=csrf-token]').attr("content"), 'input': inputs},
            success: function (response) {
                var message_shopping = $('#message-shopping');
                message_shopping.html(response.message);
                if (response.status === true) {
                    message_shopping.addClass('alert-success');
                    message_shopping.removeClass('alert-warning');
                } else {
                    message_shopping.addClass('alert-warning');
                    // message_shopping.html('Unauthorized action');
                    message_shopping.removeClass('alert-success');
                }
                message_shopping.removeClass('fade');
                setTimeout(function () {
                    message_shopping.addClass('fade');
                }, 3000);
            }
        });
    });

    $(".shopping-row").click(function () {
        var rateName = $(this).data('name');
        var rateObj = $(this).data('obj');
        $('#shopping-username').html(rateName);
        $('input[name=merchant_rate]').val(rateObj.merchant_rate);
        $('input[name=admin_rate]').val(rateObj.admin_rate);
        $('input[name=merchant_id]').val(rateObj.merchant_id);
    });

    var checkValidate = $("#trigger_modal").data('id');
    if (checkValidate) {
        $('.shopping-row[data-id=' + checkValidate + ']').trigger("click");
    }

    $('input[name="merchant_rate"]').on('keyup', function () {
        var mer_rate = $(this).val();
        if ($.isNumeric(mer_rate) && mer_rate < 100) {
            var sum100 = 100 - mer_rate;
            $('input[name="admin_rate"]').val(sum100);
        }
    });

    $('input[name="admin_rate"]').on('keyup', function () {
        var adm_rate = $(this).val();
        if ($.isNumeric(adm_rate) && adm_rate < 100) {
            var sum100 = 100 - adm_rate;
            $('input[name="merchant_rate"]').val(sum100);
        }
    })

    //shopping bonus second tab - generation bonus
    $('#single-bonus-rate').on('click', function () {
        $('.update-rate').attr('contentEditable', true).addClass('bg-info');
        $(this).addClass('fade');
        $('#save-single-bonus-rate').removeClass('fade');
    });

    $('#save-single-bonus-rate').on('click', function () {
        $('.update-rate').attr('contentEditable', false).removeClass('bg-info');
        $(this).addClass('fade');
        $('#single-bonus-rate').removeClass('fade');

        var inputs = [];
        $('span.update-rate').each(function () {
            inputs.push({
                'type': $(this).data('type'),
                'generation': $(this).data('generation'),
                'package': $(this).data('package'),
                'value': $(this).html()
            });
        });
        $.ajax({
            url: serverCustom.base_url + '/admin/shopping/update-single-shopping-rate',
            method: 'POST',
            data: {'_token': $('meta[name=csrf-token]').attr("content"), 'input': inputs},
            success: function (response) {
                var message_shopping = $('#single-bonus-rate-message');
                message_shopping.html(response.message);
                if (response.status === true) {
                    message_shopping.addClass('alert-success');
                    message_shopping.removeClass('alert-warning');
                } else {
                    message_shopping.addClass('alert-warning');
                    // message_shopping.html('Unauthorized action');
                    message_shopping.removeClass('alert-success');
                }
                message_shopping.removeClass('fade');
                setTimeout(function () {
                    message_shopping.addClass('fade');
                }, 3000);
            }
        });
    });


    //referal bonus
    $('#referal-rate').on('click', function () {
        $('.edit-referal').attr('contentEditable', true).addClass('bg-info');
        $(this).addClass('fade');
        $('#save-referal-rate').removeClass('fade');
    });

    $('#save-referal-rate').on('click', function () {
        $('.edit-referal').attr('contentEditable', false).removeClass('bg-info');
        $(this).addClass('fade');
        $('#referal-rate').removeClass('fade');

        var inputs = [];
        $('span.edit-referal').each(function () {
            inputs.push({
                'key': $(this).data('key'),
                'type': $(this).data('type'),
                'generation': $(this).data('generation'),
                'package': $(this).data('package'),
                'value': $(this).html()
            });
        });
        $.ajax({
            url: serverCustom.base_url + '/admin/config/update-single-referral',
            method: 'POST',
            data: {'_token': $('meta[name=csrf-token]').attr("content"), 'input': inputs},
            success: function (response) {
                var message_shopping = $('#referal-rate-message');
                message_shopping.html(response.message);
                if (response.status === true) {
                    message_shopping.addClass('alert-success');
                    message_shopping.removeClass('alert-warning');
                } else {
                    message_shopping.addClass('alert-warning');
                    // message_shopping.html('Unauthorized action');
                    message_shopping.removeClass('alert-success');
                }
                message_shopping.removeClass('fade');
                setTimeout(function () {
                    message_shopping.addClass('fade');
                }, 3000);
            }
        });
    });
});
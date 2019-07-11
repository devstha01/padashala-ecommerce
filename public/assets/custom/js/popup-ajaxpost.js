jQuery(document).ready(function ($) {

    $(document).on('submit', '.ajax-confirm-post', function (e) {
        e.preventDefault();
        // debugger;
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
        request_data['forvalidation'] = 'true';


        current_form.find('[name]').each(function () {
            request_data[$(this).attr("name")] = $(this).val();
        });

        console.log(request_data);


        showFullPageLoader();
        $.post(
            $(this).attr('action'),
            request_data,
            function (data) {

                console.log(data);

                if (data.show_popup == true) {
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to proceed?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: false
                    }, function () {
                        var request_data = {};

                        request_data['_token'] = $(this).find('input[name=_token]').val();
                        request_data['forvalidation'] = 'false';
                        console.log(request_data);
                        current_form.find('[name]').each(function () {
                            request_data[$(this).attr("name")] = $(this).val();
                        });
                        var uri = getTransactionPostUrl();
                        performAjaxCall(uri, 'POST', request_data, function (data) {
                            window.location.href = data.url;
                            hideFullPageLoader();

                        });
                    });
                }

                if (data.status == 'success') {
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
                    }

                }


            }
        )
            .done(function (data) {
                // if ( console && console.log ) {
                //   console.log( "Sample of data:", data.slice( 0, 100 ) );
                // }
                hideFullPageLoader();

            });


        return false;

    });
});

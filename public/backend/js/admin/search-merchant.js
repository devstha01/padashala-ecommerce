$(function () {
    $('#search-merchant').on('keyup', function () {
        var input = $(this).val();
        var base_url = serverCustom.base_url;
        if (input.length < 3) {
            $('#search-merchant-list').empty();
            $("#search-merchant-form").bind('submit', function (e) {
                e.preventDefault();
            });
            // $("#search-merchant-form").submit(function (e) {
            //     e.preventDefault();
            // });
        } else {
            $('#search-merchant-form').unbind('submit');
            $.ajax({
                url: base_url + '/admin/merchant/get-search-merchant',
                data: {'term': input},
                success: function (data) {
                    $('#search-merchant-list').empty();
                    $.each(data.merchants, function (index, value) {
                        var appendData = "<a href='" + base_url + "/admin/merchant/view-merchant/" + value.id + "'>" + value.name + ' ' + value.surname + "</a><br>";
                        $('#search-merchant-list').append(appendData);
                    });
                }
            });
        }
    });
});
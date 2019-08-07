$(function () {


    $('#lang-select').on('change', function () {

        var lang = $(this).val();

        console.log(serverCustom.base_url + "/lang");

        $.get(serverCustom.base_url + "/lang", {lang: lang}, function (responce) {
            console.log(responce);
            location.reload();
        });
    });


    $('.lang-select').on('click', function () {

        var lang = $(this).data('lang');

        console.log(serverCustom.base_url + "/lang");

        $.get(serverCustom.base_url + "/lang", {lang: lang}, function (responce) {
            console.log(responce);
            location.reload();
        });

    });


    $('.seen-notification').on('click', function () {

        var type = $(this).data('type');
        console.log(type);
        $.get(serverCustom.base_url + '/' + type + "/seen-notification", function () {
            // console.log(responce);
        });
    })


    $('.hide-if-li-0 ul').each(function () {
        var li_count = $(this).find('li').length;
        if (li_count === 0)
            $(this).parent().css({display: 'none'})
    })
});


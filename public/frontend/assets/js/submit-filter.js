$(function () {
    $('.trigger-submit-filter').on('change', function () {
        // console.log('ok');
        $('#submit-filter').click();
    });

    $('.arrowAfterHover').hover(function () {
            $(this).addClass('arrowAfter');
        },
        function () {
            $(this).removeClass('arrowAfter');
        });
});
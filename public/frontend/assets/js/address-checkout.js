$(function () {
    var change_bill_address = $('#change-bill-address');
    change_bill_address.change(function () {
        $('.new_address').toggle();
        if (change_bill_address.is(':checked')) {
            $(this).val(true);
        }
        else {
            $(this).val(false);
        }
    });

    var change_bill_contact= $('#change-bill-contact');
    change_bill_contact.change(function () {
        $('.new_contact').toggle();
        if (change_bill_contact.is(':checked')) {
            $(this).val(true);
        }
        else {
            $(this).val(false);
        }
    });


    var ecash_wallet = $('#ecash_wallet');
    var evoucher_wallet = $('#evoucher_wallet');
    var total_checkout = $('#total_checkout').val();

    evoucher_wallet.on('keyup', function () {
        var diff = total_checkout - evoucher_wallet.val();
        if (diff < 0) {
            evoucher_wallet.val(total_checkout);
            ecash_wallet.val(0);
        } else {
            ecash_wallet.val(diff);
        }
    });


    ecash_wallet.on('keyup', function () {
        var diff = total_checkout - ecash_wallet.val();
        if (diff < 0) {
            ecash_wallet.val(total_checkout);
            evoucher_wallet.val(0);
        } else {
            evoucher_wallet.val(diff);
        }
    });
});
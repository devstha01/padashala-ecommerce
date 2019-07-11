$(document).ready(function () {
    $('#selectWallet').change(function(){
        var option = $(this).val();
        $.ajax({
            type: 'get',
            url: APP_URL + '/member/asset-value-from-wallet-selection',
            data: {
                option: option
            },
            success: function(data) {
                $('#currentAmount').val(data.value);
            }
        });
    });

    $('#genpassword').click(function(){
        var memberId = $('#memberId').val();
        $.ajax({
            type: 'get',
            url: APP_URL + '/member/check-member',
            data: {
                memberId: memberId
            },
            success: function(data) {
                if(data.status == true){
                    $('#memberName').val(data.name);
                    swal('System found the match');
                    return false;
                }
                $('#memberName').val('');
                swal('System could not match username. Please check again');

            }
        });
    });


    $('#genmerchant').click(function(){
        var memberId = $('#memberId').val();
        $.ajax({
            type: 'get',
            url: APP_URL + '/merchant/payment/check-customer',
            data: {
                memberId: memberId
            },
            success: function(data) {
                if(data.status == true){
                    $('#memberName').val(data.name);
                    swal('System found the match');
                    return false;
                }
                $('#memberName').val('');
                swal('System could not match username. Please check again');

            },error:function (data) {
            // console.log(data);
        }});
    });


    $('#genmerchant1').click(function(){
        var memberId = $('#memberId').val();
        $.ajax({
            type: 'get',
            url: APP_URL + '/merchant/payment/check-merchant',
            data: {
                memberId: memberId
            },
            success: function(data) {
                if(data.status == true){
                    $('#memberName').val(data.name);
                    swal('System found the match');
                    return false;
                }
                $('#memberName').val('');
                swal('System could not match username. Please check again');

            },error:function (data) {
                // console.log(data);
            }});
    });

});

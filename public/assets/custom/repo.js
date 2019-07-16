// global document redy functions 
$(function () {

    // invalid html5 fix for fixed headerd
    var delay = 0;
    var offset = 200;

    document.addEventListener('invalid', function (e) {
        $(e.target).addClass("invalid");
        $('html, body').animate({scrollTop: $($(".invalid")[0]).offset().top - offset}, delay);
    }, true);
    document.addEventListener('change', function (e) {
        $(e.target).removeClass("invalid")
    }, true);


    // var idleTime = 0;
    // $(document).ready(function () {
    //     //Increment the idle time counter every minute.
    //     var idleInterval = setInterval(timerIncrement, 60000); // 1 minute
    //
    //     //Zero the idle timer on mouse movement.
    //     $(this).mousemove(function (e) {
    //         idleTime = 0;
    //     });
    //     $(this).keypress(function (e) {
    //         idleTime = 0;
    //     });
    // });
    //
    // function timerIncrement() {
    //     idleTime = idleTime + 1;
    //     if (idleTime > 9) { // expire in 3 minutes
    //         location.href="/member/login";
    //     }
    // }

    // Make menu active based on current navigation
    var baseurl = window.location.origin;
    var pgurl = window.location.href;

    $("ul.nav a").each(function () {
        if ($(this).attr("href") == pgurl || ((baseurl + "/") == pgurl && pgurl == ($(this).attr("href") + "/"))) {
            $(this).parent('li').addClass("active");
            $(this).closest('ul.dropdown-menu').addClass('active');
            $(this).closest('ul.dropdown-menu').parent().closest('li.menu-dropdown').addClass('active');
            return false;
        }


    })

    $('#startdate, #enddate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        // startDate: '',
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    $('#startdate, #enddate').on('apply.daterangepicker', function (ev, picker) {
        // $(this).val(picker.startDate.format('MM-DD-YYYY'));
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
    });

    $('#startdate, #enddate').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    var currentTime = new Date();
    $('input[name="dob"].datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 22),
        maxDate: moment().format('DD-MM') + '-' + Number(moment().format('YYYY') - 18),
        locale: {
            format: 'DD-MM-YYYY',
        },
    }, function (start, end, label) {
        var years = moment().diff(start, 'years');
        if (years < 18) {
            swal("You must be at least 18 years old.");
        }
    });
    // $('input[name="dob"].datepicker').data('daterangepicker').setStartDate('22-06-2000');

    //restricting all the number input from entering negative values
    $("body").on("keydown", "input[type=number]", function (event) {
        if (event.shiftKey) {
            event.preventDefault();
        }

        if (event.keyCode == 46 || event.keyCode == 8) {
        }
        else {
            if (event.keyCode < 95) {
                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                }
            }
            else {
                if (event.keyCode < 96 || event.keyCode > 105) {
                    event.preventDefault();
                }
            }
        }
    });
//disabled profile in view profile 
    if (getPageUrl().toLowerCase().includes('user/my-profile')) {
        $("body :input").attr("disabled", true);
        $('button.btn.blue[type=submit]').hide();
        $('div.page-title h1').html('My Profile')
    }

})
//glbal document redy ends here 


//Ajax call
function performAjaxCall(url, method, data, successCallBack) {
    var csrfToken = getCSRFToken();

    $.ajax({
        url: APP_URL + url,
        method: method,
        data: data,
        dataType: "json",
        beforeSend: function (request) {
            request.setRequestHeader('Csrf-Token', csrfToken);
        },
        success: function (result) {
            successCallBack(result);
        }

    })
        .done(function (data) {


        });
}

//full page loader
function getCSRFToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function showFullPageLoader() {
    $('.page-load-new').fadeIn()
}

function hideFullPageLoader() {
    $('.page-load-new').fadeOut(100)
}

//get page url
function getPageUrl() {
    var fullUrl = window.location.href.split("/");
    var route = "";
    $.each(fullUrl, function (index, value) {
        if (index > 2) {
            route = ((route == "") ? "" : (route + "/")) + value;
        }

    });
    return route;

}

//show message in popupr
function showPopupMessage(Message, headding = "Message", isError = false) {
    if (!isError) {
        swal(headding, Message);

    }
    else {
        swal({
            type: 'error',
            title: headding,
            text: Message,
        });
    }
    // $('#responsivePopupMessage h4.modal-title').html(headding);
    // $('#responsivePopupMessage h4.message1').html(Message);
    // $('#responsivePopupMessage').fadeIn();

}


//show message in popup
function hidePopupMessage() {
    swal.close();

    // $('#responsivePopupMessage').fadeOut(function(){
    //   $('#responsivePopupMessage h4.modal-title').html("");
    //   $('#responsivePopupMessage h4.message1').html("");
    // });

}


//for payment maker transaction
function getTransactionPostUrl() {
    var loc = window.location.href.split('/');
    var uri = "/" + loc[loc.length - 4] + "/" + loc[loc.length - 3] + "/" + loc[loc.length - 2] + "/" + loc[loc.length - 1];
    return uri;
}

//

//confirming secondary pin 
function confirmSecondaryPin(successCallBack) {
    // if (!$('.ajax-post.no-secondary-password').length) {
    //     hideFullPageLoader();
    //     swal({
    //         title: "Do you want to proceed",
    //         text: "Please enter your Transaction Password:",
    //         type: "input",
    //         inputType: "password",
    //         showCancelButton: true,
    //         closeOnConfirm: false,
    //         inputPlaceholder: "Transaction Password"
    //     }, function (inputValue) {
    //         if (inputValue === false) return false;
    //         if (inputValue === "") {
    //             swal.showInputError("You need to write something!");
    //             return false
    //         }
    //         var token = getCSRFToken();
    //         var data = {
    //             _token: token,
    //             transactionpassword: inputValue
    //         }
    //         performAjaxCall('/member/confirm-transaction-password', 'POST', data, function (response) {
    //             if (response == true) {
    //                 swal.close();
    //                 setTimeout(function () {
    //                     successCallBack();
    //                     hideFullPageLoader();
    //                 }, 300);
    //
    //             } else {
    //                 swal.showInputError("Wrong Transaction Password!");
    //
    //                 //swal.close();
    //                 // setTimeout(function(){
    //                 // showPopupMessage('Worng Transaction Password','Error',true);
    //                 // },300);
    //             }
    //         });
    //     });
    // } else {
        successCallBack();
    // }

}


//are you sure confirmation
function areYouSure(successCallBack) {
    var areYouSureMessage = $('#are-you-sure-message').html();
    if (areYouSureMessage === undefined)
        areYouSureMessage = '';
    // console.log(areYouSureMessage);
    swal({
        title: "Do you want to proceed?",
        showCancelButton: true,
        closeOnConfirm: false,
        // type: 'info',
        text: areYouSureMessage,
    }, function () {
        swal.close();
        setTimeout(function () {
            successCallBack();
        }, 300);
    });
}
function areYouSureGrantRetain(successCallBack,msg) {

    swal({
        title: "Do you want to proceed?",
        showCancelButton: true,
        closeOnConfirm: false,
        // type: 'info',
        text: msg,
    }, function () {
        swal.close();
        setTimeout(function () {
            successCallBack();
        }, 300);
    });
}

//confirming secondary pin
function confirmSecondaryPinMerchant(successCallBack) {
    // if (!$('.ajax-post-merchant.no-secondary-password').length) {
    //     hideFullPageLoader();
    //     swal({
    //         title: "Do you want to proceed",
    //         text: "Please enter your Transaction Password:",
    //         type: "input",
    //         inputType: "password",
    //         showCancelButton: true,
    //         closeOnConfirm: false,
    //         inputPlaceholder: "Transaction Password"
    //     }, function (inputValue) {
    //         if (inputValue === false) return false;
    //         if (inputValue === "") {
    //             swal.showInputError("You need to write something!");
    //             return false
    //         }
    //         var token = getCSRFToken();
    //         var data = {
    //             _token: token,
    //             transactionpassword: inputValue
    //         }
    //         performAjaxCall('/merchant/confirm-transaction-password', 'POST', data, function (response) {
    //             if (response == true) {
    //                 swal.close();
    //                 setTimeout(function () {
    //                     successCallBack();
    //                     hideFullPageLoader();
    //                 }, 300);
    //
    //             } else {
    //                 swal.showInputError("Wrong Transaction Password!");
    //
    //                 //swal.close();
    //                 // setTimeout(function(){
    //                 // showPopupMessage('Worng Transaction Password','Error',true);
    //                 // },300);
    //             }
    //         });
    //     });
    // } else {
        successCallBack();
    // }

}

function confirmSecondaryPinAdmin(successCallBack) {
    // if (!$('.ajax-post-merchant.no-secondary-password').length) {
    //     hideFullPageLoader();
    //     swal({
    //         title: "Do you want to proceed",
    //         text: "Please enter your Transaction Password:",
    //         type: "input",
    //         inputType: "password",
    //         showCancelButton: true,
    //         closeOnConfirm: false,
    //         inputPlaceholder: "Transaction Password"
    //     }, function (inputValue) {
    //         if (inputValue === false) return false;
    //         if (inputValue === "") {
    //             swal.showInputError("You need to write something!");
    //             return false
    //         }
    //         var token = getCSRFToken();
    //         var data = {
    //             _token: token,
    //             transactionpassword: inputValue
    //         }
    //         performAjaxCall('/admin/confirm-transaction-password', 'POST', data, function (response) {
    //             if (response == true) {
    //                 swal.close();
    //                 setTimeout(function () {
    //                     successCallBack();
    //                     hideFullPageLoader();
    //                 }, 300);
    //
    //             } else {
    //                 swal.showInputError("Wrong Transaction Password!");
    //
    //                 //swal.close();
    //                 // setTimeout(function(){
    //                 // showPopupMessage('Worng Transaction Password','Error',true);
    //                 // },300);
    //             }
    //         });
    //     });
    // } else {
        successCallBack();
    // }

}





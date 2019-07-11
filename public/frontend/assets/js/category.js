var category = document.querySelector("#category")
var dropdown = document.querySelector("#dropdown")
var h3Category = document.querySelector("#h3Category")
var liWithDropdown = document.getElementsByClassName("liWithDropdown")
var level1 = document.getElementsByClassName("level1")
var liWithDropdown1 = document.getElementsByClassName("liWithDropdown1")


var stikyCatBoxes = document.getElementById("stikyBoxes");

//     //for stiky box on category page
window.onscroll = function () {
    if (stikyCatBoxes !== null) {
        stickBoxesCategory()
    }
};

if (stikyCatBoxes !== null) {
    var sticky = stikyCatBoxes.offsetTop;

    function stickBoxesCategory() {
        if (window.pageYOffset >= sticky) {
            stikyCatBoxes.classList.add("sticky")
        } else {
            stikyCatBoxes.classList.remove("sticky");
        }
    }
}

if (category !== null) {
    category.addEventListener("mouseover", function () {
        dropdown.style.display = "block";
    })
    category.addEventListener("mouseout", function () {
        dropdown.style.display = "none";

    })
}
for (i = 0; i < liWithDropdown.length; i++) {
    liWithDropdown[i].addEventListener("mouseover", function () {
        this.querySelector(".level1").style.display = "block"
    })
}

for (i = 0; i < liWithDropdown.length; i++) {
    liWithDropdown[i].addEventListener("mouseout", function () {
        this.querySelector(".level1").style.display = "none"
    })
}

for (i = 0; i < liWithDropdown1.length; i++) {
    liWithDropdown1[i].addEventListener("mouseover", function () {
        this.querySelector(".level2").style.display = "block"
    })
}

for (i = 0; i < liWithDropdown1.length; i++) {
    liWithDropdown1[i].addEventListener("mouseout", function () {
        this.querySelector(".level2").style.display = "none"
    })
}


$('.topCategory').on('click', function () {
    var cat_id = $(this).data('id');
    var pos = $('.listCategory-' + cat_id).offset().top;
    $(document).scrollTop(pos - 150);
});


//subscribe js

$(function () {
    $('#sub-submit').on('click', function (e) {
        e.preventDefault();
        var email = $('#sub-email').val();
        $.get(serverCustom.base_url + '/save-subscriber',
            {sub_email: email},
            function (data) {
                var sub_message = $('#subscribe-message');
                if (data.status === true) {
                    sub_message.removeClass('fade').addClass('border-sub-green').html(data.message);
                } else {
                    sub_message.removeClass('fade').addClass('border-sub-red').html(data.message);
                }
                setTimeout(function () {
                    sub_message.addClass('fade').removeClass(['border-sub-red', 'border-sub-green'])
                }, 4000);

            }
        );
    });
});

$(function () {
    $('.number-min-max').on('change', function () {
        var _this = $(this);
        var min = 0; // if min attribute is not defined, 1 is default
        var max = parseInt($('.max-value').val()) || 0; // if max attribute is not defined, 100 is default
        var val = parseInt(_this.val()) || (min - 1); // if input char is not a number the value will be (min - 1) so first condition will be true
        if (val < min)
            _this.val(min);
        if (val > max)
            _this.val(max);
        // console.log(min, max, val);
    });


    $('.trigger-image-zoom').on('click', function () {
        var colorId = $(this).data('color_id');
        $('#action-color-' + colorId).click();
    });
});
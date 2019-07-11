$(function () {

    var active_toggle = $('#toggle-active');
    var category_id = active_toggle.data('category');
    $('.trigger-category-' + category_id).removeClass('fa-plus').addClass('fa-minus');
    $('.category-' + category_id).removeClass('hide');

    var subcategory_id = active_toggle.data('subcategory');
    $('.trigger-subcategory-' + subcategory_id).removeClass('fa-plus').addClass('fa-minus');
    $('.subcategory-' + subcategory_id).removeClass('hide');


    $('.has-sub').on('click', function (e) {
        e.stopPropagation();
        var checkClass = $(this).hasClass('fa-plus');
        var subclass = $(this).data('subclass');

        if (checkClass) {
            $(this).removeClass('fa-plus').addClass('fa-minus');
            $('.' + subclass).removeClass('hide');
        } else {
            $(this).removeClass('fa-minus').addClass('fa-plus');

            $('.' + subclass).addClass('hide');
            $('.minus-' + subclass).addClass('hide');
            $('.plus-' + subclass).removeClass('fa-minus').addClass('fa-plus');
        }
    });


    $('.add-modal').on('click', function () {
        var type = $(this).data('type');
        var obj = $(this).data('obj');
        $('#modal-id').val(obj.id);
        $('#modal-type').html(type);
        $('#modal-type-1').val(type);
        $('#modal-name').html(obj.eng_name);
    });

    $('.edit-modal').on('click', function () {
        var type = $(this).data('type');
        var obj = $(this).data('obj');
        // console.log(obj);
        $('#edit-modal-id').val(obj.id);
        $('#edit-modal-type').html(type);
        $('#edit-modal-type-1').val(type);
        $('#edit-modal-name').val(obj.eng_name);
        $('#edit-modal-ch-name').val(obj.ch_name);
        $('#edit-modal-trch-name').val(obj.trch_name);
        $('#edit-modal-image').empty();
        if (obj.image !== null) {
            var image = "<span>previous image :</span><br><img src='" + window.location.origin + "/image/admin/category/" + obj.image + "' alt='image' height='150px'>";
            $('#edit-modal-image').append(image);
        }
    });

});
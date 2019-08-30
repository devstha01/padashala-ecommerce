//    image area select
//    Product add edit
$(function () {
    $('input[name="x1"]').val(0);
    $('input[name="y1"]').val(0);
    $('input[name="h1"]').val(300);
    $('input[name="w1"]').val(300);

    var p = $("#previewimage");
    $("body").on("change", ".image", function () {
        var imageReader = new FileReader();
        imageReader.readAsDataURL(document.querySelector(".image").files[0]);

        imageReader.onload = function (oFREvent) {
            p.attr('src', oFREvent.target.result).fadeIn();
        };
    });


    $('#previewimage').imgAreaSelect({
        // fadeSpeed : 1,
        show: true,
        handles: true,
        minHeight: 300,
        minWidth: 300,
        aspectRatio: '800:800',
        onSelectEnd: function (img, selection) {
            $('input[name="x1"]').val(maintainRationOnResize(selection.x1, img.naturalWidth));
            $('input[name="y1"]').val(maintainRationOnResize(selection.y1, img.naturalWidth));
            $('input[name="h1"]').val(maintainRationOnResize(selection.height, img.naturalWidth));
            $('input[name="w1"]').val(maintainRationOnResize(selection.width, img.naturalWidth));
        }
    });

    function maintainRationOnResize(correction, standard) {
        return parseInt((correction * standard) / 800);
    }
});

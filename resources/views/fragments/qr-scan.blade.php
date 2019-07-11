<br>
<div class="row" style="min-height:200px">
    <div class="col-sm-5">
        <br>
        <button type="submit" class="btn" id="open-scanner">{{__('front.Open QR Scanner')}}</button>
        <button type="submit" class="btn" id="close-scanner"
                style="display: none">{{__('front.Close Scanner')}}</button>
        <br>
        <br>
        <span class="error-message" id="error-scanner"></span>
        <span id="success-scanner" style="color:green"></span>
    </div>
    <div class="col-sm-7">
        <video id="preview-qr" style="display: none;height:200px;width:240px;margin-top:-10px;padding:15px;background: gainsboro"></video>
    </div>
</div>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    let scanner = new Instascan.Scanner({video: document.getElementById('preview-qr')});

    $('#open-scanner').on('click', function () {

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                $('#error-scanner').html('');
                $('#preview-qr').show();
                $('#close-scanner').show();
                $('#open-scanner').hide();
            } else {
                $('#error-scanner').html('No cameras found.');
            }
        }).catch(function (e) {
            $('#error-scanner').html('Cannot access camera');
            // console.error(e);
        });
    });
    $('#close-scanner').on('click', function () {
        $('#preview-qr').hide();
        $('#open-scanner').show();
        $('#close-scanner').hide();
        scanner.stop();
    });


    scanner.addListener('scan', function (content) {
        sendAjaxQR(content);
    });

    function sendAjaxQR(image_data) {
        $.ajax({
            url: qr_scan_url,
            cache: false,
            method: 'POST',
            data: {qr_data: image_data, _token: "{!! csrf_token() !!}"},
            success: function (response) {
                if (response.status === true) {
                    $('#preview-qr').hide();

                    $('#open-scanner').show();
                    $('#close-scanner').hide();
                    $('#error-scanner').html('');
                    $('#success-scanner').html(response.success);
                    $('.response-qr-user_name').val(response.user_name);
                    scanner.stop();
                } else {
                    $('#error-scanner').html(response.error);
                    $('.response-qr-user_name').val('');
                    $('#success-scanner').html('');
                }
            }
        });

    }


</script>
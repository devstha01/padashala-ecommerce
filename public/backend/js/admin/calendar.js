function selectDateCalendar(dateInfo) {

    $.ajax({
        url: serverCustom.base_url + '/admin/config/check-calendar-holiday',
        data: {'date': dateInfo.format()},
        success: function (response) {
            if (response.exist === true) {
                swal({
                    title: "Do you want to proceed",
                    text: "Add holiday " + dateInfo.format(),
                    showCancelButton: true,
                    closeOnConfirm: false,
                }, function (inputValue) {
                    if (inputValue === false) return false;
                    else {
                        $.ajax({
                            url: serverCustom.base_url + '/admin/config/add-calendar-holiday',
                            data: {'date': dateInfo.format()},
                            success: function (response) {
                                if (response.status === true) {
                                    window.location.replace(serverCustom.base_url + '/admin/config/holiday-dates?date=' + dateInfo.format())
                                }
                            }
                        });
                    }
                });
            } else {
                return false;
            }
        }
    });
}

//
function removeDateCalendar(dateInfo) {
    swal({
        title: "Do you want to proceed",
        text: "Remove holiday " + dateInfo.start.format(),
        showCancelButton: true,
        closeOnConfirm: false,
    }, function (inputValue) {
        if (inputValue === false) return false;
        else {
            $.ajax({
                url: serverCustom.base_url + '/admin/config/remove-calendar-holiday',
                data: {'id': dateInfo.id},
                success: function (response) {
                    if (response.status === true) {
                        window.location.replace(serverCustom.base_url + '/admin/config/holiday-dates?date=' + dateInfo.start.format())
                    }
                }
            });
        }
    });
}
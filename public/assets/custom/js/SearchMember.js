$(function () {
    if (getPageUrl().toLowerCase().includes("admin/members")) {
        $('#searchmember').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (validateStaffFilter()) {

                var data = {
                    FirstName: $('#FirstName').val(),
                    SurName: $('#SurName').val(),
                    loginid: $('#loginid').val(),
                    IDPassport: $('#IDPassport').val(),
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                }
                showFullPageLoader();
                performAjaxCall('/searchmember', 'GET', data, function (response) {
                    //debugger;
                    //console.log(response);
                    hideFullPageLoader();
                    var htm = '';
                    // var position = "";
                    if (response.length != 0) {
                        $.each(response, function (index, value) {
                            // position = (value.position != null)?value.position.position:"-";
                            htm = htm + `
                            <tr role="` + ((index % 2 == 0) ? "even" : "odd") + `" class="even">
                            <td class="sorting_1">` + (index + 1) + `</td>
                            <td>` + value.name + `</td>
                            <td>
                            ` + value.identification_type.identification_name + `(` + value.identification_number + `)` + `
                            </td>
                            <td>
                            ` + value.user_name + `
                            </td>
                            <td>
                            ` + value.address + `
                            </td>   
                            <td>
                            ` + value.email + `
                            </td>       
                            <td>
                            ` + value.created_at + `
                            </td>    
                            <td>
                                <a type="button" class="btn btn-primary ml-action" href="` + base_url + `/admin/view-member/` + value.id + `"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a type="button" class="btn btn-primary ml-action" href="` + base_url + `/admin/edit-member/` + value.id + `"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                ` + ((value.status == 0) ?
                                    `
                                    <form action="` + base_url + `/admin/member-activation/` + value.id + `"
                                            method="POST" class="activate-form ml-action">
                                            <input name="_token" value=` + getCSRFToken() + ` type="hidden">
                                    <button type="submit" class="btn btn-sm btn-success ml-action-btn">
                                      <i class="fa fa-check" aria-hidden="true"></i>
                                    </button>
                                    </form>
                                    `
                                    :
                                    `
                                    <form action="` + base_url + `/admin/member-activation/` + value.id + `"
                                    method="POST" class="deactivate-form ml-action">
                                    <input name="_token" value=` + getCSRFToken() + ` type="hidden">
                                        <button type="submit" class="btn btn-sm btn-danger ml-action-btn">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                     </form>
        
                                    `
                                ) +
                                `
                                <br><a type="button" class="btn btn-success ml-edit-btn" href="\` + base_url + \`/admin/member-credentials-edit/\` + value.id + \`"><i>Edit Credentials</i></a>                            </td>
                        </tr>
                            `;
                        });
                        //console.log(htm);
                        // var table = $('#article').DataTable();
                        // table
                        //     .clear()
                        //     .draw();
                        // $("#dailyNews").dataTable().fnDestroy()

                        // $("#dailyNews").dataTable({
                        //     // ... skipped ...
                        // });
                        $('#article').DataTable().destroy();
                        $('#article_length, #article_filter').hide();
                        $('#article tbody').html(htm);
                        $('#article').DataTable().draw();
                    } else {
                        showPopupMessage('No resutl found', 'Message', true);
                    }


                });
            } else {
                showPopupMessage("Please Enter atleast one search filter", "Error", true);
            }

        });

        $('#stafffilter input').on('keypress', function (e) {
            if (e.keyCode == 13) {
                $('#searchstaff').click();

            }
        })

    }

});


function validateStaffFilter() {
    //only checks if there exist any value in staff search filter.
    if ($('#FirstName').val() != "") return true;
    else if ($('#SurName').val() != "") return true;
    else if ($('#loginid').val() != "") return true;
    else if ($('#IDPassport').val() != "") return true;
    else if ($('#startdate').val() != "") return true;
    else if ($('#enddate').val() != "") return true;
    else return false;
}
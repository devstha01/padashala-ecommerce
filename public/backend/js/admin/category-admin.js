$(function () {
    //get sub category list
    $('.get-sub-category').on('click', function (e) {
        $('.get-sub-category').removeClass('custom-category-bg');
        $(this).addClass('custom-category-bg');
        var category_id = $(this).data('id');
        var base_url = serverCustom.base_url;
        $.ajax({
            // type: "POST",
            url: base_url + '/admin/category/sub-category',
            data: {'category_id': category_id},
            success: function (data) {
                if (data.status) {
                    var sub_category_body = $('#sub-category');
                    sub_category_body.find("tr:gt(0)").remove();
                    // sub_category_body.empty();
                    if (data.count) {
                        for (var i = 0; i < data.sub_categories.length; i++) {
                            var sub_category = data.sub_categories[i];
                            var sub_category_row = "<tr class='get-sub-child-category' data-id='" + sub_category.id + "'>\n" +
                                "<td> " + (i + 1) + "</td><td>" + sub_category.name + "</td><td>\n" +
                                "<a href='" + base_url + "/admin/category/edit-sub-category?id=" + sub_category.id + "' class=\"fa fa-edit\"></a>\n" +
                                "</td><td><a href='" + base_url + "/admin/category/delete-sub-category?id=" + sub_category.id + "' class=\"fa fa-trash text-danger\"></a>\n" +
                                "</td></tr>";
                            sub_category_body.append(sub_category_row);
                        }
                    } else {
                        sub_category_body.append("<tr><td colspan='2'>No sub-categories available.</td></tr>");
                    }
                    var sub_input = "<tr><td colspan='2'><div class='form-group'><div class='input-group input-group-sm'>\n" +
                        "<input type='text' class='form-control'><span class='input-group-btn'>\n" +
                        "<button data-category='" + data.category_id + "' class='btn green' type='button'>Add Sub-Category</button>\n" +
                        "</span></div></div></td></tr>";
                    sub_category_body.append(sub_input);
                } else {
                    sub_category_body.append("<tr><td colspan='2'>Invalid category selected.</td></tr>");
                }
                sub_child_category_accumulate();
            }
        });
    });

    function sub_child_category_accumulate() {
        //get sub child category list
        $('.get-sub-child-category').on('click', function (e) {
            $('.get-sub-child-category').removeClass('custom-category-bg');
            $(this).addClass('custom-category-bg');
            var sub_category_id = $(this).data('id');
            var base_url = serverCustom.base_url;
            $.ajax({
                // type: "POST",
                url: base_url + '/admin/category/sub-child-category',
                data: {'sub_category_id': sub_category_id},
                success: function (data) {
                    if (data.status) {
                        var sub_child_category_body = $('#sub-child-category');
                        sub_child_category_body.find("tr:gt(0)").remove();
                        if (data.count) {
                            for (var i = 0; i < data.sub_child_categories.length; i++) {
                                var sub_child_category = data.sub_child_categories[i];
                                var sub_child_category_row = "<tr class='info-sub-child-category' data-id='" + sub_child_category.id + "'>\n" +
                                    "<td> " + (i + 1) + "</td><td>" + sub_child_category.name + "</td><td>\n" +
                                    "<a href='" + base_url + "/admin/category/edit-sub-child-category?id=" + sub_child_category.id + "' class=\"fa fa-edit\"></a>\n" +
                                    "</td><td><a href='" + base_url + "/admin/category/delete-sub-child-category?id=" + sub_child_category.id + "' class=\"fa fa-trash text-danger\"></a>\n" +
                                    "</td></tr>";
                                sub_child_category_body.append(sub_child_category_row);
                            }
                        } else {
                            sub_child_category_body.append("<tr><td colspan='2'>No sub-child-categories available.</td></tr>");
                        }
                        var sub_child_input = "<tr><td colspan='2'><div class='form-group'><div class='input-group input-group-sm'>\n" +
                            "<input type='text' class='form-control'><span class='input-group-btn'>\n" +
                            "<button data-category='" + data.sub_category_id + "' class='btn green' type='button'>Add Sub-Child-Category</button>\n" +
                            "</span></div></div></td></tr>";
                        sub_child_category_body.append(sub_child_input);
                    } else {
                        sub_child_category_body.append("<tr><td colspan='2'>Invalid sub-category selected.</td></tr>");
                    }
                }
            });
        });
    }
});
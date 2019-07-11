var currentUserId;
//get user roll
//this silply gets user roll from url
function getUserRegisterUrl() {
    var userType = $("#binaryTreeContainer").attr("userid");
    if (userType == 'admin') {
        return '/admin/add-new-member';

    }
    else {
        return '/user/register';

    }

}

function hideFouthNodes()
{
    $('#binaryTreeContainer table table table table tr.lines').hide();
    $('#binaryTreeContainer table table table table tr.nodes').hide();
}


function getObjectById(userTreeArray, ObjectId) {
    var obj = null
    $.each(userTreeArray, function (index, value) {
        if (value.parent_id == ObjectId) {
            obj = value;
        }

    });
    return obj;
}
function getParentId(userTreeArray, ChildId) {
    var obj = null;
    $.each(userTreeArray, function (index, value) {
        if (value.left_user_id == ChildId || value.right_user_id == ChildId) {
            obj = value;
        }
    });

    return obj.parent_id;
}
var nodeTemplate = function (data) {
    if (data.name != 'add user') {
        return `
             <a class="avatar tooltip-new" data-username="${data.username}" data-parent="${data.parent_username}" data-toggle="tooltip" title="<div style='text-align:center;'><img src='/assets/custom/img/user-${data.package}.png' width='100' height='100' />
             </div><div style='text-align: center;'>${data.username} </div>
             <div style='text-align: center;'>${data.package} </div>
             <table border='1' cell-spacing= '0'>
             <tr><td></td><td style='padding: 5px 10px; text-align: center;'>L</td><td style='padding: 5px 10px; text-align: center;' >R</td></tr>
            <tr>
                <td style='padding: 5px 10px; text-align: center;'>Total : </td>
                <td style='padding: 5px 10px; text-align: center;'>${data.leftbalance}</td>
                <td style='padding: 5px 10px; text-align: center;'>${data.rightbalance}</td>
            </tr>
            <tr>
                <td style='padding: 5px 10px; text-align: center;'>Balance : </td>
                <td style='padding: 5px 10px; text-align: center;'>${data.currentleft}</td>
                <td style='padding: 5px 10px; text-align: center;'>${data.currentright}</td>
            </tr>
            <tr>
                <td style='padding: 5px 10px; text-align: center;'>Register Date : </td>
                <td colspan='2' style='padding: 5px 10px; text-align: center;'>${data.registreddate}</td>
            </tr>
        
             </table>
             "><img src="/assets/custom/img/user-${data.package}.png"/>
             </a>
             <div class="title ${data.package}">${data.username}</div>
             `;
    }
    else {
        if (data.direction != undefined) {
            return `<a href="` + getUserRegisterUrl() + `?parentid=${data.parent_id}&direction=${data.direction}" class="avatar2"><img src="/assets/custom/img/images.png"/></a>`;
        } else {
            return '';
            // return '<a class="avatar more-users" href="#"><img src="/assets/custom/img/users-group.png"/></a>';
        }
        ;
    }
};

function CreateBinaryTree(userTreeArray, rootId) {
    var rootObj = getObjectById(userTreeArray, rootId);
    var parentId;
    var string = {
        'name': 'add user',
        'title': 'general manager',
        'office': '白城'
    }

    if (rootObj != null) {
        string = {
            'parent_id': rootObj.parent_id,
            'parent_username': rootObj.parent_username,
            'name': rootObj.name,
            'package': rootObj.package,
            'username': rootObj.username,
            'package_id': rootObj.package_id,
            'totalchild': rootObj.totalchild,
            'noofleftchild': rootObj.noofleftchild,
            'noofrightchild': rootObj.noofrightchild,
            'leftbalance': rootObj.leftbalance,
            'rightbalance': rootObj.rightbalance,
            'registreddate': rootObj.registreddate,
            'currentleft': ( rootObj.leftbalance - rootObj.rightbalance > 0) ?
                ( rootObj.leftbalance - rootObj.rightbalance) :
                0,
            'currentright': ( rootObj.rightbalance - rootObj.leftbalance > 0) ?
                ( rootObj.rightbalance - rootObj.leftbalance) :
                0,

            'children': [(rootObj.left_user_id != null)
                ? CreateBinaryTree(userTreeArray, rootObj.left_user_id)
                : {
                'name': 'add user',
                'parent_id': rootObj.parent_id,
                'direction': 'left'
            },
                (rootObj.right_user_id != null)
                    ? CreateBinaryTree(userTreeArray, rootObj.right_user_id)
                    : {
                    'name': 'add user',
                    'parent_id': rootObj.parent_id,
                    'direction': 'right'
                }
            ]
        }
    } else {
        parentId = getParentId(userTreeArray, rootId);
        string = {
            'name': 'add user',
            'parent_id': parentId
        }
    }

    return string;
}


function getUserTree() {

    if ($('#username').val() == "") {
        showPopupMessage('Please Enter Username', 'Message', true);
    }
    else {
        showFullPageLoader();
        var data = {
            username: $('#username').val()
        }
        performAjaxCall('/isuser', 'GET', data, function (userId) {
            if (userId != false) {
                var data = {
                    username: $('#username').val()
                }
                performAjaxCall('/genealogy', 'GET', data, function (response) {
                    $("#binaryTreeContainer").html("");
                    var st = CreateBinaryTree(response, userId);
                    // debugger;
                    // console.log(st);
                    //var responseJson = eval(response);
                    $('#binaryTreeContainer').orgchart({
                        'data': st,
                        //'nodeContent': 'title'
                        'nodeTemplate': nodeTemplate,
                        initCompleted: function () {
                            hideFouthNodes();
                            hideFullPageLoader();
                            listenPlacementTreeClick();
                        }
                    });
                })
            }
            else {
                showPopupMessage('User does not exist', 'Message', true);
                hideFullPageLoader();
            }

        })


    }


}

function listenPlacementTreeClick() {
    $('.tree-container .node .avatar').on('click', function () {
        var loadTree = true;

        if ($(this).attr('data-username') === undefined) {
            return false;
        }

        if (currentUserId == $(this).attr('data-username')) {
            loadTree = false;
        }

        if (loadTree) {
            if ($('#username').val() == $(this).attr('data-username')) {
                $('#username').val($(this).attr('data-parent'));
            } else {
                $('#username').val($(this).attr('data-username'));
                $('#gotoparent').attr('data-parent', $(this).attr('data-parent'));
            }
            getUserTree();
        }
    });
}

function refreshLoadParentButton() {
    if (currentUserId != 'admin') {
        // console.log(currentUserId);
        // console.log($(this).attr('data-parent'));
        // console.log($(this).attr('data-username'));

        $('#gotoparent').show();
        // if($('#gotoparent').attr('data-parent') == currentUserId){
        //     $('#gotoparent').hide();
        // }else{
        //     $('#gotoparent').show();
        // }
        // if($(this).attr('data-username') != currentUserId){
        //     $('#gotoparent').show();
        // }else{
        //     $('#gotoparent').hide();
        // }

    } else {
        // hide go to parent if admin
        if ($('#username').val() == 'admin') {
            $('#gotoparent').hide();
        } else {
            $('#gotoparent').show();
        }
    }
}

$(function () {
    // Refresh Placement tree on click


    if (getPageUrl().toLowerCase().includes("user/placement-tree")) {
        $('#gotoparent').on('click', function () {
            $('#username').val($('a.avatar:first').attr('data-parent'));
            getUserTree();
        })
        $(document).tooltip({
            content: function () {
                return $(this).prop('title');
            }
        });

        currentUserId = $("#binaryTreeContainer").attr("userid");
        if (currentUserId != "admin") {
            $('#username').val(currentUserId);
            getUserTree();
            // var data = {
            //     userid : currentUserId
            // }
            // performAjaxCall('/genealogy','GET',data,function(response){
            //     window.userRegisterUrl = getUserRegisterUrl();
            //     var st = CreateBinaryTree(response,currentUserId);
            //     //var responseJson = eval(response);
            //     $('#binaryTreeContainer').orgchart({
            //         'data' : st,
            //         //'nodeContent': 'title'
            //         'nodeTemplate': nodeTemplate
            //       });
            //
            // })

        }
    }


    //event for admin to search tree with user node
    $('#gettree').on('click', function () {
        window.userRegisterUrl = getUserRegisterUrl();
        getUserTree();
    });

    //event for admin to search tree with user node with enter
    $('#username').on('keypress', function (e) {
        if (e.keyCode == 13) {
            window.userRegisterUrl = getUserRegisterUrl();
            getUserTree();
        }
    });


})
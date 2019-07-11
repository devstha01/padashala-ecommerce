@extends('backend.layouts.master')

@push('styles')
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial;
            font-size: 14px;
            line-height: 1.428571429;
            color: #333333;
        }

        #chart-container {
            position: relative;
            display: inline-block;
            top: 10px;
            left: 10px;
            height: 420px;
            width: calc(100% - 24px);
            border: 2px dashed #aaa;
            border-radius: 5px;
            overflow: auto;
            text-align: center;
            border: 0;
        }

        .orgchart .node .title {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            height: 20px;
            line-height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background-color: rgb(212, 164, 104);
            color: #fff;
            border-radius: 4px 4px 0 0;
        }

        .orgchart .node .content {
            height: 2px !important;
        }

        .orgchart {
            background: #fff;
        }

        .orgchart .Gold .title {
            /*background-color: #b48610;*/
            background: linear-gradient(to bottom, #FCDA6B, #B98D57);
        }

        .orgchart .Gold .content {
            border-color: #b48610;
        }

        .orgchart .Platinum .title {
            /*background-color: #a43f51;*/
            background: linear-gradient(to bottom, #FFFFFF, #E2E3E5);
        }

        .orgchart .Platinum .content {
            border-color: #a43f51;
        }

        .orgchart .Diamond .title {
            /*background-color: #007e82;*/
            background: linear-gradient(to bottom, #65B0FD , #5A6672);
        }

        .orgchart .Diamond .content {
            border-color: #007e82;
        }

        .orgchart .node .edge {
            font-size: 15px;
            position: absolute;
            color: rgba(68, 157, 68, .5);
            cursor: default;
            transition: .2s;
            visibility: hidden;
        }

        .orgchart .node:hover {
            background-color: transparent !important;
        }

        .orgchart .Platinum .title {
            width: 75px;
            overflow: hidden;
            margin-left: 0 !important;
            padding: 0 10px !important;
        }

        .orgchart .node {
            width: fit-content;
            margin: 0 3px !important;
            overflow: hidden;
            max-width: 95px !important;
        }

        .orgchart .Gold .title {
            width: 75px;
            overflow: hidden;
            margin-left: 0 !important;
            padding: 0 10px !important;
        }

        .orgchart .Default .title {
            width: fit-content !important;
            margin-left: 0 !important;
            margin: auto !important;
            padding: 0 10px;
        }

        .orgchart .Diamond .title {
            width: 75px;
            overflow: hidden;
            margin-left: 0 !important;
            padding: 0 10px !important;
        }

        .fa.fa-users.symbol {
            display: none;
        }

        .ui-helper-hidden-accessible {

            display: none !important
        }

        .ui-tooltip {
            position: absolute;
            background-color: white !important;
            z-index: 9999;
        }


    </style>
@endpush

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="container">
            <div class="page-head">
                <div class="container">
                    <div class="page-title">
                        <h1>{{__('dashboard.Auto Placement Tree')}}
                            <small></small>
                        </h1>
                    </div>
                </div>
            </div>

            @if(Auth::guard('admin')->check() && Request::is('admin/*'))
                @php  $username=$defaultMember->user_name;@endphp
            @else
                @php $username=\Auth::user()->user_name; @endphp
            @endif

            <div class="logo get-tree" style="">
                <input id="username" class="form-control" type="text" placeholder="Login Id" required="required"
                       value="{{$username}}">
                <button type="submit" id="gettree" class="btn green uppercase">{{__('dashboard.Search')}}</button>

            </div>


        </div>
    </div>
    <div id="chart-container"></div>

@stop
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#chart-container').hide();
            $(document).tooltip({
                position: {
                    my: "center bottom",
                    at: "center top-10",
                    collision: "flip",
                    using: function (position, feedback) {
                        $(this).addClass(feedback.vertical)
                            .css(position);
                    }
                },
                content: function () {
                    return $(this).prop('title');
                }
            });
        });

        $("#gettree").click(function (e) {
            e.preventDefault();
            getUserTree();

        });
        $('#username').on('keypress', function (e) {
            if (e.keyCode == 13) {
                getUserTree();
            }
        });

        var userName = '<?php echo $username; ?>';

        window.onload = function () {
            getUserTree(userName);
        };

        function listenPlacementTreeClick() {
            $('.memberData').on('click', function () {

                if ($(this).attr('data-username') === undefined) {
                    return false;
                }
                getUserTree($(this).attr('data-username'));

            });

        }

        function getUserTree(userName) {
            if (userName) {
                $('#username').val(userName);
            }

            if ($('#username').val() == "") {
                showPopupMessage('Please Enter Username', 'Message', true);
            }
            else {
                showFullPageLoader();
                var data = {
                    user_name: $('#username').val()
                }
                performAjaxCall('/member/ismember', 'GET', data, function (userId) {
                    if (userId != false) {
                        var data = {
                            username: $('#username').val()
                        }
                        performAjaxCall('/member/getAutoTree', 'GET', data, function (response) {
                            if (response != false) {
                                $('#chart-container').show();
                                $('#chart-container').html('');
                                $('#chart-container').orgchart({
                                    'data': response,
                                    'nodeContent': 'title',
                                    'nodeID': 'id',
                                    'nodeTemplate': function (data) {
                                        var dotted = '';
                                        if (data.name.length > 7) {
                                            dotted = '...';
                                        }
                                        var names = '';
                                        if (data.parent && data.parent != 'None') {
                                            names = data.parent;
                                        } else {
                                            names = data.name;
                                        }
                                        return `  <div class="title" data-toggle="tooltip"
                                             title="<div style='text-align:center;margin-left:100px !important'> </div>
                                       <table border='1' cell-spacing= '0'>
                                         <tr>
                                             <td colspan='2' style='padding: 5px 10px; text-align: center;'>${data.name}</td>
                                         </tr>
                                          <tr>
                                             <td style='padding: 5px 10px; text-align: center;'>Package : </td>
                                             <td colspan='2' style='padding: 5px 10px; text-align: center;'>${data.className}</td>
                                           </tr>
                                          <tr>
                                               <td style='padding: 5px 10px; text-align: center;'>Register Date : </td>
                                               <td colspan='2' style='padding: 5px 10px; text-align: center;'>${data.date}</td>
                                            </tr>
                                     </table>"> <a class="memberData avatar tooltip-new"

                                   data-username="${names}">${data.name.substring(0, 10) + dotted}</a></div>`;

                                    },
                                    initCompleted: function () {
                                        hideFullPageLoader();
                                        listenPlacementTreeClick();

                                    }

                                });
                            } else {
                                showPopupMessage('Member Not Found Under Your Spill', 'Message', true);
                                hideFullPageLoader();
                            }

                        })
                    }
                    else {
                        showPopupMessage('User does not exist', 'Message', true);
                        hideFullPageLoader();
                    }

                })


            }


        }
    </script>
@stop

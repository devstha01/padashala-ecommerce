@extends('backend.layouts.master')

@push('styles')
    <style>

        /*.demo { overflow:hidden; min-height:500px;margin-left: 25% }*/

        .demo {
            overflow: hidden;
            min-height: 500px;
            margin-left: 7%;
            margin-top: 1%;
        }
        /*/ custom.css 74 /*/
        .get-tree input {
            width: 170px;
            float: left;
        }
        /*/ custom:1156 /*/
        button.btn {
            max-width: 95px;
            width: 100%;
            text-transform: capitalize !important;
            border-radius: 0% 5px 5px 0 !important;
        }
        .get-tree input {
            width: 170px;
            float: left;
            border-radius: 5px 0 0 5px !important;
        }

    </style>
@endpush

@section('content')
    @if(Auth::guard('admin')->check() && Request::is('admin/*'))
        @include('backend.member.register.listForAdmin')
        @else
        <div class="page-wrapper-row full-height">
            <div class="container">
                <div class="page-head">
                    <div class="container">
                        <div class="page-title">
                            <h1>{{__('dashboard.Member Lists Tree')}}
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
                    <input id="username" class="form-control" type="text" placeholder="Login Id" required="required" value="{{$username}}">
                    <button type="submit" id="getList" class="btn green uppercase">{{__('dashboard.Search')}}</button>

                </div>

            </div>
            <br>

            <div id="data" class="demo"></div>

        </div>
    @endauth



@stop
@if(Auth::guard('admin')->check() && Request::is('admin/*'))
@section('scripts')

@stop
@else
@section('scripts')
    <script type="text/javascript">
        $(function() {
            $('#chart-container').hide();
        });

        var userName='<?php echo $username; ?>';

        window.onload = function() {
            getUserTree(userName);
        };

        $("#getList").click(function(e) {
            e.preventDefault();
            var userName=$('#username').val();
            getUserTree(userName);

        });
        $('#username').on('keypress', function (e) {
            if (e.keyCode == 13) {
                var userName=$('#username').val();
                getUserTree(userName);
            }
        });


        function getUserTree(userName) {
            if(userName){
                $('#username').val(userName);
            }
            if ($('#username').val() == "") {
                showPopupMessage('Please Enter Username', 'Message', true);
            }
            else {
                // showFullPageLoader();
                var data = {
                    user_name: $('#username').val()
                }
                performAjaxCall('/member/ismember', 'GET', data, function (userId) {
                    if (userId!= false) {
                        var data = {
                            username: $('#username').val()
                        }
                        performAjaxCall('/member/getMemberList', 'GET', data, function (response) {
                            if(response!= false){


                                $('#data').jstree({
                                    'core' : {
                                        'data' : response,
                                    }
                                }).bind("loaded.jstree", function () {
                                    // $.each(response, function (index, value) {
                                    //     if(value=='Gold'){
                                    //         $(".jstree-anchor").css("background", "#b48610");
                                    //     }
                                    //     if(value=='Platinum'){
                                    //         $(".jstree-anchor").css("background", "#a43f51");
                                    //     }
                                    // });

                                }).on("open_node.jstree", function () {
                                    // $.each(response, function (index, value) {
                                    //     if(value=='Gold'){
                                    //         $(".jstree-anchor").css("background", "#b48610");
                                    //     }
                                    //     if(value=='Platinum'){
                                    //         $(".jstree-anchor").css("background", "#a43f51");
                                    //     }
                                    // });
                                }).on("changed.jstree", function (e, data) {
                                    // if(data.selected.length) {
                                    //     alert('The selected node is: ' + data.instance.get_node(data.selected[0]).text);
                                    // }
                                });


                            }


                            else {
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
@endif


@extends('backend.layouts.master')

@section('content')


        <div class="page-container">
            <div class="page-content">
                <div class="container">
                    @if(session('successMemberRegister'))
                        <div class="alert alert-success alert-dismissible">
                            <h4><i class="icon fa fa-check"></i> {{session('successMemberRegister')}}</h4>
                        </div>
                    @else
                    <div class="page-head">
                        <div class="page-title">
                            <h1>{{__('dashboard.Add New Member')}}</h1>
                        </div>

                    </div>
                {{ Form::open([
                'url' => 'member/add-new-member',
                'id' => 'registerForm',
                'class' => 'horizontal-form ajax-post  border custom-border-1',
                'method'=> 'POST',
                'autocomplete' => 'off'
                ])
                }}

                <!-- <form action="#" class="horizontal-form"> -->
                    <div class="form-body">


                        @if(Auth::guard('admin')->check() && Request::is('admin/*'))
                            <input type="hidden" name="role" value="admin">
                        @else
                            <input type="hidden" name="role" value="member">
                        @endif

                        @include('backend.member.register.memberdata.register-personal-detail')

                        @include('backend.member.register.memberdata.register-nominee-detail')

                        @include('backend.member.register.memberdata.register-login-detail')

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @include('backend.member.register.memberdata.register-placement-detail')
                        </div>
                        <div class="col-md-6">
                            @include('backend.member.register.memberdata.register-payment-detail')
                        </div>

                    </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-actions right">
                                <button type="submit" class="btn blue">
                                    <i class="fa fa-check"></i> {{__('dashboard.Submit')}}
                                </button>
                            </div>
                        </div>
                    </div>
               @endif
                </div>

                {{ Form::close() }}

            </div>
        </div>
        </div>
        {{--@auth("admin")--}}

        {{--<script>--}}

        {{--$("#registerForm").addClass("no-secondary-password");--}}
        {{--</script>--}}
        {{--@endauth--}}

        <style type="text/css">
            .select2-container {
                border: 1px solid #ccc;

            }

            select .select2-design.select2-hidden-accessible {
                border: 0px solid #000 !important;
                /* clip: rect(0 0 0 0) !important; */
                height: 1px !important;
                margin: -1px !important;
                /* overflow: hidden !important; */

                position: absolute !important;
                width: 1px !important;
                /* z-index: 9999999; */
                right: 18px;
                top: 34px;
            }
        </style>
        <script>
            $('#confirmPsd').keyup(function (e) {
                var password=$('#password').val();
                var conPsd= $('#confirmPsd').val();
                if(password && password==conPsd){
                    $('#psdConfirm').html('<span style="color: green">Password Match Successfully</span>')
                }else{
                    $('#psdConfirm').html('<span style="color: red">Password Miss Match</span>')
                }

            });
            $('#tranConfirmPsd').keyup(function (e) {
                var tranPasswd=$('#tranPasswd').val();
                var tranConfirmPsd= $('#tranConfirmPsd').val();
                if(tranPasswd && tranPasswd==tranConfirmPsd){
                    $('#trnPsdConfirm').html('<span style="color: green">Password Match Successfully</span>')
                }else{
                    $('#trnPsdConfirm').html('<span style="color: red">Password Miss Match</span>')
                }

            });
        </script>
        <!-- <script src="{{ URL::asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script> -->
        <script src="{{ URL::asset('assets/custom/repo.js') }}" type="text/javascript"></script>
        <script src="{{ URL::asset('assets/custom/js/register.js') }}" type="text/javascript"></script>
@stop
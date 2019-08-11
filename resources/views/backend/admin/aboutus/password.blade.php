@extends('backend.layouts.master')

@section('content')
    <div class="page-container">
        <div class="page-content">
            <div class="container">
                <div class="page-head">
                    <div class="page-title">
                        <h1>{{__('dashboard.Change Password')}}</h1>
                    </div>
                </div>

                @include('fragments.message')
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{route('admin-customer-edit-password',$user->id)}}" method="post">
                            {{csrf_field()}}
                            <h3>{{__('dashboard.Login Password')}}</h3>
                            <div class="form-group required">
                                <label class="control-label">{{__('dashboard.Login Password')}}</label>
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                       id="password" required>

                                <span class="error-message"></span>
                            </div>
                            <div class="form-group required">
                                <label class="control-label">{{__('dashboard.Confirm Login Password')}}</label>
                                <input type="password" class="form-control" id="confirmPsd"
                                       placeholder="Confirm Password"
                                       name="password_confirmation" required>
                                <span id="psdConfirm"></span>
                                <span class="error-message"></span>
                            </div>

                            <button class="btn blue" type="submit">{{__('dashboard.Submit')}}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $('#confirmPsd').keyup(function (e) {
            var password = $('#password').val();
            var conPsd = $('#confirmPsd').val();
            if (password && password == conPsd) {
                $('#psdConfirm').html('<span style="color: green">Password Match Successfully</span>')
            } else {
                $('#psdConfirm').html('<span style="color: red">Password Miss Match</span>')
            }

        });
        // $('#tranConfirmPsd').keyup(function (e) {
        //     var tranPasswd = $('#tranPasswd').val();
        //     var tranConfirmPsd = $('#tranConfirmPsd').val();
        //     if (tranPasswd && tranPasswd == tranConfirmPsd) {
        //         $('#trnPsdConfirm').html('<span style="color: green">Password Match Successfully</span>')
        //     } else {
        //         $('#trnPsdConfirm').html('<span style="color: red">Password Miss Match</span>')
        //     }
        //
        // });
    </script>
    <script src="{{ URL::asset('assets/custom/repo.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/custom/js/register.js') }}" type="text/javascript"></script>
@stop
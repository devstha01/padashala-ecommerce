@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="heading">
                        <h2 class="title"><i class="fa fa-money"></i> {{__('front.Wallet transfer')}}</h2>
                    </div><!-- End .heading -->
                    @include('fragments.message')
                    <span style="display:none" id="ecash_payment">{{$user->getWallet->ecash_wallet??0}}</span>
                    <span style="display:none" id="evoucher_payment">{{$user->getWallet->evoucher_wallet??0}}</span>
                    <span id="are-you-sure-message" style="display: none"></span>
                    <hr>
                    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                        <?php
                        echo session('paymenttab');?>
                        <li class="nav-item">
                            <a class="nav-link {{session('qr')?'':'active'}}" id="manual-tab"
                               data-toggle="tab" href="#manual" role="tab"
                               aria-controls="manual" aria-selected="true">{{__('front.Manually Input User name')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{session('qr')?'active':''}}" id="qr-scan-tab"
                               data-toggle="tab" href="#qr-scan" role="tab"
                               aria-controls="qr-scan" aria-selected="false">{{__('front.Scan Qr code')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade {{session('qr')?'':'show active'}}" id="manual"
                             role="tabpanel" aria-labelledby="manual-tab">

                            <br>
                            <br>
                            {{ Form::open([
                            'url' => 'manual-transfer',
                            'class' => 'horizontal-form ajax-post-payment',
                            'method'=> 'POST'
                            ])   }}
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Make Transfer to')}}:</label>
                                            <input type="text" name="payment_to" id="payment-to"
                                                   class="form-control" placeholder="Enter Customer User name"
                                                   value="{{(old('payment_to')??'')}}">
                                            <span class="error-message"></span>
                                            {{--<span style="color: red">{{$errors->first('payment_to')??''}}</span>--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <br>
                                        <button class="btn" id="check-user">{{__('front.Check User')}}</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Transfer Method')}}</label>
                                            <select name="payment_method" class="form-control">
                                                <option value="">{{__('dashboard.Select Wallet')}}</option>
                                                <option value="ecash_wallet">{{__('front.E cash Wallet')}}</option>
                                                @if(Auth::user())
                                                    @if(Auth::user()->is_member === 1)
                                                        <option value="evoucher_wallet">{{__('front.E voucher Wallet')}}</option>
                                                    @endif
                                                @endif
                                            </select>
                                            <span class="error-message"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Amount')}}</label>
                                            <input type="text" name="amount"
                                                   class="form-control" value="{{(old('amount')??'')}}">
                                            <span class="error-message"></span>
                                            {{--<span style="color: red">{{$errors->first('amount')??''}}</span>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-actions right">
                                        <button type="submit" class="btn blue" id="are-you-sure-btn-transfer">
                                            <i class="fa fa-check"></i> {{__('front.Confirm Transfer')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            {{--<button type="submit" class="btn btn-primary"> {{__('front.Confirm Transfer')}}</button>--}}
                            {{ Form::close() }}

                        </div>
                        <div class="tab-pane fade {{session('qr')?'show active':''}}" id="qr-scan"
                             role="tabpanel" aria-labelledby="qr-scan-tab">

                            <script>
                                var qr_scan_url = "{!! route('qr-user-exist') !!}";
                            </script>
                            @include('fragments.qr-scan')
                            {{--<br>--}}
                            {{--<br>--}}
                            {{--<form action="{{route('qr-user-exist')}}" method="post"--}}
                            {{--enctype="multipart/form-data">--}}
                            {{--{{csrf_field()}}--}}

                            {{--<div class="row">--}}
                            {{--<div class="col-sm-6">--}}
                            {{--<div class="form-group">--}}
                            {{--<label>{{__('front.Upload Qr image')}}: </label>--}}
                            {{--<br>--}}
                            {{--<input type="file" name="qr_image">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-6">--}}
                            {{--<button type="submit" class="btn">{{__('front.Scan')}}</button>--}}
                            {{--@if(session('qr_status') === true)--}}
                            {{--<i class="fa fa-check fa-2x text-success"></i>--}}
                            {{--@elseif(session('qr_status') ===false)--}}
                            {{--<i class="fa fa-times fa-2x text-danger"></i>--}}
                            {{--@else--}}
                            {{--@endif--}}

                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</form>--}}


                            {{ Form::open([
                          'url' => 'qr-transfer',
                          'class' => 'horizontal-form ajax-post-qr-payment',
                          'method'=> 'POST'
                          ])   }}
                            <div class="form-body">

                                <div class="form-group">
                                    <label class="control-label">{{__('front.Make Payment to')}}:</label>
                                    <input type="text" name="qr_payment_to"
                                           class="form-control response-qr-user_name" readonly
                                           value="{{(session('qr_payment_to'))??''}}">
                                    <span class="error-message"></span>
                                    {{--<span style="color: red">{{$errors->first('qr_payment_to')??''}}</span>--}}
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Transfer Method')}}</label>
                                            <select name="qr_payment_method" class="form-control">
                                                <option value="">{{__('dashboard.Select Wallet')}}</option>
                                                <option value="ecash_wallet">{{__('front.E cash Wallet')}}</option>
                                                @if(Auth::user())
                                                    @if(Auth::user()->is_member === 1)
                                                        <option value="evoucher_wallet">{{__('front.E voucher Wallet')}}</option>
                                                    @endif
                                                @endif
                                            </select>
                                            <span class="error-message"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Amount')}}</label>
                                            <input type="text" name="qr_amount"
                                                   class="form-control" value="{{(old('qr_amount')??'')}}">
                                            <span class="error-message"></span>
                                            {{--<span style="color: red">{{$errors->first('qr_amount')??''}}</span>--}}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-actions right">
                                            <button type="submit" class="btn blue" id="are-you-sure-qr-btn-transfer">
                                                <i class="fa fa-check"></i> {{__('front.Confirm Transfer')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    @if(Auth::user())
                        <h3>{{__('front.Wallet Info')}}</h3>
                        <hr>
                        <table class="table border">
                            <tr>
                                <th colspan="2">{{__('front.Available wallet')}} :</th>
                            </tr>
                            <tr>
                                <td><b> <i class="fa fa-money"></i> {{__('front.E cash Wallet')}} :</b></td>
                                <td>${{$user->getWallet->ecash_wallet??'0.00'}}</td>
                            </tr>
                            @if(Auth::user()->is_member === 1)
                                <tr>
                                    <td><b> <i class="fa fa-money"></i> {{__('front.E voucher Wallet')}} :</b></td>
                                    <td>${{$user->getWallet->evoucher_wallet??'0.00'}}</td>
                                </tr>
                            @endif
                        </table>
                    @endif
                    <hr>
                    <h3>{{__('front.Transfer Info')}}</h3>
                    <div id="payment-info">
                        <label>{{__('front.Name')}} : <i id="user-name">{{session('name')??''}}</i></label>
                        <br>
                        <label>{{__('front.User Name')}} : <i
                                    id="user-user_name">{{session('user_name')??''}}</i></label>
                        <br>
                        <br>
                        @if($errors->first('success'))
                            <span class="alert alert-success"><i
                                        class="fa fa-check"></i> {{$errors->first('success')??''}}</span>
                        @endif
                        @if($errors->first('fail'))
                            <span class="alert alert-danger"><i
                                        class="fa fa-times"></i> {{$errors->first('fail')??''}}</span>
                        @endif

                    </div>


                </div>
            </div>
            <br>

        </div>
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('frontend/plugin/DataTables/datatables.css')}}">
    {{--<link href="http://mlm.local/assets/custom/css/global.css" rel="stylesheet" type="text/css"/>--}}
@endsection

@section('scripts')
    <script src="{{asset('frontend/assets/js/payment-form.js')}}"></script>
    <script src="{{asset('frontend/plugin/DataTables/datatables.js')}}"></script>
    <script>
        $(function () {
            $('#sample_2').DataTable();
        })
    </script>

    <script src="{{asset('frontend/custom/ajax-post.js')}}" type="text/javascript"></script>
    <script src="{{asset('frontend/custom/repo.js')}}" type="text/javascript"></script>

@endsection
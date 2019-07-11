@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="heading">
                        <h2 class="title"><i class="fa fa-money"></i> {{__('front.Payment')}}</h2>
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
                            'url' => 'manual-payment',
                            'class' => 'horizontal-form ajax-post-payment',
                            'method'=> 'POST'
                            ])   }}
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Make Payment to')}}:</label>
                                            <input type="text" name="payment_to" id="payment-to"
                                                   class="form-control" placeholder="Enter Merchant User name"
                                                   value="{{(old('payment_to')??'')}}">
                                            <span class="error-message"></span>
                                            {{--<span style="color: red">{{$errors->first('payment_to')??''}}</span>--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <br>
                                        <button class="btn" id="check-merchant">{{__('front.Check Merchant')}}</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">{{__('front.Payment Method')}}</label>
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
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label">{{__('front.Transaction Password')}}</label>--}}
                            {{--<input type="password" name="transaction_pass"--}}
                            {{--class="form-control">--}}
                            {{--<span class="error-message"></span>--}}
                            {{--<span style="color: red">{{$errors->first('transaction_pass')??''}}</span>--}}
                            {{--</div>--}}
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-actions right">
                                        <button type="submit" class="btn blue" id="are-you-sure-btn">
                                            <i class="fa fa-check"></i> {{__('front.Confirm Payment')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            {{--<button type="submit" class="btn btn-primary"> {{__('front.Confirm Payment')}}</button>--}}
                            {{ Form::close() }}

                        </div>
                        <div class="tab-pane fade {{session('qr')?'show active':''}}" id="qr-scan"
                             role="tabpanel" aria-labelledby="qr-scan-tab">
                            <script>
                                var qr_scan_url = "{!! route('qr-merchant-exist') !!}";
                            </script>
                            @include('fragments.qr-scan')


                            {{ Form::open([
                          'url' => 'qr-payment',
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
                                            <label class="control-label">{{__('front.Payment Method')}}</label>
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
                                            <button type="submit" class="btn blue" id="are-you-sure-qr-btn">
                                                <i class="fa fa-check"></i> {{__('front.Confirm Payment')}}
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
                    <h3>{{__('front.Payment Info')}}</h3>
                    <div id="payment-info">
                        <label>{{__('front.Name')}} : <i id="merchant-name">{{session('name')??''}}</i></label>
                        <br>
                        <label>{{__('front.User Name')}} : <i
                                    id="merchant-user_name">{{session('user_name')??''}}</i></label>
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
            <hr>

            <br>
            <h3>{{__('front.Payment Approval Requests')}} </h3>
            <table class="table table-hover">
                <tr>
                    <th>{{__('front.Username')}}</th>
                    <th>{{__('front.Wallet')}}</th>
                    <th>{{__('front.Amount')}}</th>
                    <th colspan="2">{{__('front.Action')}}</th>
                </tr>
                @forelse($request as $key=>$req)
                    <tr>
                        <td>{{$req->getToMerchant->user_name}}</td>
                        <td>{{$req->getWallet->detail}}</td>
                        <td>{{$req->amount}}</td>
                        <td>
                            <?php
                            switch ($req->getWallet->name) {
                                case 'ecash_wallet':
                                    $check_wallet = $user->getWallet->ecash_wallet;
                                    break;
                                case 'evoucher_wallet':
                                    $check_wallet = $user->getWallet->evoucher_wallet;
                                    break;
                                default;
                                    $check_wallet = 0;
                            }
                            ?>
                            @if($req->amount > $check_wallet)
                                <i>{{__('dashboard.Not enough balance')}}</i>
                            @else
                                {{ Form::open([
               'url' => 'accept-request/'.$req->id,
               'class' => 'horizontal-form ajax-post-approve',
               'method'=> 'POST'
               ])   }}
                                <button type="submit"
                                        data-wallet="{{$req->getWallet->detail}}"
                                        data-user="{{$req->getToMerchant->user_name}}"
                                        data-amount="{{$req->amount}}"
                                        class="btn blue" id="are-you-sure-approve-btn">
                                    <i class="fa fa-check"></i> {{__('front.Confirm Payment')}}
                                </button>
                                {{ Form::close() }}
                            @endif
                        </td>
                        <td>
                            {{ Form::open([
         'url' => 'decline-request/'.$req->id,
         'class' => 'horizontal-form ajax-post-approve',
         'method'=> 'POST'
         ])   }}
                            <button type="submit"
                                    data-wallet="{{$req->getWallet->detail}}"
                                    data-user="{{$req->getToMerchant->user_name}}"
                                    data-amount="{{$req->amount}}"
                                    class="btn blue" id="are-you-sure-decline-btn">
                                <i class="fa fa-check"></i> {{__('front.Decline')}}
                            </button>
                            {{ Form::close() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">{{__('front.No request available')}}.</td>
                    </tr>
                @endforelse
            </table>

        </div>
        <!-- End .row -->

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
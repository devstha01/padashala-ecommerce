@extends('backend.layouts.master')

@section('content')

    <style>
        .table.overview tr {
            border: 0;
        }

        .table.overview tr td {
            vertical-align: middle;
            border: 0;
        }

        .table.overview {
            margin-bottom: 0;
        }

        .table.overview > tbody > tr > td, .table.overview > tbody > tr > th, .table.overview > tfoot > tr > td, .table.overview > tfoot > tr > th, .table.overview > thead > tr > td, .table.overview > thead > tr > th {
            padding: 0;
        }
    </style>

    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{ __('dashboard.Wallet Transfer Request') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="page-content">
                        <div class="container">
                            @include('backend.member.wallet-card')
                            <span id="ecash_wallet_validation" style="display: none">{{$wallet->ecash_wallet}}</span>
                            <span id="evoucher_wallet_validation"
                                  style="display: none">{{$wallet->evoucher_wallet}}</span>
                            <span id="chip_validation" style="display: none">{{$wallet->chip}}</span>
                            <span id="r_point_validation" style="display: none">{{$wallet->r_point}}</span>
                            <span id="are-you-sure-message" style="display: none"></span>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row" style="margin: 0">
                            @include('backend.includes.flash')

                            <div class="col-md-4 portlet light">
                                <h4> {{__('dashboard.Member')}} <br><i
                                            class="fa fa-qrcode"></i> {{__('dashboard.Qr code')}}</h4>
                                <br>
                                <form action="{{route('qr-check-member')}}" method="post"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label>{{__('dashboard.Upload Qr image')}}: </label>
                                        <br>
                                        <br>
                                        <input type="file" name="qr_image">
                                        <br>
                                        <button class="btn">{{__('dashboard.Scan')}}</button>
                                    </div>
                                    @if(session('qr_status') === true)
                                        <br>
                                        <br>
                                        <i class="fa fa-check text-success">
                                            {{__('dashboard.Name')}}: {{session('name')}}</i>
                                        <br>
                                    @elseif(session('qr_status') ===false)
                                        <br>
                                        <br>
                                        <i class="fa fa-times text-danger"> {{__('dashboard.Member Not found')}}!
                                        </i>
                                    @else
                                    @endif
                                </form>


                            </div>
                            <div class="col-md-8" style="padding: 0 0 0 15px">
                                <div class="form-body">
                                    {{ Form::open([
                                    'url' => 'member/wallet-transfer-request/',
                                    'class' => 'horizontal-form ajax-post-transfer',
                                    'method'=> 'POST'
                                    ])   }}
                                    <h3 class="form-section">{{__('dashboard.Transfer Request')}}</h3>
                                    <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>

                                    <div class="form-group hide">
                                        <label class="control-label">{{__('dashboard.Current Amount')}}</label>
                                        {{ Form::text('current_amount',null, ['class'=> 'form-control', 'placeholder' => 'Current Amount' , 'id'=>"currentAmount",'readonly'=>true]) }}
                                        <span class="error-message"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Select Wallet')}}</label>
                                        <select name="wallet" class="form-control" id="selectWallet"
                                                placeholder="Choose a Wallet" tabindex="1">
                                            <option value="">{{__('dashboard.Select Wallet')}}</option>
                                            <option value="ecash_wallet">{{__('dashboard.ECash Wallet')}}</option>
                                            <option value="evoucher_wallet">{{__('dashboard.EVoucher Wallet')}}</option>
                                            <option value="r_point">{{__('dashboard.R Point')}}</option>
                                            <option value="chip">{{__('dashboard.Chip')}}</option>
                                        </select>
                                        <span class="error-message"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Request Amount')}}</label>
                                        {{ Form::number('amount',null, ['class'=> 'form-control', 'placeholder' => 'Transfer Amount' , 'id'=>"amountTransfer"]) }}
                                        <span class="error-message"></span>
                                    </div>

                                    <h3 class="form-section">{{__('dashboard.Request From')}}</h3>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Login ID')}}</label>
                                        {{ Form::text('member_id', session('qr_payment_to') , ['class'=> 'form-control', 'placeholder' => 'Login ID', 'id'=>"memberId"]) }}

                                        <span class="error-message"></span>
                                        <span class="input-group-btn">
                                <button id="genpassword" class="btn btn-success" type="button">
                                    <i class="fa fa-arrow-left fa-fw"></i> {{__('dashboard.Check')}}</button>
                                </span>
                                    </div>

                                    <div class="form-actions right">
                                        <button type="submit" class="btn blue" id="are-you-sure-btn">
                                            <i class="fa fa-check"></i>{{__('dashboard.Save')}}
                                        </button>
                                    </div>
                                    {{ Form::close() }}

                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light">
                                    <h3>{{__('dashboard.Request Approval')}}</h3>
                                    <table class="table table-hover">
                                        <tr>
                                            <th>{{__('dashboard.Username')}}</th>
                                            <th>{{__('dashboard.Wallet')}}</th>
                                            <th>{{__('dashboard.Amount')}}</th>
                                            <th colspan="2">{{__('dashboard.Action')}}</th>
                                        </tr>
                                        @forelse($request as $key=>$req)
                                            <tr>
                                                <td>{{$req->getToMember->user_name}}</td>
                                                <td>{{$req->getWallet->detail}}</td>
                                                <td>{{$req->amount}}</td>
                                                <td>
                                                    <?php
                                                    switch ($req->getWallet->name) {
                                                        case 'ecash_wallet':
                                                            $check_wallet = $wallet->ecash_wallet;
                                                            break;
                                                        case 'evoucher_wallet':
                                                            $check_wallet = $wallet->evoucher_wallet;
                                                            break;
                                                        case 'chip':
                                                            $check_wallet = $wallet->chip;
                                                            break;
                                                        case 'r_point':
                                                            $check_wallet = $wallet->r_point;
                                                            break;
                                                        default;
                                                            $check_wallet = 0;
                                                    }
                                                    ?>
                                                    @if($req->amount > $check_wallet)
                                                        <i>{{__('dashboard.Not enough balance')}}</i>
                                                    @else
                                                        {{ Form::open([
                                             'url' => 'member/wallet-transfer-approve/'.$req->id,
                                             'class' => 'horizontal-form ajax-post-transfer-approve',
                                             'method'=> 'POST'
                                             ])   }}
                                                        <button type="submit" data-wallet="{{$req->getWallet->detail}}"
                                                                data-user="{{$req->getToMember->user_name}}"
                                                                data-amount="{{$req->amount}}"
                                                                class="btn blue ajax-post-transfer-approve-btn"><i
                                                                    class="fa fa-check"></i>
                                                            {{__('dashboard.Approve')}}
                                                        </button>
                                                        {{Form::close()}}

                                                    @endif
                                                </td>
                                                <td>
                                                    {{ Form::open([
                                         'url' => 'member/wallet-transfer-decline/'.$req->id,
                                         'class' => 'horizontal-form ajax-post-transfer-approve',
                                         'method'=> 'POST'
                                         ])   }}
                                                    <button type="submit" data-wallet="{{$req->getWallet->detail}}"
                                                            data-user="{{$req->getToMember->user_name}}"
                                                            data-amount="{{$req->amount}}"
                                                            class="btn blue ajax-post-transfer-decline-btn"><i class="fa fa-times"></i>
                                                        {{__('dashboard.Decline')}}
                                                    </button>
                                                    {{Form::close()}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">{{__('dashboard.No request available')}}.</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        <!-- END CONTAINER -->
    </div>

    <script src="{{ URL::asset('assets/custom/js/wallet-transfer.js') }}" type="text/javascript"></script>

@stop

@section('stylesheets')
    <style>
        .qr-scan {
            position: relative;
        }
    </style>
@endsection


@section('scripts')
    <script>
        $('#are-you-sure-btn').on('click', function () {
            var from = $('input[name="member_id"]').val();
            var wallet = $('select[name="wallet"]').children("option:selected").html();
            var amount = $('input[name="amount"]').val();
            $('#are-you-sure-message').html('Wallet transfer request amount $' + amount + ' in ' + wallet + ' from ' + from);
        });


        $('.ajax-post-transfer-approve-btn').on('click', function () {
            var to = $(this).data('user');
            var wallet = $(this).data('wallet');
            var amount = $(this).data('amount');
            $('#are-you-sure-message').html('Wallet transfer amount $' + amount + ' in ' + wallet + ' to ' + to);
        });

        $('.ajax-post-transfer-decline-btn').on('click', function () {
            var to = $(this).data('user');
            var wallet = $(this).data('wallet');
            var amount = $(this).data('amount');
            $('#are-you-sure-message').html('Decline wallet transfer amount $' + amount + ' in ' + wallet + ' to ' + to);
        });

    </script>
@endsection

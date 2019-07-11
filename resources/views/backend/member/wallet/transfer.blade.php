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
                                <h1>{{ __('dashboard.Wallet Transfer') }}
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
                            <div class="col-md-6 portlet light">
                                <h3>{{__('dashboard.Member')}} <br><i
                                            class="fa fa-qrcode"></i> {{__('dashboard.Qr code')}}</h3>
                                <br>
                                <script>
                                    var qr_scan_url = "{!! route('qr-check-member') !!}";
                                </script>
                                @include('fragments.qr-scan')

                            </div>
                            <div class="col-md-6" style="padding: 0 0 0 15px">
                                <div class="form-body">
                                    {{ Form::open([
                                    'url' => 'member/wallet-transfer/',
                                    'class' => 'horizontal-form ajax-post-transfer',
                                    'method'=> 'POST'
                                    ])   }}
                                    @include('backend.includes.flash')
                                    <h3 class="form-section">{{__('dashboard.Transfer Wallet')}}</h3>
                                    <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>
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
                                    <div class="form-group hide">
                                        <label class="control-label">{{__('dashboard.Current Amount')}}</label>
                                        {{ Form::text('current_amount',null, ['class'=> 'form-control', 'placeholder' => 'Current Amount' , 'id'=>"currentAmount",'readonly'=>true]) }}
                                        <span class="error-message"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Transfer Amount')}}</label>
                                        {{ Form::number('amount',null, ['class'=> 'form-control', 'placeholder' => 'Transfer Amount' , 'id'=>"amountTransfer"]) }}
                                        <span class="error-message"></span>
                                    </div>
                                    <h3 class="form-section">{{__('dashboard.Transfer To')}}</h3>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Login ID')}}</label>
                                        {{ Form::text('member_id', session('qr_payment_to') , ['class'=> 'form-control response-qr-user_name', 'placeholder' => 'Login ID', 'id'=>"memberId"]) }}

                                        <span class="error-message"></span>
                                        <span class="input-group-btn">
                                <button id="genpassword" class="btn btn-success" type="button">
                                    <i class="fa fa-arrow-left fa-fw"></i> {{__('dashboard.Check')}}</button>
                                </span>
                                    </div>

                                    <div class="form-actions right">
                                        <button type="submit" class="btn blue" id="are-you-sure-btn">
                                            <i class="fa fa-check"></i>{{__('dashboard.Submit')}}
                                        </button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
            </div>
            <!-- END CONTAINER -->
        </div>
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
            var to = $('input[name="member_id"]').val();
            var wallet = $('select[name="wallet"]').children("option:selected").html();
            var amount = $('input[name="amount"]').val();
            $('#are-you-sure-message').html('Wallet transfer amount $' + amount + ' in ' + wallet + ' to ' + to);
        });
    </script>
@endsection

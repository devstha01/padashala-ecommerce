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
                                <h1>Wallet Transfer
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        @include('backend.merchant.merchant-wallet-card')
                        <div class="row" style="margin: 0">
                            @include('backend.includes.flash')
                            <span id="ecash_wallet_validation" style="display: none">{{$wallet->ecash_wallet}}</span>
                            <span id="are-you-sure-message" style="display: none"></span>

                            <div class="col-md-6 portlet light">
                                <h4> Customer <br><i class="fa fa-qrcode"></i> {{__('dashboard.Qr code')}}</h4>
                                <br>
                                <script>
                                    var qr_scan_url = "{!! route('qr-check-customer') !!}";
                                </script>
                                @include('fragments.qr-scan')


                            </div>
                            <div class="col-md-6">
                                <div class="form-body">
                                    {{ Form::open([
                                    'url' => 'merchant/payment/wallet-transfer/',
                                    'class' => 'horizontal-form ajax-post-merchant-transfer',
                                    'method'=> 'POST'
                                    ])   }}
                                    <h3 class="form-section">Wallet Transfer - Customer</h3>
                                    <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>

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
                                <button id="genmerchant" class="btn btn-success" type="button">
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
            var to = $('input[name="member_id"]').val();
            // var wallet = $('select[name="wallet"]').children("option:selected").html();
            var amount = $('input[name="amount"]').val();
            $('#are-you-sure-message').html('Wallet transfer amount $' + amount + ' to ' + to);
        });
    </script>
@endsection
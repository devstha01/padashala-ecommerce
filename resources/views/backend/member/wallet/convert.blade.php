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
                                <h1>{{__('dashboard.Wallet Convert')}}
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            @include('backend.member.wallet-card')
                        </div>
                    </div>
                    <div class="container">
                        <span id="ecash_wallet_validation" style="display: none">{{$wallet->ecash_wallet}}</span>
                        <span id="evoucher_wallet_validation" style="display: none">{{$wallet->evoucher_wallet}}</span>
                        <span id="are-you-sure-message" style="display: none"></span>

                        {{ Form::open([
                        'url' => 'member/wallet-convert/',
                        'class' => 'horizontal-form ajax-post-convert',
                        'method'=> 'POST'
                        ])   }}
                        <div class="form-body">
                            @include('backend.includes.flash')
                            <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">{{__('dashboard.Convert From')}}</h3>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Select Wallet')}}</label>
                                        <select name="transferto" class="form-control" id="transferto"
                                                placeholder="Choose a Wallet" tabindex="1">
                                            <option value="">{{__('dashboard.Select Wallet')}}</option>
                                            <option value="ecash_wallet">{{__('dashboard.ECash Wallet')}}</option>
                                            <option value="evoucher_wallet">{{__('dashboard.EVoucher Wallet')}}</option>
                                        </select>
                                        <span class="error-message"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">{{__('dashboard.Convert To')}}</h3>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Select Wallet')}}</label>
                                        <select name="wallet" class="form-control" id="selectWallet" tabindex="1">
                                            <option value="">{{__('dashboard.Select Wallet')}}</option>
                                            <option value="evoucher_wallet">{{__('dashboard.EVoucher Wallet')}}</option>
                                            <option value="r_point">{{__('dashboard.R Point')}}</option>
                                            <option value="chip">{{__('dashboard.Chip')}}</option>
                                        </select>
                                        <span class="error-message"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Convert Amount')}}</label>
                                        {{ Form::number('amount',null, ['class'=> 'form-control', 'placeholder' => 'Amount', 'id'=>"amountTransfer"]) }}
                                        <span class="error-message"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="row">--}}
                        {{--<div class="col-md-6">--}}
                        {{--<div class="form-group">--}}
                        {{--<label class="control-label">Transaction Password</label>--}}
                        {{--<input type="password" name="transaction_password" class="form-control" placeholder="Transaction Password" id="transactionPassword">--}}
                        {{--<span class="error-message"></span>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-actions right">
                                    <button type="submit" class="btn blue" id="are-you-sure-btn">
                                        <i class="fa fa-check"></i> {{__('dashboard.Save')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>

                        {{ Form::close() }}
                    </div>


                </div>
            </div>


        </div>
        <!-- END CONTAINER -->
    </div>

    {{--<script src="{{ URL::asset('backend/assets/custom/js/wallet-transfer.js') }}" type="text/javascript"></script>--}}

    <script>
        $("#transferto").on('change', function () {

            if ($(this).val() == 'ecash_wallet') {

                $("#selectWallet").html(" ' <option value=\"\">Select Wallet</option>\n" +
                    "<option value=\"evoucher_wallet\">Voucher Wallet</option>\n" +
                    "<option value=\"r_point\">R Point</option>\n" +
                    "<option value=\"chip\">Chip</option> ' ");
            }
            else if ($(this).val() == "evoucher_wallet") {

                $("#selectWallet").html("' <option value=\"\">Select Wallet</option>\\n\" +\n" +
                    "<option value=\"r_point\">R Point</option>\n" +
                    "<option value=\"chip\">Chip</option> ' ");
            }
        });

        $('#are-you-sure-btn').on('click', function () {
            var from = $('select[name="transferto"]').children("option:selected").html();
            var to = $('select[name="wallet"]').children("option:selected").html();
            var amount = $('input[name="amount"]').val();
            $('#are-you-sure-message').html('Wallet convert amount $' + amount + ' from ' + from + ' to ' + to);
        });
    </script>

@stop

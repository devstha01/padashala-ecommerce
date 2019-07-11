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
                                <h1>{{__('dashboard.Shopping Point Transform')}}
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
                        @include('fragments.message')
                        <span id="shop_point_validation" style="display: none">{{$wallet->shop_point}}</span>
                        <span id="are-you-sure-message" style="display: none"></span>
                        {{ Form::open([
                                                 'url' => 'member/shopping-withdraw/',
                                                 'class' => 'horizontal-form ajax-post-shopping',
                                                 'method'=> 'POST'
                                                 ])   }}
                        <div class="form-body">
                            @include('backend.includes.flash')
                            <h3 class="form-section">{{__('dashboard.Shopping Point Transform')}}</h3>
                            <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Shopping Point Amount')}}</label>
                                        {{ Form::text('amount',null, ['class'=> 'form-control', 'placeholder' => 'Type Shopping Point Amount' , 'id'=>"Amount"]) }}
                                        <span class="error-message"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">{{__('dashboard.Transform To')}}</h3>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <span>{{__('dashboard.Cash Wallet')}}</span><br>
                                            <span id="receive-cash"
                                                   style="padding: 2px 20px;background:white;border:1px solid whitesmoke">0</span>
                                        </div>
                                        <div class="col-xs-4">
                                            <span>{{__('dashboard.Voucher Wallet')}}</span><br>
                                            <span id="receive-voucher"
                                                   style="padding: 2px 20px;background:white;border:1px solid whitesmoke">0</span>
                                        </div>
                                        <div class="col-xs-4">
                                            <span>{{__('dashboard.Chips')}}</span><br>
                                            <span id="receive-bid"
                                                  style="padding: 2px 20px;background:white;border:1px solid whitesmoke">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Remarks')}}</label>
                                {{ Form::text('remarks',null, ['class'=> 'form-control', 'placeholder' => 'Remarks' , 'id'=>"remarks"]) }}
                                <span class="error-message"></span>
                            </div>

                            <div class="form-actions right">
                                <button type="submit" id="are-you-sure-btn" class="btn blue">
                                    <i class="fa fa-check"></i>{{__('dashboard.Submit')}}
                                </button>
                            </div>
                        </div>

                        {{ Form::close() }}

                        <br>
                        <br>
                        <br>
                    </div>
                </div>


            </div>
            <!-- END CONTAINER -->
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $('input#Amount').on('keyup', function () {
            var shop_point = $(this).val();
            var main = $(this);
            $.get(
                serverCustom.base_url + '/member/shopping-calculate',
                {amount: shop_point},
                function (data) {
                    $('#receive-cash').html(data.data.cash);
                    $('#receive-voucher').html(data.data.voucher);
                    $('#receive-bid').html(data.data.bid);
                    // if (data.status === true) {
                    //     main.removeClass('red');
                    //     $('#submit-btn').prop('disabled', false);
                    // }
                    // else {
                    //     $('#submit-btn').prop('disabled', true);
                    //     main.addClass('red');
                    // }
                }
            );
        });

        $('#are-you-sure-btn').on('click', function () {
            var shop_amount = $('input#Amount').val();
            $('#are-you-sure-message').html('Shopping point withdraw amount $' + shop_amount);
        });

    </script>
@endsection
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
                                <h1>{{__('dashboard.Withdrawal Request')}}
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            @include('backend.member.wallet-card')
                            <span id="ecash_wallet_validation" style="display: none">{{$wallet->ecash_wallet}}</span>
                            <span id="are-you-sure-message" style="display: none"></span>
                        </div>
                    </div>

                    <div class="container">
                        @include('fragments.message')
                        {{ Form::open([
                              'url' => 'member/wallet-withdraw/',
                              'class' => 'horizontal-form ajax-post-withdraw',
                              'method'=> 'POST'
                              ])   }}
                        <div class="form-body">
                            @include('backend.includes.flash')
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">{{__('dashboard.Withdrawal Request')}}</h3>
                                    <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>
                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Withdrawal Request Amount')}}</label>
                                        {{ Form::text('amount',null, ['class'=> 'form-control', 'placeholder' => __('dashboard.Withdrawal Request Amount') , 'id'=>"Amount"]) }}
                                        <input type="hidden" name="amount_status" id="amount_status" value="true">
                                        <input type="hidden" name="min_amount" id="min_amount" value="{{$min}}">
                                        <input type="hidden" name="max_amount" id="max_amount" value="{{$max}}">
                                        <span class="error-message"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h3 class="form-section">{{__('dashboard.Bank Detail')}}</h3>
                                    <div class="form-group hide">
                                        <label class="control-label">{{__('dashboard.Contact Number')}}</label>
                                        {{ Form::text('contact_number',$bank->contact_number, ['class'=> 'form-control', 'placeholder' => 'Type your Contact Number' , 'id'=>"contactNumber",'disabled']) }}
                                        <span class="error-message"></span>
                                    </div>

                                    <div class="form-group hide">
                                        <label class="control-label">{{__('dashboard.Bank Name')}}</label>
                                        {{ Form::text('bank_name',$bank->bank_name, ['class'=> 'form-control', 'placeholder' => 'Type Bank Name' , 'id'=>"bankName",'disabled']) }}
                                        <span class="error-message"></span>
                                    </div>
                                    <div class="form-group hide">
                                        <label class="control-label">{{__('dashboard.Account Holder Name')}}</label>
                                        {{ Form::text('acc_name',$bank->acc_name, ['class'=> 'form-control', 'placeholder' => 'Type your Account Name' , 'id'=>"accName",'disabled']) }}
                                        <span class="error-message"></span>
                                    </div>
                                    <div class="form-group hide">
                                        <label class="control-label">{{__('dashboard.Account No')}}#</label>
                                        {{ Form::text('acc_number',$bank->acc_number, ['class'=> 'form-control', 'placeholder' => 'Type youe Account Number' , 'id'=>"accNumber",'disabled']) }}
                                        <span class="error-message"></span>
                                    </div>

                                    <table class="table  table-bordered">
                                        <tr>
                                            <td>{{__('dashboard.Bank Name')}}</td>
                                            <td>{{$bank->bank_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('dashboard.Account Holder Name')}}</td>
                                            <td>{{$bank->acc_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('dashboard.Account No')}}</td>
                                            <td>{{$bank->acc_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('dashboard.Contact Number')}}</td>
                                            <td>{{$bank->contact_number}}</td>
                                        </tr>
                                    </table>

                                    <div class="text-right">
                                        <a href="{{route('edit-bank',Auth::user()->id)}}"
                                           class="btn blue">{{__('dashboard.Edit Bank Info')}}</a>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Remarks')}}</label>
                                {{ Form::text('remarks',null, ['class'=> 'form-control', 'placeholder' => 'Remarks' , 'id'=>"remarks"]) }}
                                <span class="error-message"></span>
                            </div>

                            <div class="form-actions right">
                                <button type="submit" class="btn blue" id="are-you-sure-btn">
                                    <i class="fa fa-check"></i>{{__('dashboard.Submit')}}
                                </button>
                            </div>
                        </div>

                        {{ Form::close() }}
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <!-- END CONTAINER -->
        </div>
    </div>
@stop
@section('stylesheets')
    <style>
        .border-error {
            background: #f1b0b7;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('#are-you-sure-btn').on('click', function () {
                var amount = $('input[name="amount"]').val();
                $('#are-you-sure-message').html('Withdraw request amount $' + amount);
            });

        })
    </script>
@endsection
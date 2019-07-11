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
                                <h1>{{__('dashboard.Dividend Transform')}}
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
                        <div class="row">
                            <div class="col-md-6">

                                <span id="dividend_validation" style="display: none">{{$current_amount}}</span>
                                <span id="are-you-sure-message" style="display: none"></span>

                                {{ Form::open([
                                'url' => 'member/dividend-withdraw/',
                                'class' => 'horizontal-form ajax-post-dividend-transform',
                                'method'=> 'POST'
                                ])   }}
                                <div class="form-body">
                                    @include('backend.includes.flash')
                                    <h3 class="form-section">{{__('dashboard.Dividend Transform')}}</h3>
                                    <h5 class="scroll-top-profile-page">{{__('dashboard.Required Fields')}}</h5>

                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Available Dividend Amount')}}</label>
                                        {{ Form::number('current_amount',$current_amount, ['class'=> 'form-control','disabled'=>'true']) }}
                                        <span class="error-message"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">{{__('dashboard.Dividend Amount')}}</label>
                                        {{ Form::number('amount',null, ['class'=> 'form-control', 'placeholder' => 'Amount', 'id'=>"amountTransfer"]) }}
                                        <span class="error-message"></span>
                                    </div>
                                </div>
                                <div class="form-actions right">
                                    <button type="submit" class="btn blue" id="are-you-sure-btn">
                                        <i class="fa fa-check"></i> {{__('dashboard.Submit')}}
                                    </button>
                                </div>
                                <br>

                                {{ Form::close() }}
                            </div>


                            <div class="col-md-6">
                                <div class="portlet light">
                                    <div class="portlet-body">
                                        <h3>{{__('dashboard.Dividend Transform Record')}}</h3>
                                        <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                               id="sample_1">
                                            <thead>
                                            <tr>
                                                <th>{{__('dashboard.SN')}}</th>
                                                <th>{{__('dashboard.Amount')}}</th>
                                                <th>{{__('dashboard.Date')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($reports as $key=>$report)
                                                <tr>
                                                    <td>{{++$key}}
                                                    <td>{{$report->amount}}</td>
                                                    <td>{{\Carbon\Carbon::parse($report->created_at)->diffForHumans()}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- END CONTAINER -->
    </div>

    <script>
        $('#are-you-sure-btn').on('click', function () {
            var amount = $('input[name="amount"]').val();
            $('#are-you-sure-message').html('Dividend transform amount $' + amount + ' to Cash Wallet');
        });
    </script>

@stop

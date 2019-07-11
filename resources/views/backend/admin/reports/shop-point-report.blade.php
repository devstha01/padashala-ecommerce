@extends('backend.layouts.master')

@section('content')
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
                                <h1> {{__('dashboard.Shopping Point Transform Report')}}
                                </h1>
                            </div>

                        </div>
                    </div>

                    <div class="container">

                    <div class="portlet light">
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                   id="sample_1">
                                <thead>
                                <tr>
                                    <th>{{__('dashboard.SN')}}</th>
                                    <th>{{__('dashboard.Member Id')}}</th>
                                    <th>{{__('dashboard.Shopping Point')}}</th>
                                    <th>{{__('dashboard.Cash Wallet')}}</th>
                                    <th>{{__('dashboard.Voucher Wallet')}}</th>
                                    <th>{{__('dashboard.Chip')}}</th>
                                    <th>{{__('dashboard.Withdraw on')}}</th>
                                    <th>{{__('dashboard.Remarks')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports as $key=>$report)
                                    <tr>
                                        <td>{{++$key}}
                                        <td>{{$report->getMember->user_name}}</td>
                                        <td>{{$report->shop_point}}</td>
                                        <td>${{$report->ecash_wallet}}</td>
                                        <td>${{$report->evoucher_wallet}}</td>
                                        <td>{{$report->chip}}</td>
                                        <td>{{$report->created_at}}
                                        <td>{{$report->remarks}}
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

    <!-- END CONTAINER -->
@endsection

@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@stop
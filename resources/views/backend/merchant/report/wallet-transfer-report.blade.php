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
                                <h1> {{__('dashboard.Wallet Transfer Report')}}
                                </h1>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                        {{--<div class="portlet light">--}}
                            {{--<div class="portlet-body">--}}
                                {{--<h3>{{__('dashboard.Transferred to member')}}</h3>--}}
                                {{--<table class="table table-striped table-bordered table-hover dataTable dtr-inline"--}}
                                       {{--id="sample_2">--}}
                                    {{--<thead>--}}
                                    {{--<tr>--}}
                                        {{--<th>{{__('dashboard.SN')}}</th>--}}
                                        {{--                                    <th>{{__('dashboard.Member Id')}}</th>--}}
                                        {{--<th>{{__('dashboard.To Member Id')}}</th>--}}
                                        {{--<th>{{__('dashboard.Wallet')}}</th>--}}
                                        {{--<th>{{__('dashboard.Amount')}}</th>--}}
                                        {{--<th>{{__('dashboard.Status')}}</th>--}}
                                        {{--<th>{{__('dashboard.Remarks')}}</th>--}}
                                    {{--</tr>--}}
                                    {{--</thead>--}}
                                    {{--<tbody>--}}
                                    {{--@foreach($reports as $key=>$report)--}}
                                        {{--<tr>--}}
                                            {{--<td>{{++$key}}</td>--}}
                                            {{--<td>{{$report->getToMember->user_name}}</td>--}}
                                            {{--<td>{{$report->getWallet->detail}}</td>--}}
                                            {{--<td>{{$report->amount}}</td>--}}
                                            {{--<td>--}}
                                                {{--{{$report->status?__('dashboard.True'):__('dashboard.Cancelled')}}</td>--}}
                                            {{--<td>{{$report->remarks}}</td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}
                                    {{--</tbody>--}}
                                {{--</table>--}}
                            {{--</div>--}}

                        {{--</div>--}}

                        <div class="portlet light">
                            <div class="portlet-body">
                                <h3>{{__('dashboard.Transferred to merchant')}}</h3>
                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                       id="sample_3">
                                    <thead>
                                    <tr>
                                        <th>{{__('dashboard.SN')}}</th>
                                        {{--                                    <th>{{__('dashboard.Member Id')}}</th>--}}
                                        <th>{{__('dashboard.To Merchant Id')}}</th>
                                        <th>{{__('dashboard.Wallet')}}</th>
                                        <th>{{__('dashboard.Amount')}}</th>
                                        <th>{{__('dashboard.Status')}}</th>
                                        <th>{{__('dashboard.Remarks')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($from_reports as $key=>$report)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$report->getToMerchant->user_name}}</td>
                                            <td>{{$report->getWallet->detail}}</td>
                                            <td>{{$report->amount}}</td>
                                            <td>
                                                {{$report->status?__('dashboard.True'):__('dashboard.Cancelled')}}</td>
                                            <td>{{$report->remarks}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="portlet light">
                            <div class="portlet-body">
                                <h3>{{__('dashboard.Received from merchant')}}</h3>
                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                       id="sample_1">
                                    <thead>
                                    <tr>
                                        <th>{{__('dashboard.SN')}}</th>
                                        {{--                                    <th>{{__('dashboard.Member Id')}}</th>--}}
                                        <th>{{__('dashboard.From Merchant Id')}}</th>
                                        <th>{{__('dashboard.Wallet')}}</th>
                                        <th>{{__('dashboard.Amount')}}</th>
                                        <th>{{__('dashboard.Status')}}</th>
                                        <th>{{__('dashboard.Remarks')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($to_reports as $key=>$report)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$report->getFromMerchant->user_name}}</td>
                                            <td>{{$report->getWallet->detail}}</td>
                                            <td>{{$report->amount}}</td>
                                            <td>
                                                {{$report->status?__('dashboard.True'):__('dashboard.Cancelled')}}</td>
                                            <td>{{$report->remarks}}</td>
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
        <!-- END CONTAINER -->
    </div>
@endsection



@section('stylesheets')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@stop
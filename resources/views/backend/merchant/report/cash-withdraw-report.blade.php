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
                                <h1>{{__('dashboard.Cash Withdrawal Report')}}
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
                                    {{--<th>{{__('dashboard.Member Id')}}</th>--}}
                                    <th>{{__('dashboard.Account Name')}}</th>
                                    <th>{{__('dashboard.Contact Number')}}</th>
                                    <th>{{__('dashboard.Bank Name')}}</th>
                                    <th>{{__('dashboard.Account No')}}</th>
                                    <th>{{__('dashboard.Amount')}}</th>
                                    <th>{{__('dashboard.Withdraw Date')}}</th>
                                    <th>{{__('dashboard.Status')}}</th>
                                    <th>{{__('dashboard.Remarks')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($reports as $key=>$report)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        {{--<td>{{$report->getuser->user_name}}</td>--}}
                                        <td>{{$report->acc_name}}</td>
                                        <td>{{$report->contact_number}}</td>
                                        <td>{{$report->bank_name}}</td>
                                        <td>{{$report->acc_number}}</td>
                                        <td>{{$report->amount}}</td>
                                        <td>{{$report->created_at}}</td>
                                        <td>
                                            @if($report->flag ===0 & $report->status ===1)
                                                {{__('dashboard.Pending')}}
                                            @elseif($report->flag ===1 & $report->status ===0)
                                                {{__('dashboard.Failed')}}
                                            @elseif($report->flag ===1 & $report->status ===1)
                                                {{__('dashboard.Success')}}
                                            @endif
                                        </td>
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
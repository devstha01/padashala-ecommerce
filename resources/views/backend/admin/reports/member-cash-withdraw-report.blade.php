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
                                <h1>{{__('dashboard.Member Cash Withdrawal Report')}}
                                </h1>
                            </div>

                        </div>
                    </div>

                    <div class="container">
                        @include('fragments.message')
                        <div class="portlet light">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                       id="sample_1">
                                    <thead>
                                    <tr>
                                        <th>{{__('dashboard.SN')}}</th>
                                        <th>{{__('dashboard.Member Login Id')}}</th>
                                        <th>{{__('dashboard.Contact Number')}}</th>
                                        <th>{{__('dashboard.Withdraw Amount')}}</th>
                                        <th>{{__('dashboard.Withdraw Date')}}</th>
                                        <th>{{__('dashboard.Remarks')}}</th>
                                        <th>{{__('dashboard.Updated By')}}</th>
                                        <th>{{__('dashboard.Updated On')}}</th>
                                        <th>{{__('dashboard.Status')}}</th>
                                        <th>{{__('dashboard.Detail')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($reports as $key=>$report)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$report->getuser->user_name}}</td>
                                            <td>{{$report->contact_number}}</td>
                                            <td>{{$report->amount}}</td>
                                            <td>{{$report->created_at}}</td>
                                            <td>{{$report->remarks}}</td>
                                            <td>
                                                {{$report->withdraw_date?$report->admin->name:' - '}}
                                            </td>
                                            <td>{{$report->withdraw_date??' - '}}</td>
                                            <td>
                                                {{$report->flag?__('dashboard.Done'):__('dashboard.Pending')}}
                                                {{--</td>--}}
                                                {{--<td>--}}
                                                |
                                                {{$report->status?__('dashboard.True'):__('dashboard.Cancelled')}}
                                            </td>
                                            <td>
                                                <a href="{{route('admin-member-cash-withdraw-detail',$report->id)}}"
                                                   class="btn blue">Detail</a>
                                            </td>
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
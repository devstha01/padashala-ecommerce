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
                                <h1> Generation Wise Bonus Report
                                </h1>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                        <div class="portlet light">
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                   id="sample_2">
                                <thead>
                                <tr>
                                    <th>{{__('dashboard.SN')}}</th>
                                    {{--                                    <th>{{__('dashboard.Member Id')}}</th>--}}
                                    <th>Generation</th>
                                    <th>Bonus Value</th>
                                    <th>Member Created</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reports as $key=>$report)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$report->generation}}</td>
                                        <td>{{$report->bonus_value}}</td>
                                        <td>{{$report->getMember->name}} ({{ $report->getMember->user_name}} )</td>
                                        <td>{{$report->created_at}}</td>

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
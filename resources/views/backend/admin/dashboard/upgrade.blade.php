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
                                <h1>{{__('dashboard.Upgrade Membership')}}
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
                                        <th>{{__('dashboard.Name')}}</th>
                                        <th>{{__('dashboard.Contact Number')}}</th>
                                        <th>{{__('dashboard.Email')}}</th>
{{--                                        <th>{{__('dashboard.Status')}}</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($users as $key=>$user)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->contact_number}}</td>
                                            <td>{{$user->email}}</td>
{{--                                            <td>{{$user->status}}</td>--}}
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